<?php

namespace Drupal\landportal_remote_block;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Site\Settings;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A Solr connector service.
 *
 * @package Landportal
 */
class ConnectionService implements ConnectionServiceInterface {

  /**
   * Client.
   *
   * @var \Solarium\Client
   */
  protected $client;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs a new ConnectionService object.
   *
   * @param \Drupal\Core\Site\Settings $settings
   *   Drupal settings.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(Settings $settings, LanguageManagerInterface $language_manager) {
    try {
      $adapter = new Curl();
      $eventDispatcher = new EventDispatcher();
      $config = [
        'endpoint' => [
          'localhost' => [
            'host' => $settings->get('external_solr_host', ''),
            'port' => $settings->get('external_solr_port', ''),
            'path' => $settings->get('external_solr_path', ''),
            'core' => $settings->get('external_solr_core', ''),
          ],
        ],
      ];
      $this->client = new Client($adapter, $eventDispatcher, $config);
      $this->languageManager = $language_manager;
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getResults($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1) {
    $items = [];
    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

      // Add language code, removing country variant from 'pt-pt'.
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;

      $query->createFilterQuery('language')
        ->setQuery('ss_search_api_language:(' . $langcode . ' OR "und")');

      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $results = $this->client->select($query);

      foreach ($results as $document) {
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentResults($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1, $item_ids = "") {
    $items = [];
    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

      // Add language code, removing country variant from 'pt-pt'.
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;

      $query->createFilterQuery('language')
        ->setQuery('ss_search_api_language:(' . $langcode . ' OR "und")');

      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }

      $query->createFilterQuery('item_id')->setQuery($item_ids);

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $results = $this->client->select($query);

      foreach ($results as $document) {
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }

    return $items;
  }


  /**
   * {@inheritdoc}
   */
  public function getContentResultsOrganizations($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1, $item_ids = "") {
    $items = [];
    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

      // Add language code, removing country variant from 'pt-pt'.
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;

      //$query->createFilterQuery('language')
        //->setQuery('ss_search_api_language:(' . $langcode . ' OR "und")');

      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }

      $query->createFilterQuery('item_id')->setQuery($item_ids);

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $results = $this->client->select($query);

      foreach ($results as $document) {
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getContentResultsRelatedContent($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1, $item_ids = "") {
    $items = [];
    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $indexes_of_related_content = "index_id:remote_events OR index_id:remote_news OR index_id:remote_blogs";
      $query->createFilterQuery('index')->setQuery($indexes_of_related_content);

      // Add language code, removing country variant from 'pt-pt'.
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;

      //$query->createFilterQuery('language')
        //->setQuery('ss_search_api_language:(' . $langcode . ' OR "und")');

      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }

      $query->createFilterQuery('item_id')->setQuery($item_ids);

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $results = $this->client->select($query);

      foreach ($results as $document) {
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }

    return $items;
  }
  /**
   * {@inheritdoc}
   */
  public function getCountResults($index, array $conditions, array $fields = [], array $sorts = [], $num_rows = 1) {
    $items = [];
    try {
      $query = $this->client->createSelect();

      // Add the base conditions (index and language), then add custom ones.
      $query->createFilterQuery('index')->setQuery('index_id:' . $index);

      // Add language code, removing country variant from 'pt-pt'.
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      $langcode = $langcode == 'pt-pt' ? 'pt' : $langcode;
      if($index == "remote_libraries"){

      }
      else {
        $query->createFilterQuery('language')
          ->setQuery('ss_search_api_language:(' . $langcode . ' OR "und")');        
      }
      foreach ($conditions as $name => $value) {
        $query->createFilterQuery($name)->setQuery($name . ':' . $value);
      }
      

      // Set the projection of fields to retrieve.
      if ($fields) {
        $query->setFields($fields);
      }

      // The sorts are asc/desc.
      foreach ($sorts as $name => $value) {
        $query->addSort($name, $value);
      }

      // Limit the query.
      $query->setRows($num_rows);

      $results = $this->client->select($query);

      foreach ($results as $document) {
        $item = [];
        foreach ($document as $field => $value) {
          $item[$field] = $value;
        }
        $items[] = $item;
      }
      $items = [];
      $items[] = $results->getNumFound();      
    }
    catch (\Exception $e) {
      watchdog_exception('landportal_remote_block', $e);
    }
    return $items;
  }

}
