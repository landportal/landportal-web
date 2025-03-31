<?php
header('Content-Type: application/xml');

$issued_date = $node->field_doc_creation_date->value;
$description_value  = $node->field_doc_description->value;
$description = strip_tags($description_value);
$description  = str_replace('&nbsp;', '', $description);
$contributors_value = $node->field_doc_people->value;
$contributors = strip_tags($contributors_value);
$contributors  = str_replace('&nbsp;', '', $contributors);
$subtitle = $node->field_doc_alt_title->value;
$page = $node->field_doc_pagination->value;
$document_urls = $node->field_doc_is_shown_at->uri;
\Drupal::logger('check value Landportal hamza')->notice(serialize($document_urls));
//licences
global $licences_tids;
$licences_tids = array();

foreach ($content['field_doc_licencing']['widget'] as $key => $value) {//foreach start
  if (is_numeric($key)) { //if (is_numeric($key)) start
    $licences_id = $content['field_doc_licencing']['widget'][$key];
    if (isset($licences_id['target_id']['#default_value'][0])) {//if (isset) start
      $licences_tid = $licences_id['target_id']['#default_value'][0]->get('tid')->value;
      $licences_tids[] = $licences_tid;
    }////if (isset) end
  }//if (is_numeric($key)) end
}//foreach end

 // publish Field
 global $publisher_id;
 $publisher_id = array();
 foreach ($content['field_doc_publisher']['widget'] as $key => $value) {
   if (is_numeric($key)) {
     $publisher_pro = $content['field_doc_publisher']['widget'][$key];
     if (isset($publisher_pro['target_id']['#default_value'][0])) {
       $publisher_provider = $publisher_pro['target_id']['#default_value'][0]->get('nid')->value;
       $publisher_id[] = $publisher_provider;
     }
   }
 }
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
//document type
global $document_type;
$document_type = array();

foreach ($content['field_doc_type']['widget'] as $key => $value) {//foreach start
  if (is_numeric($key)) { //if (is_numeric($key)) start
    $document_id = $content['field_doc_type']['widget'][$key];
    if (isset($document_id['target_id']['#default_value'][0])) {//if (isset) start
      $document_tid = $document_id['target_id']['#default_value'][0]->get('tid')->value;
      $document_type[] = $document_tid;
    }////if (isset) end
  }//if (is_numeric($key)) end
}//foreach end

//Languages
global $lang_tids;
$lang_tids = array();
foreach ($content['field_doc_language']['widget'] as $key => $value) { //foreach start
  if (is_numeric($key)) { //if (is_numeric($key)) start
    $lang_id = $content['field_doc_language']['widget'][$key];
    if (isset($lang_id['target_id']['#default_value'][0])) {//if (isset) start
      $lang_tid = $lang_id['target_id']['#default_value'][0]->get('tid')->value;
      $lang_tids[] = $lang_tid;
    }//if (isset) end
  }// if(is_numeric($key)) end
}// foreach end

$title_valOne = $node->title_field->value;
$title = strip_tags($title_valOne);
$title  = str_replace('&nbsp;', '', $title);


// Start XML document
$rdf = new SimpleXMLElement('<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dct="http://purl.org/dc/terms/" xmlns:bibo="http://purl.org/ontology/bibo/" xmlns:schema="http://schema.org/" xmlns:prov="http://www.w3.org/ns/prov#" xmlns:edm="http://www.europeana.eu/schemas/edm/" xmlns:ns0="http://example.org/ns0#"></rdf:RDF>');

// Add description
$description_element = $rdf->addChild('rdf:Description');
$description_element->addAttribute('rdf:about', "http://data.landportal.info/library/resource/$uuid",'rdf');

// Add rdf:type elements
$types = [
    "http://purl.org/dc/terms/BibliographicResource",
    "http://purl.org/ontology/bibo/Document",
    "http://schema.org/CreativeWork",
    "http://www.w3.org/ns/prov#Entity",
    "http://www.europeana.eu/schemas/edm/ProvidedCHO"
];
foreach ($types as $type) {
    $description_element->addChild('rdf:type')->addAttribute('rdf:resource', $type,'rdf');
}

// Add image and thumbnail


// Add spatial and subject terms
// foreach ($geo_terms as $geo) {
//     $description_element->addChild('dct:spatial', null, 'schema')->addAttribute('rdf:resource', $geo,'rdf');
//     $description_element->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource', $geo,'rdf');
// }
//Theme
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
 
 //For Licences schema
 $term_licences = \Drupal\taxonomy\Entity\Term::loadMultiple($licences_tids);
 foreach ($term_licences as $key => $licences) {// foreach start
   $rdflicense = $licences->get('field_rdflicense_uri')->getString();
   $description_element->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource', $rdflicense, 'rdf');
   
// Add license
$description_element->addChild('dct:license',null,'dct')->addAttribute('rdf:resource', $rdflicense,'rdf');
$description_element->addChild('schema:license',null,'schema')->addAttribute('rdf:resource', $rdflicense,'rdf');
 }//foreach end
 $image_url = '';
