<?php


// get iso3 field data
$field_iso3 = $taxonomy->field_iso3->value;
$field_area_code = $taxonomy->field_area_code_un_m_49_->value;
  

// get code_area field data from parent term
$parent_id = $taxonomy->parent->target_id;
$parent_taxonomy = \Drupal\taxonomy\Entity\Term::load($parent_id);
if (is_object($parent_taxonomy)){
    $parent_field_area_code = $parent_taxonomy->field_area_code_un_m_49_->value;

}
// get description field data  
$region_description = $taxonomy->description->value;
$region_description = strip_tags($region_description);



//Rdf/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:dct="http://purl.org/dc/terms/"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:geonames="http://www.geonames.org/ontology#" />');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');

//field_iso3 use in 1st link
if(!$field_iso3 == null){
  $description->addAttribute('rdf:about', 'http://data.landportal.info/geo/'.$field_iso3, 'rdf');
}
if (!$field_area_code == null) {
  $description->addAttribute('rdf:about', 'http://data.landportal.info/geo/'.$field_area_code, 'rdf');
}

//Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://schema.org/Place', 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://purl.org/dc/terms/Location', 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.geonames.org/ontology#Feature', 'rdf');

//title field skos:prefLabel, dct:title and rdfs:label
$description->addChild('skos:prefLabel', $title_val, 'skos');
$description->addChild('skos:prefLabel', $title_val, 'skos');

$description->addChild('dct:title', $title_val, 'dct');

$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('rdfs:label', $title_val, 'rdfs');

// Synonyms Name
// if(!$field_synonyms == null){
  foreach ($taxonomy->field_synonyms_synonyms as $key => $value) {
     $field_synonyms_value = $taxonomy->field_synonyms_synonyms[$key]->value;
     $description->addChild('skos:altLabel', $field_synonyms_value, 'skos');
   }//end foreach

 // }

//Description
if (!$region_description == null) {
  $region_description = str_replace('&nbsp;', '', $region_description);
  $description->addChild('skos:definition', $region_description, 'skos');
  $description->addChild('skos:definition', $region_description, 'skos');
  $description->addChild('dct:description', $region_description, 'dct');
  $description->addChild('rdfs:comment', $region_description, 'rdfs');
}

//field_iso3 identifier and notation
if(!$field_iso3 == null){
  $description->addChild('dct:identifier', $field_iso3, 'dct');
  $description->addChild('skos:notation', $field_iso3, 'skos');
  $description->addChild('geonames:countryCode', $field_iso3, 'geonames');
}
//field_area_code identifier and notation
if (!$field_area_code == null) {
  $description->addChild('dct:identifier', $field_area_code, 'dct');
  $description->addChild('skos:notation', $field_area_code, 'skos');
}  

  // For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

//field_area_code parent id.
if(isset($parent_field_area_code)){
  $description->addChild('skos:broader', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$parent_field_area_code, 'rdf');
}

// For Full term Link 
$description->addChild('rdfs:seeAlso', null, 'rdfs')->addAttribute('rdf:resource',$host.$alias, 'rdf');

  $xmlString = $rdf->asXML();

  // Remove the xmlns:rdfs attribute
  $xmlString = str_replace(' xmlns:rdfs="rdfs"', '', $xmlString);
  $xmlString = str_replace(' xmlns:skos="skos"', '', $xmlString);
  $xmlString = str_replace(' xmlns:dct="dct"', '', $xmlString);  
  $xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);  
  $xmlString = str_replace(' xmlns:geonames="geonames"', '', $xmlString);  

  // set header type of XML
  header('Content-Type: application/xml; charset=UTF-8');
  // RDF Save and print on screen
  echo $xmlString;
  exit();

?>