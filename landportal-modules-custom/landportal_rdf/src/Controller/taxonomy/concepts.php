<?php

$region_description = $taxonomy->description->value;
$region_description = strip_tags($region_description);
$field_agrovoc_uri = $taxonomy->field_agrovoc_uri->uri;
//$synonyms_synonyms = $taxonomy->synonyms_synonyms->value;
$concept_parent_id = $taxonomy->parent->target_id;
$concept_term = \Drupal\taxonomy\Entity\Term::load($concept_parent_id);

//Rdf/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');

//field_agrovoc_uri in link
$description->addAttribute('rdf:about', $field_agrovoc_uri, 'rdf');

//Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');

//title field skos:prefLabel, dct:title
$description->addChild('skos:prefLabel', $title_val, 'skos');
$description->addChild('skos:prefLabel', $title_val, 'skos');

//$field_agrovoc_uri in links
$description->addChild('skos:exactMatch', null, 'skos')->addAttribute('rdf:resource', $field_agrovoc_uri, 'rdf');
$description->addChild('owl:sameAs', null, 'owl')->addAttribute('rdf:resource', $field_agrovoc_uri, 'rdf');

// Synonyms Name
foreach ($taxonomy->synonyms_synonyms as $key => $value) {
   $field_synonyms_value = $taxonomy->synonyms_synonyms[$key]->value;
   $description->addChild('skos:altLabel', $field_synonyms_value, 'skos');
 }

//Description
if (!$region_description == null) {
  $region_description = str_replace('&nbsp;', '', $region_description);
  $description->addChild('skos:definition', $region_description, 'skos');
  $description->addChild('skos:definition', $region_description, 'skos');
}

//title field rdfs:label
$description->addChild('rdfs:label', $title_val, 'rdfs');

// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','
  http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

//parent agrovoc field
if (isset($concept_term->field_agrovoc_uri)) {
  $parent_agrovoc_uri = $concept_term->field_agrovoc_uri->uri;
  if (!$parent_agrovoc_uri == null) {
    $description->addChild('skos:broader', null, 'skos')->addAttribute('rdf:resource',$parent_agrovoc_uri, 'rdf');
  }
}

// For Full term Link 
$description->addChild('rdfs:seeAlso', null, 'rdfs')->addAttribute('rdf:resource',$host.$alias, 'rdf');


  $xmlString = $rdf->asXML();

  // Remove the xmlns:rdfs attribute
  $xmlString = str_replace(' xmlns:rdfs="rdfs"', '', $xmlString);
  $xmlString = str_replace(' xmlns:skos="skos"', '', $xmlString);
  $xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);
  $xmlString = str_replace(' xmlns:owl="owl"', '', $xmlString);  

  // set header type of XML
  header('Content-Type: application/xml; charset=UTF-8');
  // echo $rdf->saveXML();
  echo $xmlString;
  exit();
?>