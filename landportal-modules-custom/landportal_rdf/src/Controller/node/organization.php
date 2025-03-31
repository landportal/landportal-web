<?php
$body_val = $node->body->value;
$body_val_stp = strip_tags($body_val);
$body_val_stp = str_replace('&nbsp;', '', $body_val_stp);

$acronym_field= $content['field_acronym']['widget'][0]['value']['#default_value'];
$focal_point = $node->field_contact->value;
$postal_addresss = $node->field_postal_address->value;
//Languages
global $lang_tids;
$lang_tids = array();

foreach ($content['field_language']['widget'] as $key => $value) { //foreach start
  if (is_numeric($key)) { //if (is_numeric($key)) start
    $lang_id = $content['field_language']['widget'][$key];
    if (isset($lang_id['target_id']['#default_value'][0])) {//if (isset) start
      $lang_tid = $lang_id['target_id']['#default_value'][0]->get('tid')->value;
      $lang_tids[] = $lang_tid;
    }//if (isset) end
  }// if(is_numeric($key)) end
}// foreach end

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


// xml start
$rdf = new SimpleXMLElement('<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dct="http://purl.org/dc/terms/" xmlns:schema="http://schema.org/" xmlns:ns0="http://example.com/ns0"/>');


// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
$description->addAttribute('rdf:about', 'http://data.landportal.info/'.$bundle_hyph.'/'.$uuid, 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://xmlns.com/foaf/0.1/Organization', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://schema.org/Organization', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/ns/prov#Entity', 'rdf');
$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/ns/org#Organization', 'rdf');

  // Add the body property
$body = $description->addChild('dct:description', $body_val_stp, 'dct'); 
$body = $description->addChild('schema:description', $body_val_stp, 'schema');

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
    $description->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
  }// if end 
  if (!$iso3 == null) { // start if
    $description->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
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
    $description->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
  } // if end
  if (!$iso3 == null) { // if start
    $description->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
  }  // if end             
}//foreach end

// For Themes subject
$terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($theme_tids);
foreach ($terms_themes as $term) { // foreach start
  $feild_id = $term->field_id->value;
  $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}// foreach end

//For Concepts subject
$term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($concepts_tids);
foreach ($term_concepts as $key => $concepts) {//foreach start
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
}// foreach end

// For Themes Schema
$terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($theme_tids);
foreach ($terms_themes as $term) {//foreach start
  $feild_id = $term->field_id->value;
  $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
}// foreach end

//For Concepts schema
$term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($concepts_tids);
foreach ($term_concepts as $key => $concepts) {// foreach start
  $agrov = $concepts->get('field_agrovoc_uri')->getString();
  $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
}//foreach end

//Add Image, thumnanil, sharedContent.
$image_url = '';
if(isset($node->field_image->entity)){ //if(isset) start
  $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_image->entity->getFileUri()); 
  $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
  if(!$bundle == 'organization'){// if(!$bundle) start
    $description->addChild('schema:thumbnailUrl', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
    $description->addChild('schema:sharedContent', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
  }// if(!$bundle) end
}// if(isset) end

// Website URL link 
foreach ($node->field_link as $key => $value) {//foreach start
  $website_uri = $value->uri;
  if (isset($website_uri) ) {//if(isset) start
    $website_uri_xml = $description->addChild('schema:url', null, 'url')->addAttribute('rdf:resource', $website_uri, 'rdf');
    $description->addChild('foaf:homepage', null, 'foaf')->addAttribute('rdf:resource', $website_uri, 'rdf');
  }//if(isset) end
}//foreach end

// Focal Point 
$focal_point_xml = $description->addChild('schema:contactPoint', $focal_point, 'schema');

// For Language
$terms_lang = \Drupal\taxonomy\Entity\Term::loadMultiple($lang_tids);
foreach ($terms_lang as $term) {//foreach start
  $field_language_code_iso = $term->field_language_code_iso_639_1->getString();
  if (!$field_language_code_iso == null) {//if(!$field_language_code_iso == null) start
    $description->addChild('lingvo:workingLanguage', null, 'lingvo')->addAttribute('rdf:resource','http://data.landportal.info/voc/language/'. $field_language_code_iso, 'rdf');

  }//if(!$field_language_code_iso == null) start
}//foreach end

// Organization Types 
foreach ($content['field_organization_type']['widget'] as $key => $value) {//forach start
  $org_type = $content['field_organization_type']['widget'][$key];
  if(isset($org_type['target_id']['#default_value'][0])){// if(isset) start
    $orgnaization_type_name_obj = $org_type['target_id']['#default_value'][0]->get('name');
    if(isset($orgnaization_type_name_obj->value)){ //if(isset) inner start
      $orgnaization_typ = $orgnaization_type_name_obj->value;
      $orgnaization_type = strtolower(str_replace(' ', '-', $orgnaization_typ));
      $org = $description->addChild('dct:type', null, 'dct')->addAttribute('rdf:resource', 'http://data.landportal.info/voc/organization-types/'.$orgnaization_type, 'rdf');
    }//if(isset) inner end
  }// if(isset) end
}//foreach end

// Acronym 
if(!$acronym_field == null){
  $acronym = $description->addChild('dct:identifier', $acronym_field, 'dct');
  $acronym_org = $description->addChild('org:identifier', $acronym_field, 'org');
}

if (!$postal_addresss  == null) {
  $description->addChild('schema:address', $postal_addresss, 'schema');
}

  // Add the title property
$title = $description->addChild('dct:title', $title_val, 'dct');

// Add the title healine property
$title_healine = $description->addChild('schema:headline', $title_val, 'schema');   

// Add the title label property
$title_label = $description->addChild('rdfs:label', $title_val, 'rdfs');    

// Add the title schema property
$title_schema_name = $description->addChild('schema:name', $title_val, 'schema');    

// Add the title foaf property
$title_foaf_name = $description->addChild('foaf:name', $title_val, 'foaf');    

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