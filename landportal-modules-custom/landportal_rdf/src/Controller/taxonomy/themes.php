<?php

  //Get Theme ID 
  $field_id = $taxonomy->field_id->value;

  //Get Concepts Ids
  global $field_concepts_tids;
  $field_concepts_tids = array();
  foreach ($taxonomy->field_related_topic as $key => $value) {
    $field_concepts_tids[] = $taxonomy->field_related_topic[$key]->target_id;
  }

  // Get Theme Description
  $theme_description = $taxonomy->description->value;
  $theme_description = strip_tags($theme_description);

  // Get Langing Page Image URI
  if (!empty($taxonomy) && isset($taxonomy->field_image->entity)) {
    $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($taxonomy->field_image->entity->getFileUri()); 
  }

  //Rdf/XML body start
  $rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dct="http://purl.org/dc/terms/"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:schema="http://schema.org/"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');



  // Create the Description element with the about attribute
  $description = $rdf->addChild('rdf:Description');

  //field_agrovoc_uri in link
  $description->addAttribute('rdf:resource', 'http://data.landportal.info/voc/landvoc/theme/'.$field_id, 'rdf');

  //Below this all XML elements are Child of Description
  $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');

  // show image url
  if(isset($image_url)){
     $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource',$image_url, 'rdf');
  }

  //concepts subject
  $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids);
    foreach ($term_concepts as $key => $concepts) {
      $agrov = $concepts->get('field_agrovoc_uri')->getString();
      $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
    }


  //title field skos:prefLabel, dct:title
  $description->addChild('skos:prefLabel', $title_val, 'skos');
  $description->addChild('skos:prefLabel', $title_val, 'skos');

  //title field rdfs:label
  $description->addChild('rdfs:label', $title_val, 'rdfs');
  $description->addChild('rdfs:label', $title_val, 'rdfs');

  //ID notation.
  if(!$field_id == null){
    $description->addChild('skos:notation', $field_id, 'skos');
   }

  //Description
  if (!$theme_description == null) {
    $theme_description = str_replace('&nbsp;', '', $theme_description);
    $description->addChild('skos:definition', $theme_description, 'skos');
    $description->addChild('skos:definition', $theme_description, 'skos');
  }


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
  $xmlString = str_replace(' xmlns:dct="dct"', '', $xmlString);  
  $xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);  
  $xmlString = str_replace(' xmlns:schema="schema" ', ' ', $xmlString);
  // set header type of XML
  header('Content-Type: application/xml; charset=UTF-8');
  // RDF Save and print on screen
  echo $xmlString;
  exit();

?>