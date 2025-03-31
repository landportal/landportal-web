<?php

// Field Id of Taxonomy Term
$field_id = $taxonomy->field_id->value;

// Condition To Check License     
$license_text = '';
if (is_object($taxonomy->field_license_text) && !empty($taxonomy->field_license_text)) { 
  $copyrighttext = $taxonomy->field_license_text->value;
  $license_text = strip_tags($copyrighttext);
  $license_text = str_replace('&nbsp;', '', $license_text);
}

// Condition To Check Vocabularies
$description = $taxonomy->field_dataset_description->value;
$dataset_descriptions = strip_tags($description);
$dataset_description = str_replace('&nbsp;', '', $dataset_descriptions);

// Condition for Check Measurement Unit in Indicator
if (is_object($taxonomy->field_unit)) {
  $measurement_unit = $taxonomy->field_unit->value;
}

// Condition for Check DataSet Field Id in Indicator
if (is_object($taxonomy->field_dataset)) { 
  $indicator_field_id = $taxonomy->field_dataset->target_id; 
  $dataset_field = \Drupal\taxonomy\Entity\Term::load($indicator_field_id);
  if (!is_null($dataset_field) && $dataset_field->hasField('field_id') && is_object($dataset_field)) {
    $dataset_field_id = $dataset_field->field_id->value;
  }
}

// Get Organization reference Ids and UUIDs
global $data_id;
$data_id = [];
$org_uuids = [];
if (isset($taxonomy->field_orgref) && !empty($taxonomy->field_orgref)) {
  foreach ($taxonomy->field_orgref as $reference) {
    if (is_object($reference->entity)) {
      $org_node = $reference->entity;
      $data_id[] = $org_node->id();
      $org_uuids[] = $org_node->uuid->value;
    }
  }
}

// Get license Ids
global $licence_tids;
$licence_tids = [];
if (!empty($taxonomy->field_doc_licencing)) {
  foreach ($taxonomy->field_doc_licencing as $key => $value) {
    $licence = $taxonomy->field_doc_licencing[$key]->target_id;
    $licence_tids[] = $licence;
  }
}

// Get themes Ids
global $field_theme_tids;
$field_theme_tids = [];
foreach ($taxonomy->field_related_themes as $key => $value) {
  $field_theme_tids[] = $taxonomy->field_related_themes[$key]->target_id;
}

// Get Concepts Ids
global $field_concepts_tids;
$field_concepts_tids = [];
foreach ($taxonomy->field_related_topic as $key => $value) {
  $field_concepts_tids[] = $taxonomy->field_related_topic[$key]->target_id;
}

// RDF/XML body start
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" 
  xmlns:dct="http://purl.org/dc/terms/" 
  xmlns:schema="http://schema.org/" 
  xmlns:ns0="dc:"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" />');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
$description->addAttribute('rdf:about', 'http://data.landportal.info/dataset/'.$field_id, 'rdf');

// Below this all XML elements are Child of Description
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://purl.org/linked-data/cube#DataSet', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/ns/dcat#Dataset', 'rdf');

// Image schema, logo schema
if (isset($taxonomy->field_image->entity)) {
  $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($taxonomy->field_image->entity->getFileUri()); 
  $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
  $description->addChild('schema:logo', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
}

// Get id field data
if(!$field_id == null){ 
  $description->addChild('dct:identifier', $field_id, 'dct');
  $description->addChild('skos:notation', $field_id, 'skos');
}

// Concepts data
$term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids);
foreach ($term_concepts as $key => $concepts) {  
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  if(!$agrov == null){
    $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
  }
}

// Theme data
$terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($field_theme_tids);
foreach ($terms_themes as $term) {
  $feild_id = $term->field_id->value;
  $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}

// Concepts data (schema:about)
foreach ($term_concepts as $key => $concepts) {
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  if (!$agrov == null) {
    $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
  }
}

// Theme data (schema:about)
foreach ($terms_themes as $term) {  
  $feild_id = $term->field_id->value;
  $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}

// For Publisher Link
// $data_term = \Drupal\node\Entity\Node::loadMultiple($data_id);
// foreach ($data_term as $key => $value) {
//   $publisher_link  =$value->uuid->value;
//   $description->addChild('dct:publisher', null, 'dct')->addAttribute('rdf:resource', 'http://data.landportal.info/organization/'.$publisher_link, 'rdf');
// }

// For License Link || URI
$licenc_term = \Drupal\taxonomy\Entity\Term::loadMultiple($licence_tids);
foreach ($licenc_term as $term) {
  $rdflice_uri = $term->field_rdflicense_uri->uri;
  $description->addChild('dct:license', null, 'dct')->addAttribute('rdf:resource', $rdflice_uri, 'rdf'); 
  $description->addChild('schema:license', null, 'schema')->addAttribute('rdf:resource', $rdflice_uri, 'rdf');
}

// Add organization reference UUIDs
foreach ($org_uuids as $org_uuid) {
  $description->addChild('dct:publisher', null, 'dct')->addAttribute('rdf:resource', 'http://data.landportal.info/organization/'.$org_uuid, 'rdf');
}

// Copy right
if (!empty($license_text)) {
  $description->addChild('ns0:rights', $license_text, 'ns0')->addAttribute('rdf:datatype', "http://www.w3.org/2001/XMLSchema#string", 'rdf');
}

if (isset($dataset_field_id)) {
 $description->addChild('dct:source', null, 'dct')->addAttribute('rdf:resource', "http://data.landportal.info/dataset/".$dataset_field_id, 'rdf');
}

// Title skos, rdfs, dct.
if(!$title_val == null){
  $description->addChild('skos:prefLabel', $title_val, 'skos');
  $description->addChild('rdfs:label', $title_val, 'rdfs');
  $description->addChild('dct:title', $title_val, 'dct');
}

// Description
if(!$dataset_description == null){
  $description->addChild('skos:definition', $dataset_description, 'skos');
  $description->addChild('dct:description', $dataset_description, 'dct');
  $description->addChild('rdfs:comment', $dataset_description, 'rdfs');
}

// For Vocabulary Link 
$description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

 

// For Full term Link 
$description->addChild('rdfs:seeAlso', null, 'rdfs')->addAttribute('rdf:resource',$host.$alias, 'rdf');

$xmlString = $rdf->asXML();
$xmlString = str_replace(' xmlns:rdfs="rdfs"', '', $xmlString);
$xmlString = str_replace(' xmlns:skos="skos"', '', $xmlString);
$xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);
$xmlString = str_replace(' xmlns:dct="dct"', '', $xmlString);  
$xmlString = str_replace(' xmlns:sdmx-attribute="sdmx-attribute"', '', $xmlString);
$xmlString = str_replace(' xmlns:schema="schema" ', ' ', $xmlString);
$xmlString = str_replace(' xmlns:ns0="ns0" ', ' ', $xmlString);

// Set header type of XML
header('Content-Type: application/xml charset=UTF-8');

// RDF Save and print on screen
echo $xmlString;
exit();
?>
