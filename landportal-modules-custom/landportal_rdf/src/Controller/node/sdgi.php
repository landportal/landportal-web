<?php
header('Content-Type: application/xml');


$body_value= $node->body->value;
$body_description = strip_tags($body_value);
$body_description  = str_replace('&nbsp;', '', $body_description);



// Start XML document
$rdf = new SimpleXMLElement('<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dct="http://purl.org/dc/terms/" xmlns:bibo="http://purl.org/ontology/bibo/" xmlns:schema="http://schema.org/" xmlns:prov="http://www.w3.org/ns/prov#" xmlns:edm="http://www.europeana.eu/schemas/edm/" xmlns:ns0="http://example.org/ns0#"></rdf:RDF>');

// Add description
$description_element = $rdf->addChild('rdf:Description');
$description_element->addAttribute('rdf:about', "$host.$path_alias",'rdf');

// Add rdf:type elements
$types = [
    "http://rdfs.org/sioc/ns#Item",
    "http://xmlns.com/foaf/0.1/Document"
];
foreach ($types as $type) {
    $description_element->addChild('rdf:type')->addAttribute('rdf:resource', $type,'rdf');
}


$title = $description_element->addChild('ns0:title', $title_val, 'ns0');


$description_element->addChild('content:encoded', $body_description, 'content');
// Add the date property with datatype attribute
$date = $description_element->addChild('ns0:date', $created_date , 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');
$date = $description_element->addChild('ns0:date', $created_date , 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');
// Add the created date element with the rdf:datatype attribute
$date = $description_element->addChild('ns0:created', $created_date, 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');

// Add the modified date element with the rdf:datatype attribute
$date = $description_element->addChild('ns0:modified', $modified_date, 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');

//auhtors
$description_element->addChild('sioc:has_creator', null, 'sioc')->addAttribute('rdf:resource', $host.'/users/'.$username, 'rdf');
$description_element->addChild('sioc:num_replies', $comments, 'sioc')->addAttribute('rdf:resource',  "http://www.w3.org/2001/XMLSchema#integer", 'rdf');

// Output RDF XML
echo $rdf->asXML();
exit();
?>
