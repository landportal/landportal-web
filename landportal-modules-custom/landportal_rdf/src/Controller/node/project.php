<?php

  $body_val = $node->body->value;
  $body_val_stp = strip_tags($body_val);
  $body_val_stp = str_replace('&nbsp;', '', $body_val_stp);
  $project_id = $node->field_id->value;
  $field_contect = $node->field_contact->value;
  $field_contact = strip_tags($field_contect);
  $extenal_link = $node->official_website->uri;

  // Data provider Field
  global $field_doc_provider_id;
  $field_doc_provider_id = array();
  foreach ($content['field_doc_provider']['widget'] as $key => $value) {
    if (is_numeric($key)) {
      $data_pro = $content['field_doc_provider']['widget'][$key];
      if (isset($data_pro['target_id']['#default_value'][0])) {
        $data_provider = $data_pro['target_id']['#default_value'][0]->get('nid')->value;
        $field_doc_provider_id[] = $data_provider;
      }
    }
  }

  // Donor(s) Field
  global $field_donors_id;
  $field_donors_id = array();
  foreach ($content['field_donors']['widget'] as $key => $value) {
    if (is_numeric($key)) {
      $donor = $content['field_donors']['widget'][$key];
      if (isset($donor['target_id']['#default_value'][0])) {
        $donors = $donor['target_id']['#default_value'][0]->get('nid')->value;
        $field_donors_id[] = $donors;
      }
    }
  }

  // Implementing Organization Field
  global $implementing_organization_id;
  $implementing_organization_id = array();
  foreach ($content['field_implementers']['widget'] as $key => $value) {
    if (is_numeric($key)) {
      $imp_org = $content['field_implementers']['widget'][$key];
      if (isset($imp_org['target_id']['#default_value'][0])) {
        $imp_organization = $imp_org['target_id']['#default_value'][0]->get('nid')->value;
        $implementing_organization_id[] = $imp_organization;
      }
    }
  }

  // Currency path
  global $currency_tid;
  $currency_tid = array();
  foreach ($content['field_currency']['widget'] as $key => $value) {
    if (is_numeric($key)) {
      $currency = $content['field_currency']['widget'][$key];
      if (isset($currency['target_id']['#default_value'][0])) {
        $currency_path = $currency['target_id']['#default_value'][0]->get('tid')->value;
        $currency_tid[] = $currency_path;
      }
    }
  }

  // Theme
  global $theme_tids;
  $theme_tids = array();
  foreach ($content['project_themes']['widget'] as $key => $value) {//foreach start
    if (is_numeric($key)) { //if (is_numeric($key)) start
      $theme_id = $content['project_themes']['widget'][$key];
      if (isset($theme_id['target_id']['#default_value'][0])) {//if (isset) start
        $theme_tid = $theme_id['target_id']['#default_value'][0]->get('tid')->value;
        $theme_tids[] = $theme_tid;
      }////if (isset) end
    }//if (is_numeric($key)) end
  }//foreach end

  // Concepts
  global $concepts_tids;
  $concepts_tids = array();
  foreach ($content['project_concepts']['widget'] as $key => $value) {//foreach start
    if (is_numeric($key)) {//if (is_numeric($key)) start
      $concept = $content['project_concepts']['widget'][$key];
      if (isset($concept['target_id']['#default_value'][0])) {//if (isset) start
        $concept_tid = $concept['target_id']['#default_value'][0]->get('tid')->value;
        $concepts_tids[] = $concept_tid;
      }//if (isset) end
    }//if (is_numeric($key)) end
  }//foreach end

  // Geoghraphical Focus
  global $geoghraphical_tids;
  $geoghraphical_tids = array();
  foreach ($content['project_regions']['widget'] as $key => $value) {//foreach start
    if (is_numeric($key)) {//if (is_numeric($key) start
      $country_id = $content['project_regions']['widget'][$key];
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

  $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://xmlns.com/foaf/0.1/Project', 'rdf');
  $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://purl.org/collections/iati/activity', 'rdf');

  // Add the body property
  $body = $description->addChild('dct:description', $body_val_stp, 'dct'); 
  $body = $description->addChild('schema:description', $body_val_stp, 'schema');
  $body = $description->addChild('iati:description-text', $body_val_stp, 'iati');
  // For Geoghraphical subject
  $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
  foreach ($geo_terms as $key => $geo_term) {// foreach start
    $area_code = $geo_term->get('field_area_code_un_m_49_')->getString();
    $iso3 = $geo_term->get('field_iso3')->getString();
    if (!$area_code == null) {// if start
      $description->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
    }// if end 
    if (!$iso3 == null) { // start if
      $description->addChild('dct:spatial', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
    } // if end               
  }// foreach end

  // For Geoghraphical schema
  $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
  foreach ($geo_terms as $key => $geo_term) {// foreach start
    $area_code = $geo_term->get('field_area_code_un_m_49_')->getString();
    $iso3 = $geo_term->get('field_iso3')->getString();
    if (!$area_code == null) {// if start
      $description->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
    } // if end
    if (!$iso3 == null) { // if start
      $description->addChild('schema:spatialCoverage', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
    }  // if end             
  }//foreach end

  // For Geoghraphical iati:activity-recipient-country
  $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
  foreach ($geo_terms as $key => $geo_term) {// foreach start
    $area_code = $geo_term->get('field_area_code_un_m_49_')->getString();
    $iso3 = $geo_term->get('field_iso3')->getString();
    if (!$area_code == null) {// if start
      $description->addChild('iati:activity-recipient-country', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
    } // if end
    if (!$iso3 == null) { // if start
      $description->addChild('iati:activity-recipient-country', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
    }  // if end             
  }//foreach end

  // For Geoghraphical iati:activity-recipient-region
  $geo_terms = \Drupal\taxonomy\Entity\Term::loadMultiple($geoghraphical_tids);
  foreach ($geo_terms as $key => $geo_term) {// foreach start
    $area_code = $geo_term->get('field_area_code_un_m_49_')->getString();
    $iso3 = $geo_term->get('field_iso3')->getString();
    if (!$area_code == null) {// if start
      $description->addChild('iati:activity-recipient-region', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$area_code, 'rdf');
    } // if end
    if (!$iso3 == null) { // if start
      $description->addChild('iati:activity-recipient-region', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/geo/'.$iso3, 'rdf');
    }  // if end             
  }//foreach end

  // For Themes subject
  $terms_themes = \Drupal\taxonomy\Entity\Term::loadMultiple($theme_tids);
  foreach ($terms_themes as $term) { // foreach start
    $feild_id = $term->field_id->value;
    $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
  }// foreach end

  // For Concepts subject
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

  // For Concepts schema
  $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($concepts_tids);
  foreach ($term_concepts as $key => $concepts) {// foreach start
    $agrov = $concepts->get('field_agrovoc_uri')->getString();
    $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
  }//foreach end

  // Add the title property
  $title = $description->addChild('dct:title', $title_val, 'dct');

  // Add the title property
  $title = $description->addChild('dct:title', $title_val, 'dct');

  // Add the title label property
  $title_label = $description->addChild('rdfs:label', $title_val, 'rdfs');    

  // Add the title label property
  $title_label = $description->addChild('rdfs:label', $title_val, 'rdfs');  

  // Add the title schema property
  $title_schema_name = $description->addChild('schema:name', $title_val, 'schema');    

  // Add the title schema property
  $title_schema_name = $description->addChild('schema:name', $title_val, 'schema');  

  // Add the title foaf property
  $title_foaf_name = $description->addChild('foaf:name', $title_val, 'foaf');    

  // Add the title foaf property
  $title_foaf_name = $description->addChild('foaf:name', $title_val, 'foaf'); 

  //Add Image, thumnanil, sharedContent.
  $image_url = '';
  if(isset($node->project_image->entity)){ //if(isset) start
    $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($node->project_image->entity->getFileUri()); 
    $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
  }// if(isset) end

  // Add Child of identifier
  if(isset($project_id)){
    $description->addChild('dct:identifier', $project_id, 'dct');
    $description->addChild('iati:activity-id', $project_id, 'iati');
  }

  // Add Child of contactPoint
  if(!$field_contact == null){
    $description->addChild('schema:contactPoint', $field_contact, 'schema');
    $description->addChild('iati:activity-contact-info', $field_contact, 'iati');
  }
  // Add Website Link 
  if(isset($extenal_link)){
    $description->addChild('schema:url', null, 'schema')->addAttribute('rdf:resource',$extenal_link, 'rdf');
    $description->addChild('foaf:homepage', null, 'foaf')->addAttribute('rdf:resource',$extenal_link, 'rdf');
    $description->addChild('iati:activity-website', null, 'iati')->addAttribute('rdf:resource',$extenal_link, 'rdf');
  }

  // Data Provider 
  $data_provider_node = \Drupal\node\Entity\Node::loadMultiple($field_doc_provider_id);
  foreach ($data_provider_node as $key => $value) {
    $data_provider = $value->uuid->value;
    $description->addChild('schema:provider', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$data_provider, 'rdf');
  }

  // Implementing Organization
  $organization_node = \Drupal\node\Entity\Node::loadMultiple($implementing_organization_id);
  foreach ($organization_node as $key => $value) {
    $implementing_organization = $value->uuid->value;
    $description->addChild('iati:activity-participating-org', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$implementing_organization, 'rdf');
  }

  // Donor Field
  $donor_node = \Drupal\node\Entity\Node::loadMultiple($field_donors_id);
  

  foreach ($donor_node as $key => $value) {
    $donors = $value->uuid->value;
    $description->addChild('iati:activity-participating-org', null, 'iati')->addAttribute('rdf:resource','http://data.landportal.info/organization/'.$donors, 'rdf');
  }


  // Currency path
  $curreny_term = \Drupal\taxonomy\Entity\Term::loadMultiple($currency_tid);

  foreach ($curreny_term as $key => $curreny_terms) {
    $term_id = $curreny_terms->id();
    $aliasManager = \Drupal::service('path_alias.manager');
    $alias = $aliasManager->getAliasByPath('/taxonomy/term/'.$term_id);
    $description->addChild('schema:currency', null, 'schema')->addAttribute('rdf:resource',$host.$alias, 'rdf');
    $description->addChild('iati:value-currency', null, 'iati')->addAttribute('rdf:resource',$host.$alias, 'rdf');
  }

  // Project Value
  if (isset($node->field_value_decimal->value)) {
    $project_value = $node->field_value_decimal->value;
    $description->addChild('iati:value', $project_value, 'iati');
  }

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