<?php
$pub_date = $node->field_pubdate->getString();
$publish_date = explode('T', $pub_date)[0];
$authors = $node->field_doc_people->getString();

//Theme
global $theme_tids;
$theme_tids = array();
foreach ($content['field_related_themes']['widget'] as $key => $value) {
  if (is_numeric($key)) {
    $themes_tid = $content['field_related_themes']['widget'][$key];
    if (isset($themes_tid['target_id']['#default_value'][0])) {
      $all_themes = $themes_tid['target_id']['#default_value'][0]->get('tid')->value;
      $theme_tids[] = $all_themes;
    }
  }
}

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
$rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dct="http://purl.org/dc/terms/"
  xmlns:schema="http://schema.org/"
  xmlns:ns0="dc:"
  xmlns:sioc="http://rdfs.org/sioc/ns#"/>');

// Create the Description element with the about attribute
$description = $rdf->addChild('rdf:Description');
$description->addAttribute('rdf:about', 'http://data.landportal.info/'.$bundle_hyph.'/'.$uuid, 'rdf');

$description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://schema.org/NewsArticle', 'rdf');

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
  //$image_url = file_create_url($node->field_image->entity->getFileUri()); 
  $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->field_image->entity->getFileUri()); 
  $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
  $description->addChild('schema:thumbnailUrl', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');

}// if(isset) end

//auhtors
if (!$authors == null) {
  $description->addChild('ns0:creator', $authors, 'ns0');
  $description->addChild('schema:author', $authors, 'schema');
}

// publish date
$pub_date = $description->addChild('dct:issued', $publish_date, 'dct');
$pub_date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#date', 'rdf');

$pub_date = $description->addChild('schema:datePublished', $publish_date, 'schema');
$pub_date->addAttribute('rdf:datatype', 'http://www.w3.org/2001/XMLSchema#date', 'rdf');

// For Language
$terms_lang = \Drupal\taxonomy\Entity\Term::loadMultiple($lang_tids);
foreach ($terms_lang as $term) {//foreach start
  $field_language_code_iso = $term->field_language_code_iso_639_1->getString();
  if (!$field_language_code_iso == null) {//if start

    $description->addChild('dct:language', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/language/'. $field_language_code_iso, 'rdf');

    $description->addChild('schema:inLanguage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/language/'. $field_language_code_iso, 'rdf');

  }//if start
}//foreach end

foreach ($node->field_link as $key => $value) {
  if (!$value == null) {
    if (isset($value->uri)) {
      $source = $value->uri;
      $description->addChild('dct:source', null, 'dct')->addAttribute('rdf:resource', $source, 'rdf');
      $description->addChild('schema:citation', null, 'schema')->addAttribute('rdf:resource', $source, 'rdf');
    }
  }
}


// Add the title property
$title = $description->addChild('dct:title', $title_val, 'dct');

// Add the title healine property
$title_healine = $description->addChild('schema:headline', $title_val, 'schema');   

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

$description->addChild('sioc:num_replies', $comments, 'sioc')->addAttribute('rdf:resource',  "
  http://www.w3.org/2001/XMLSchema#integer", 'rdf');

$xmlString = $rdf->asXML();

// Remove the xmlns:rdfs attribute
$xmlString = str_replace(' xmlns:dct="dct"', '', $xmlString);
$xmlString = str_replace(' xmlns:schema="schema"', '', $xmlString);
$xmlString = str_replace(' xmlns:ns0="ns0"', '', $xmlString);
$xmlString = str_replace(' xmlns:sioc="sioc"', '', $xmlString);


header('Content-Type: application/xml; charset=UTF-8');
// echo $rdf->saveXML();
echo $xmlString;
exit();
?>