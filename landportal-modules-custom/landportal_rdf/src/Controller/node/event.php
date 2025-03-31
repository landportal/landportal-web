<?php
header('Content-Type: application/xml');


$body_value= $node->body->value;
$body_description = strip_tags($body_value);
$body_description  = str_replace('&nbsp;', '', $body_description);
$issue_narrative_summary_value = $node->issue_narrative_summary->value;
$summary = strip_tags($issue_narrative_summary_value);
$summary  = str_replace('&nbsp;', '', $summary);
$contact = $node->field_contact->value;
$zoomurl = $node->field_link->uri;

// Start XML document
$rdf = new SimpleXMLElement('<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dct="http://purl.org/dc/terms/" xmlns:bibo="http://purl.org/ontology/bibo/" xmlns:schema="http://schema.org/" xmlns:prov="http://www.w3.org/ns/prov#" xmlns:edm="http://www.europeana.eu/schemas/edm/" xmlns:ns0="http://example.org/ns0#"></rdf:RDF>');

// Add description
$description_element = $rdf->addChild('rdf:Description');
$description_element->addAttribute('rdf:about', "http://data.landportal.info/event/$uuid",'rdf');

// Add rdf:type elements
$types = [
    "http://schema.org/Event",
];
foreach ($types as $type) {
    $description_element->addChild('rdf:type')->addAttribute('rdf:resource', $type,'rdf');
}


global $theme_tids;
$theme_tids = array();

foreach ($content['field_related_themes']['widget'] as $key => $value) {//foreach start
  if (is_numeric($key)) { //if (is_numeric($key)) start
    $theme_id = $content['field_related_themes']['widget'][$key];
    if (isset($theme_id['target_id']['#default_value'][0])) {//if (isset) start
      $theme_tid = $theme_id['target_id']['#default_value'][0]->get('tid')->value;
      $theme_tids[] = $theme_tid;
    }////if (isset) end
  }//if (is_numeric($key)) end
}//foreach end

//Concepts
global $concepts_tids;
$concepts_tids = array();

foreach ($content['field_related_topics']['widget'] as $key => $value) {//foreach start
  if (is_numeric($key)) {//if (is_numeric($key)) start
    $concept = $content['field_related_topics']['widget'][$key];
    if (isset($concept['target_id']['#default_value'][0])) {//if (isset) start
      $concept_tid = $concept['target_id']['#default_value'][0]->get('tid')->value;
      $concepts_tids[] = $concept_tid;
    }//if (isset) end
  }//if (is_numeric($key)) end
}//foreach end
global $organizer_id;
$organizer_id = array();
foreach ($content['field_orgref']['widget'] as $key => $value) {
  if (is_numeric($key)) {
    $organizer_pro = $content['field_orgref']['widget'][$key];
    if (isset($organizer_pro['target_id']['#default_value'][0])) {
      $organizer_provider = $organizer_pro['target_id']['#default_value'][0]->get('nid')->value;
      $organizer_id[] = $organizer_provider;
    }
  }
}
//Geoghraphical Focus
global $geoghraphical_tids;
$geoghraphical_tids = array();

foreach ($content['field_geographical_focus']['widget'] as $key => $value) {//foreach start
  if (is_numeric($key)) {//if (is_numeric($key) start
    $country_id = $content['field_geographical_focus']['widget'][$key];
    if (isset($country_id['target_id']['#default_value'][0])) {//if(isset) start
      $geoghraphical_tid = $country_id['target_id']['#default_value'][0]->get('tid')->value;
      $geoghraphical_tids[] = $geoghraphical_tid;
    }//if(isset) end
  }//if (is_numeric($key) end
}//foreach end

 //For Geoghraphical subject
 $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
 foreach ($geo_terms as $key => $geo_term) {// foreach start
 
 if (!$geo_term == null) {
     if ($geo_term->hasField('field_area_code_un_m_49')) {
       $area_code = $geo_term->get('field_area_code_un_m_49')->value;
     } elseif ($geo_term->hasField('field_area_code_un_m_49_')) {
       $area_code = $geo_term->get('field_area_code_un_m_49_')->value;
     }
 }
 
   $iso3 = $geo_term->get('field_iso3')->getString();
   if (!$area_code == null) {// if start
    $description_element->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
   }// if end 
   if (!$iso3 == null) { // start if
    $description_element->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
   } // if end               
 }// foreach end
 
 //For Geoghraphical schema
 $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
 foreach ($geo_terms as $key => $geo_term) {// foreach start
 
 if (!$geo_term == null) {
     if ($geo_term->hasField('field_area_code_un_m_49')) {
       $area_code = $geo_term->get('field_area_code_un_m_49')->value;
     } elseif ($geo_term->hasField('field_area_code_un_m_49_')) {
       $area_code = $geo_term->get('field_area_code_un_m_49_')->value;
     }
 }
 
 
   $iso3 = $geo_term->get('field_iso3')->getString();
   if (!$area_code == null) {// if start
    $description_element->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
   } // if end
   if (!$iso3 == null) { // if start
    $description_element->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
   }  // if end             
 }//foreach end
 
 // For Themes subject
 $terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($theme_tids);
 foreach ($terms_themes as $term) { // foreach start
   $feild_id = $term->field_id->value;
   $description_element->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
 }// foreach end
 
 //For Concepts subject
 $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($concepts_tids);
 foreach ($term_concepts as $key => $concepts) {//foreach start
   $agrov = $concepts->get('field_agrovoc_uri')->getString();
   $description_element->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
 }// foreach end
 
 // For Themes Schema
 $terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($theme_tids);
 foreach ($terms_themes as $term) {//foreach start
   $feild_id = $term->field_id->value;
   $description_element->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
 }// foreach end
 
 //For Concepts schema
 $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($concepts_tids);
 foreach ($term_concepts as $key => $concepts) {// foreach start
   $agrov = $concepts->get('field_agrovoc_uri')->getString();
   $description_element->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
 }//foreach end
 $image_url = '';
 if(isset($node->field_image->entity)){ //if(isset) start
   $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_image->entity->getFileUri()); 
   $description_element->addChild('schema:image',null,'schema')->addAttribute('rdf:resource', $image_url,'rdf');
   $description_element->addChild('schema:thumbnailUrl',null,'schema')->addAttribute('rdf:resource', $image_url,'rdf');
 }// if(isset) end
$title = $description_element->addChild('dct:title', $title_val, 'dct');
$title = $description_element->addChild('schema:name', $title_val, 'schema');
$title = $description_element->addChild('schema:name', $title_val, 'schema');
$description_element->addChild('dct:description', $body_description, 'dct');
$description_element->addChild('schema:description', $body_description, 'schema');
// Add the date property with datatype attribute
$date = $description_element->addChild('ns0:date', $created_date , 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');
$date = $description_element->addChild('ns0:date', $created_date , 'ns0');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');
$date = $description_element->addChild('schema:StartDate', $created_date , 'schema');
$date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#dateTime', 'rdf');
$contactdetail = $description_element->addChild('dct:location', $contact, 'dct')->addAttribute('rdf:datatype', 'Place', 'rdf');
$contactdetail = $description_element->addChild('schema:location', $contact, 'schema')->addAttribute('rdf:datatype', 'Place', 'rdf');
$description_element->addChild('schema:url', null, 'schema')->addAttribute('rdf:resource', $zoomurl, 'rdf');
$organizer_node = \Drupal\node\Entity\Node::loadMultiple($organizer_id);
foreach ( $organizer_node  as $key => $value) {
  $organizer= $value->uuid->value;
  $description_element->addChild('schema:organizer',null,'schema')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$organizer, 'rdf');
}
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
