<?php


$organization_types_description = $taxonomy->description->value;
$organization_types_description = strip_tags($organization_types_description);
$field_eu_currency_nal_uri = $taxonomy->field_eu_currency_nal_uri->uri;
$field_iso_4217_code = $taxonomy->field_iso_4217_code->value;


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
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://publications.europa.eu/ontology/euvoc#Currency', 'rdf');



$description->addChild('skos:notation', $field_iso_4217_code, 'skos');
$description->addChild('ns0:identifier', $field_iso_4217_code, 'ns0');


$description->addChild('owl:sameAs', null, 'owl')->addAttribute('rdf:resource', $field_eu_currency_nal_uri, 'rdf');

//$description->addChild('owl:sameAs', $field_eu_currency_nal_uri, 'owl');


//title field skos:prefLabel, dct:title
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('skos:prefLabel', $title_val, 'skos');


// Synonyms Name
foreach ($taxonomy->field_synonyms_synonyms as $key => $value) {
   $field_synonyms_value = $taxonomy->field_synonyms_synonyms[$key]->value;
   $description->addChild('skos:altLabel', $field_synonyms_value, 'skos');
 }//end foreach

//Description
if (!$organization_types_description == null) {
  $organization_types_description = str_replace('&nbsp;', '', $organization_types_description);
  $description->addChild('skos:defination', $organization_types_description, 'skos');
  $description->addChild('skos:defination', $organization_types_description, 'skos');
}


// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

//parent agrovoc field
if (isset($concept_term->field_agrovoc_uri)) {
  $parent_agrovoc_uri = $concept_term->field_agrovoc_uri->uri;
  if (!$parent_agrovoc_uri == null) {
    $description->addChild('skos:broader', null, 'skos')->addAttribute('rdf:resource',$parent_agrovoc_uri, 'rdf');
  }
}



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