<?php


$languages_description = $taxonomy->description->value;
$languages_description = strip_tags($languages_description);
$field_language_code_iso_639_1 = $taxonomy->field_language_code_iso_639_1->value;
$field_language_code_iso_639_3 = $taxonomy->field_language_code_iso_639_3->value;


//Rdf/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
// get url of the term
$description->addAttribute('rdf:about', 'http://data.landportal.info/voc/language/'.$field_language_code_iso_639_1, 'rdf');

// $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource','
//   http://data.landportal.info/voc/language/'.$field_language_code_iso_639_1, 'rdf'); 
// get url of the term
// $description->addChild('rdf:Description', null, 'rdf')->addAttribute('rdf:about',$host.$alias, 'rdf');

//Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://lexvo.org/ontology#Language', 'rdf');

//title field skos:prefLabel, dct:title
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('skos:prefLabel', $title_val, 'skos');
$description->addChild('skos:prefLabel', $title_val, 'skos');
$description->addChild('dct:title', $title_val, 'dct');

$description->addChild('lvont:iso639P1Code', $field_language_code_iso_639_1, 'lvont');
$description->addChild('lvont:iso639P3Code', $field_language_code_iso_639_3, 'lvont');

//Description
if (!$languages_description == null) {
  $languages_description = str_replace('&nbsp;', '', $languages_description);
  $description->addChild('skos:definition', $languages_description, 'skos');
}


// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','
  http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

$description->addChild('rdfs:seeAlso', null, 'rdfs')->addAttribute('rdf:resource',$host.$alias, 'rdf'); 


// For Full term Link 



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