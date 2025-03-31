<?php

namespace Drupal\landportal_virtuoso;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Service for querying to virtuoso.
 *
 * @todo This is a preliminary version of this code, ideally we should be able to encapsulate all these redundancies into a more systematic way.
 * @todo First pass could be to convert the indicators into config entities, driven by a plugin manager/plugin system that runs the query based in the information from the config entity, such as indicator value, namespaces, etc. One challenge is to tell apart how much info we want and in what order.
 * @todo Handle this with the indicator pages.
 *
 * @package Drupal\landportal_virtuoso
 */
class IndicatorsService {

  use StringTranslationTrait;

  /**
   * Drupal\landportal_virtuoso\ConnectionService definition.
   *
   * @var \Drupal\landportal_virtuoso\ConnectionService
   */
  protected $connection;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new IndicatorsService object.
   *
   * @param \Drupal\landportal_virtuoso\ConnectionService $connection_service
   *   Virtuoso connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(ConnectionService $connection_service, EntityTypeManagerInterface $entity_type_manager, RendererInterface $renderer) {
    $this->connection = $connection_service;
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * Returns land area.
   *
   * @param string $iso3
   *   ISO3 code.
   *
   * @return array
   *   Array of land area data.
   */
  public function getLandArea($iso3) {
    $indicator_id = 'FAO-6601-5110';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Land Area'),
      'unit' => 'ha',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        // Value is returned as 'thousands of has' so we multiply x 1000.
        $data['value'] = round($result[0]['value']->getValue() * 1000, 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns total population.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Data array of total population.
   */
  public function getTotalPopulation($iso3) {
    $indicator_id = 'WB-SP.POP.TOTL';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Total population'),
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = $result[0]['value']->getValue();
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns GINI index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of GNI Index data.
   */
  public function getGiniIndex($iso3) {
    $indicator_id = 'WB-SI.POV.GINI';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('GINI Index'),
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns GDP/Capita index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of GDP/Capita Index data.
   */
  public function getGdpCapita($iso3) {
    $indicator_id = 'WB-NY.GDP.PCAP.PP.KD';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('GDP/Capita'),
      'unit' => 'USD',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Urban population index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of urban population data.
   */
  public function getUrbanPopulation($iso3) {
    $indicator_id = 'WB-SP.URB.TOTL.IN.ZS';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Urban population'),
      'unit' => '%',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Agricultural land index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of agricultural land data.
   */
  public function getAgriculturalLand($iso3) {
    $indicator_id = 'FAO-X-6610-5110';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Agricultural land'),
      'unit' => '%',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Perceived Tenure Security index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of index data.
   */
  public function getPerceivedTenureSecurity($iso3) {
    $indicator_id = 'PRINDEX-PRINDEX2018.O.TS';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Perceived tenure security'),
      'unit' => '%',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = $result[0]['value']->getValue();
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Primary Forests index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of index value data.
   */
  public function getPrimaryForests($iso3) {
    $indicator_id = 'FAO-6714-5110';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Primary forests'),
      'unit' => 'ha',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/FAO-6714-5110> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        // Value is returned as 'thousands of has' so we multiply x 1000.
        $data['value'] = $result[0]['value']->getValue() * 1000;
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Women owning land index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of index value data.
   */
  public function getWomenOwningLand($iso3) {
    $indicator_id = 'WB-SG.OWN.LDAJ.FE.ZS';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Women owning land'),
      'unit' => '%',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Returns Area in deals index.
   *
   * @param string $iso3
   *   ISO3 code of region.
   *
   * @return array
   *   Array of index value data.
   */
  public function getAreaInDeals($iso3) {
    $indicator_id = 'LM-HA1';
    $data = $this->initializeData($indicator_id, [
      'title' => $this->t('Area in deals'),
      'unit' => 'ha',
    ]);

    try {
      $namespaces = [
        'cex' => 'http://purl.org/weso/ontology/computex#',
        'time' => 'http://www.w3.org/2006/time#',
        'qb' => 'http://purl.org/linked-data/cube#',
        'rdfs' => 'http://www.w3.org/2000/01/rdf-schema#',
        'dct' => 'http://purl.org/dc/terms/',
        'sdmx-attribute' => 'http://purl.org/linked-data/sdmx/2009/attribute#',
      ];
      $result = $this->connection->getResults($namespaces,
        'SELECT ?indicator ?country ?time ?value ?note
        FROM <http://data.landportal.info>
        FROM <http://countries.landportal.info>
        FROM <http://indicators.landportal.info>
        WHERE {
        ?obs cex:ref-indicator ?bindicator;
        cex:ref-area ?bcountry;
        cex:ref-time ?btime;
        cex:value ?bvalue .
        OPTIONAL { ?obs rdfs:comment ?bnote }
        VALUES ?bindicator { <http://data.landportal.info/indicator/' . $indicator_id . '> }
        BIND(REPLACE(STR(?bindicator), "http://data.landportal.info/indicator/", "") AS ?indicator)
        BIND(REPLACE(STR(?bcountry), "http://data.landportal.info/geo/", "") AS ?country)
        BIND(REPLACE(STR(?btime), "http://data.landportal.info/time/", "") AS ?time)
        BIND(STR(IF(DATATYPE(?bvalue)=xsd:string, STR(?bvalue), ?bvalue)) AS ?value)
        BIND(STR(IF(DATATYPE(?bnote)=xsd:string, STR(?bnote), ?bnote)) AS ?note)
        FILTER ( ?country = "' . $iso3 . '" )
        } ORDER BY DESC(?time)
        LIMIT 1'
      );

      if (!empty($result) && isset($result[0]['value'])) {
        $data['value'] = round($result[0]['value']->getValue(), 2);
        $data['description'] = $this->buildDescription($data, $result[0]);
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_virtuoso', $e);
    }

    return $data;
  }

  /**
   * Set the default values for the data display.
   *
   * @param string $indicator_id
   *   Id of the indicator term.
   * @param array $data
   *   Default data.
   *
   * @return array|string[]
   *   Initial array of data to display.
   */
  protected function initializeData(string $indicator_id, array $data = []) {
    $data += [
      'title' => '',
      'label' => '',
      'value' => '',
      'url' => '',
      'description' => '',
      'unit' => '',
      'taxonomy_term' => NULL,
    ];
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $terms = $storage->loadByProperties([
      'vid' => 'indicators',
      //'id' => $indicator_id,
    ]);
    if ($terms) {
      /** @var \Drupal\taxonomy\TermInterface $term */
      $term = reset($terms);
      $description = [
        '#type' => 'processed_text',
        '#text' => $term->getDescription(),
        '#format' => $term->getFormat(),
      ];
      $description = strip_tags($this->renderer->renderPlain($description));
      $summary = substr($description, 0, strrpos(substr($description, 0, 150), ' '));
      if (strlen($description) > strlen($summary)) {
        $summary .= '…';
      }
      $data = [
        'taxonomy_term' => $term,
        'label' => $term->label(),
          // Trim the description to 150 chars to the closest word.
        'description' => $summary,
      ] + $data;
    }

    if (empty($data['label'])) {
      $data['label'] = $data['title'];
    }

    return $data;
  }

  /**
   * Builds a description for displaying in the tooltips.
   *
   * @param array $data
   *   Data array for the template.
   * @param array $result
   *   Virtuoso query result.
   *
   * @return string
   *   Description string.
   */
  protected function buildDescription(array $data, array $result) {
    $description = $data['label'] ?? $data['title'];
    $time = $result['time']->getValue();
    if ($time || $data['unit']) {
      $description .= ' (';
      if ($time) {
        $description .= $time;
      }
      if ($data['unit']) {
        $description .= ', ' . $data['unit'];
      }
      $description .= ')';
    }

    if ($data['description']) {
      $description .= ': ' . $data['description'];
    }

    return $description;
  }

}
