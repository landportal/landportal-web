<?php

use Drupal\Core\Url;
use Drupal\taxonomy\Entity\Term;

$languages_description = $taxonomy->description->value;
$languages_description = strip_tags($languages_description);

$concept_parent_id = $taxonomy->parent->target_id;
$concept_term = Term::load($concept_parent_id);

// Get the parent term URL alias if it exists
$parent_term_url_alias = null;
if ($concept_term) {
    $url = Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $concept_parent_id], ['absolute' => TRUE]);
    $parent_term_url_alias = $url->toString();
}

// RDF/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
// Get URL of the term
$description->addAttribute('rdf:about', $host . $alias, 'rdf');

// Below this, all XML elements are children of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');

// Title field skos:prefLabel, dct:title
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('skos:prefLabel', $title_val, 'skos');

// Description
if ($languages_description !== null) {
  $languages_description = str_replace('&nbsp;', '', $languages_description);
  $description->addChild('skos:definition', $languages_description, 'skos');
}

// For Vocabulary Link
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource', 'http://data.landportal.info/vocabulary/' . $vocubalry_id, 'rdf'); 

// Add the parent term URL alias to the skos:broader element only if it exists and is not the parent term itself
if ($parent_term_url_alias && $taxonomy->parent->target_id) {
  $description->addChild('skos:broader', null, 'skos')->addAttribute('rdf:resource', $parent_term_url_alias, 'rdf');
}

// For Full term Link
$xmlString = $rdf->asXML();

// Remove the xmlns:rdfs attribute
$xmlString = str_replace(' xmlns:rdfs="rdfs"', '', $xmlString);
$xmlString = str_replace(' xmlns:skos="skos"', '', $xmlString);
$xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);
$xmlString = str_replace(' xmlns:owl="owl"', '', $xmlString);  

// Set header type of XML
header('Content-Type: application/xml; charset=UTF-8');

// Echo the XML string
echo $xmlString;
exit();
?>
