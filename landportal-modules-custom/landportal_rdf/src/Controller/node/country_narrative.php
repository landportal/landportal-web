<?php
$field_narrative_value = $node->narrative->value;
$field_narrative = strip_tags($field_narrative_value);
$field_narrative  = str_replace('&nbsp;', '', $field_narrative);
$field_narrative_valueOne = $node->additional_information->value;
$field_narrativeOne = strip_tags($field_narrative_valueOne);
$field_narrativeOne  = str_replace('&nbsp;', '', $field_narrativeOne);
// xml start
$rdf = new SimpleXMLElement('<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dct="http://purl.org/dc/terms/" xmlns:schema="http://schema.org/" xmlns:ns0="http://example.com/ns0"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');

$description->addAttribute('rdf:about', $host.$path_alias, 'rdf');
  $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://rdfs.org/sioc/ns#Item', 'rdf');
  $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://xmlns.com/foaf/0.1/Document', 'rdf'); 

//field_narrative
// if(isset($node->field_narrative)){//if start
//     $field_narrative = $node->narrative->value;
//     $field_narrative = strip_tags($field_narrative);

//     if (!$field_narrative == null) {//if start
//     	$description->addChild('content:encoded', $field_narrative, 'content');
//     }//if end
// }//if end
$description->addChild('content:encoded', $field_narrative.$field_narrativeOne, 'content');
// Add the title property
$title = $description->addChild('dct:title', $title_val, 'dct');
// Add the date property with datatype attribute
$date = $description->addChild('ns0:date', $created_date , 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');

// Created date in xml
$date = $description->addChild('ns0:created', $created_date, 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');

// modified date in xml
$date = $description->addChild('ns0:modified', $modified_date, 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');

$description->addChild('sioc:has_creator', null, 'sioc')->addAttribute('rdf:resource', $host.'/users/'.$username, 'rdf');

$description->addChild('sioc:num_replies', $comments, 'sioc')->addAttribute('rdf:resource',  "http://www.w3.org/2001/XMLSchema#integer", 'rdf');

header('Content-Type: application/xml');
echo $rdf->saveXML();
exit();
?>