<?php

$id = $taxonomy->field_id->value;
$observation_description = $taxonomy->field_description->value;
$observation_description = strip_tags($observation_description);


//get dataset tax id field data
global $entity_reference_field;
$entity_reference_field = array();
    foreach ($taxonomy->field_dataset as $key => $value) {
      $entity_reference_field[] = $taxonomy->field_dataset[$key]->target_id;
    }

 //Get themes Ids
global $field_theme_tids;
$field_theme_tids = array();
  foreach ($taxonomy->field_related_themes as $key => $value) {
    $field_theme_tids[] = $taxonomy->field_related_themes[$key]->target_id;
  }

//Get Concepts Ids
global $field_concepts_tids;
$field_concepts_tids = array();
foreach ($taxonomy->field_related_topics as $key => $value) {
  $field_concepts_tids[] = $taxonomy->field_related_topics[$key]->target_id;
 }

//Rdf/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');

$description->addAttribute('rdf:about', $host.$alias, 'rdf');

//Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://schema.org/GeospatialGeometry', 'rdf');

// get id field data 
$description->addChild('dct:identifier', $id, 'dct');
$description->addChild('skos:notation', $id, 'skos');

// get concepts term name
$term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids);
foreach ($term_concepts as $key => $concepts) {  //start foreach loop
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  if (!$agrov == null) {
    $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
  }
}

// get theme term name
$terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($field_theme_tids);
foreach ($terms_themes as $term) {  //start foreach loop
  $feild_id = $term->field_id->value;
  $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}

// get concepts term name
$term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids);
foreach ($term_concepts as $key => $concepts) {  //start foreach loop
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  if (!$agrov == null) {
    $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
  }
}

// get theme term name
$terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($field_theme_tids);
foreach ($terms_themes as $term) {  //start foreach loop
  $feild_id = $term->field_id->value;
  $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}

// get dataset tax field_id data
$dataset_term_name = \Drupal\taxonomy\Entity\Term::loadMultiple($entity_reference_field);
foreach ($dataset_term_name as $dataset_term_names) {  //start foreach loop
  $feild_id_dataset = $dataset_term_names->get('field_id')->value;
  $description->addChild('dct:source', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/dataset/'. $feild_id_dataset, 'rdf');
}


//title field skos:prefLabel, dct:title
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('skos:prefLabel', $title_val, 'skos');


$description->addChild('skos:definition', $observation_description, 'skos');

// Synonyms Name
foreach ($taxonomy->field_synonyms_synonyms as $key => $value) {
   $field_synonyms_value = $taxonomy->field_synonyms_synonyms[$key]->value;
   $description->addChild('skos:altLabel', $field_synonyms_value, 'skos');
 }//end foreach


// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 


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