if(isset($node->field_image->entity)){ //if(isset) start
  $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_image->entity->getFileUri()); 
  $description_element->addChild('schema:image',null,'schema')->addAttribute('rdf:resource', $image_url,'rdf');
  $description_element->addChild('schema:thumbnailUrl',null,'schema')->addAttribute('rdf:resource', $image_url,'rdf');
}// if(isset) end

// Add contributor
$description_element->addChild('ns0:contributor', $contributors,"ns0");
// Add alternative title
$description_element->addChild('dct:alternative', $subtitle ,'dct');

// Add issued date
$description_element->addChild('dct:issued', $issued_date,'dct')->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#date','rdf');
$description_element->addChild('schema:datePublished', $issued_date,'schema')->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#date','rdf');

// Add description
$description_element->addChild('dct:description', $description,'dct');
$description_element->addChild('schema:description', $description,'schema');

// Add abstract
$description_element->addChild('bibo:abstract', $description,'bibo');

// Add language


// Add document type
 
$term_type = \Drupal\taxonomy\Entity\Term::loadMultiple($document_type);
 foreach ($term_type as $key => $type) {// foreach start
   $dcmt_type = $type->get('path')->getString();
   $description_element->addChild('dct:type',null,'dct')->addAttribute('rdf:resource','https://landportal.org'.$dcmt_type,'rdf');
  //  $description_element->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource', $rdflicense, 'rdf');
 }

$document_url = '';
  if(isset($node->field_doc_is_shown_by_file->entity)){ //if(isset) start
    $document_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_doc_is_shown_by_file->entity->getFileUri()); 
   
    $description_element->addChild('bibo:uri',null,'bibo')->addAttribute('rdf:resource', $document_url,'rdf');
    $description_element->addChild('schema:url',null,'schema')->addAttribute('rdf:resource', $document_url,'rdf');
    $description_element->addChild('edm:isShownBy',null,'edm')->addAttribute('rdf:resource', $document_url,'rdf');
  }// if(isset) end

 

    // $document_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_doc_is_shown_by_file->entity->getFileUri()); 
   
    $description_element->addChild('bibo:uri',null,'bibo')->addAttribute('rdf:resource', $document_urls,'rdf');
    $description_element->addChild('bibo:uri',null,'bibo')->addAttribute('rdf:resource', $document_urls,'rdf');
    $description_element->addChild('schema:url',null,'schema')->addAttribute('rdf:resource', $document_urls,'rdf');
    $description_element->addChild('schema:url',null,'schema')->addAttribute('rdf:resource', $document_urls,'rdf');
    $description_element->addChild('edm:isShownAt',null,'edm')->addAttribute('rdf:resource', $document_urls,'rdf');
    $description_element->addChild('edm:isShownBy',null,'edm')->addAttribute('rdf:resource', $document_urls,'rdf');
// For Language
$terms_lang = \Drupal\taxonomy\Entity\Term::loadMultiple($lang_tids);
foreach ($terms_lang as $term) {//foreach start
  $field_language_code_iso = $term->field_language_code_iso_639_1->getString();
  if (!$field_language_code_iso == null) {//if(!$field_language_code_iso == null) start
    $description_element->addChild('dct:language', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/language/'. $field_language_code_iso, 'rdf');
    $description_element->addChild('dct:language', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/language/'. $field_language_code_iso, 'rdf');

  }//if(!$field_language_code_iso == null) start
}//foreach end
// Add extent
$description_element->addChild('dct:extent', $page,'dct');

// Add publisher
$organizer_node = \Drupal\node\Entity\Node::loadMultiple($organizer_id);
foreach ( $organizer_node  as $key => $value) {
  $organizer= $value->uuid->value;
  $description_element->addChild('prov:wasDerivedFrom',null,'prov')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$organizer, 'rdf');
  $description_element->addChild('schema:provider',null,'schema')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$organizer, 'rdf');
   $description_element->addChild('edm:dataProvider',null,'edm')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$organizer, 'rdf');
}

 // Publish uri
 $publisher_node = \Drupal\node\Entity\Node::loadMultiple($publisher_id);
 foreach ( $publisher_node  as $key => $value) {
   $publisher= $value->uuid->value;
   $description_element->addChild('dct:publisher',null,'dct')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$publisher, 'rdf');
   $description_element->addChild('schema:publisher',null,'schema')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$publisher, 'rdf');
 }
    
// Add title
$description_element->addChild('dct:title', $title,'dct');
$description_element->addChild('schema:name', $title,'schema');

// Output RDF XML
echo $rdf->asXML();
exit();
?>
