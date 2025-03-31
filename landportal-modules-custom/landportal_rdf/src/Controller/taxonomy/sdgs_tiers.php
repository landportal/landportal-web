<?php


$sdgs_tiers_description = $taxonomy->description->value;
$sdgs_tiers_description = strip_tags($sdgs_tiers_description);



//Rdf/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
// get url of the term
$description->addAttribute('rdf:about',$host.$alias, 'rdf');


//Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');


//title field skos:prefLabel, dct:title
$description->addChild('rdfs:label', $title_val, 'rdfs');
$description->addChild('skos:prefLabel', $title_val, 'skos');




//Description
if (!$sdgs_tiers_description == null) {
  $sdgs_tiers_description = str_replace('&nbsp;', '', $sdgs_tiers_description);
  $description->addChild('skos:definition', $sdgs_tiers_description, 'skos');
  //$description->addChild('skos:defination', $licences_description, 'skos');
}


// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','
  http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 



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