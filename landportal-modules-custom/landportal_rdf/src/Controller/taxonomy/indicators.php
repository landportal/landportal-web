<?php
    // Field Id of Taxonomy Term
    $field_id = $taxonomy->field_id->value;

    // Condition To Check License     
    if (is_object($taxonomy->field_license_text)) {
      $copyrighttext = $taxonomy->field_license_text->value;
      $license_text = strip_tags($copyrighttext);
      $license_text = str_replace('&nbsp;', '', $license_text);
    }

    $description = $taxonomy->field_description->value;
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

    //Get Organization reference Ids
    global $data_id;
    $data_id = [];
    if (isset($taxonomy->field_orgref) && is_array($taxonomy->field_orgref)) {
      foreach ($taxonomy->field_orgref as $key => $value) {
        $data = $taxonomy->field_orgref[$key]->target_id;
        $data_id[] = $data;
      }
    }

    //Get license Ids
    global $licence_tids;
    $licence_tids = [];
    if (!empty($taxonomy->field_doc_licencing)) {
      foreach ($taxonomy->field_doc_licencing as $key => $value) {
        $licence = $taxonomy->field_doc_licencing[$key]->target_id;
        $licence_tids[] = $licence;
      }
    }

    //Get themes Ids
    global $field_theme_tids;
    $field_theme_tids = array();
    foreach ($taxonomy->field_related_themes as $key => $value) {
      $field_theme_tids[] = $taxonomy->field_related_themes[$key]->target_id;
     }

    //Get Concepts Ids
    global $field_concepts_tids;
    $field_concepts_tids = array();
    foreach ($taxonomy->field_related_topics as $key => $value) {
      $field_concepts_tids[] = $taxonomy->field_related_topics[$key]->target_id;
     }




 //Rdf/XML body start

    $rdf = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dct="http://purl.org/dc/terms/"
  xmlns:skos="http://www.w3.org/2004/02/skos/core#"
  xmlns:sdmx-attribute="http://purl.org/linked-data/sdmx/2009/attribute#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:schema="http://schema.org/" />');

    // Create the Description element with the about attribute
    $description = $rdf->addChild('rdf:Description');
    $description->addAttribute('rdf:about', 'http://data.landportal.info/indicator/'.$field_id, 'rdf');

    //Below this all XML elements are Child of Description
    $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://www.w3.org/2004/02/skos/core#Concept', 'rdf');
    $description->addChild('rdf:type', null, 'rdf')->addAttribute('rdf:resource', 'http://purl.org/weso/ontology/computex#Indicator', 'rdf');


 //ID field data show 
    if(!$field_id == null){
      $description->addChild('dct:identifier', $field_id, 'dct');
      $description->addChild('skos:notation', $field_id, 'skos');
    }

    //concepts subject
    $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids);
    foreach ($term_concepts as $key => $concepts) {
      $agrov = $concepts->get('field_agrovoc_uri')->getString();
      $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource',$agrov, 'rdf');
    }


     // get also unpublish term data
    $terms_themes = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($field_theme_tids, ['include_unpublished' => TRUE]);
    foreach ($terms_themes as $term) {
      $feild_id = $term->field_id->value;
      $description->addChild('dct:subject', null, 'dct')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
    }


    //concepts schema
    $term_concepts = \Drupal\taxonomy\Entity\Term::loadMultiple($field_concepts_tids, ['access_check' => FALSE]);
    foreach ($term_concepts as $key => $concepts) {
      $agrov = $concepts->get('field_agrovoc_uri')->getString();
      $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource',$agrov, 'rdf');
     }


     // get also unpublish term data
    $terms_themes = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadMultiple($field_theme_tids, ['include_unpublished' => TRUE]);
    foreach ($terms_themes as $term) {
      $feild_id = $term->field_id->value;
      $description->addChild('schema:about', null, 'schema')->addAttribute('rdf:resource','http://data.landportal.info/voc/landvoc/theme/'. $feild_id, 'rdf');
    }


    // image schema, logo schema
    if (isset($taxonomy->field_image->entity)) {
      $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($taxonomy->field_image->entity->getFileUri()); 
      $description->addChild('schema:image', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
      $description->addChild('schema:logo', null, 'schema')->addAttribute('rdf:resource', $image_url, 'rdf');
     }

    // For Publisher Link
    $data_term = \Drupal\node\Entity\Node::loadMultiple($data_id);
    foreach ($data_term as $key => $value) {
      $publisher_link  =$value->uuid->value;
      $description->addChild('dct:publisher', null, 'dct')->addAttribute('rdf:resource', 'http://data.landportal.info/organization/'.$publisher_link, 'rdf');
     }


    // For License Link || URI
    $licenc_term = \Drupal\taxonomy\Entity\Term::loadMultiple($licence_tids);
    foreach ($licenc_term as $term) {
      $rdflice_uri = $term->field_rdflicense_uri->uri;
      $description->addChild('dct:license', null, 'dct')->addAttribute('rdf:resource', $rdflice_uri, 'rdf'); 
      $description->addChild('schema:license', null, 'schema')->addAttribute('rdf:resource', $rdflice_uri, 'rdf');
     }



    //Copy right
    if($vid == 'datasets'){
      $description->addChild('ns0:rights', $license_text, 'ns0')->addAttribute('rdf:datatype', "http://www.w3.org/2001/XMLSchema#string", 'rdf');
     }

    if (isset($dataset_field_id)) {
     $description->addChild('dct:source', null, 'dct')->addAttribute('rdf:resource', "http://data.landportal.info/dataset/".$dataset_field_id, 'rdf');
     }

    //  unitMeasure In Indicators
    if($vid == 'indicators'){
      if(!$measurement_unit == null){
        $description->addChild('sdmx-attribute:unitMeasure', $measurement_unit, 'sdmx-attribute');
      }
     }

    //Title skos, rdfs, dct.
  if(!$title_val == null){
      $description->addChild('rdfs:label', $title_val, 'rdfs');
      $description->addChild('skos:prefLabel', $title_val, 'skos');
      $description->addChild('dct:title', $title_val, 'dct');
    }

  if(!$dataset_description == null){
      //description skos, dct
      $description->addChild('skos:definition', $dataset_description, 'skos');
      $description->addChild('dct:description', $dataset_description, 'dct');
      $description->addChild('rdfs:comment', $dataset_description, 'rdfs');
    }


  // For Vocabulary Link 
  $description->addChild('skos:inScheme', null, 'skos')->addAttribute('rdf:resource','http://data.landportal.info/vocabulary/'.$vocubalry_id, 'rdf'); 

  // For Full term Link 
  $description->addChild('rdfs:seeAlso', null, 'rdfs')->addAttribute('rdf:resource',$host.$alias, 'rdf');


  $xmlString = $rdf->asXML();

  // Remove the xmlns:rdfs attribute
  $xmlString = str_replace(' xmlns:rdfs="rdfs"', '', $xmlString);
  $xmlString = str_replace(' xmlns:skos="skos"', '', $xmlString);
  $xmlString = str_replace(' xmlns:rdf="rdf"', '', $xmlString);
  $xmlString = str_replace(' xmlns:dct="dct"', '', $xmlString);  
  $xmlString = str_replace(' xmlns:sdmx-attribute="sdmx-attribute"', '', $xmlString);
  $xmlString = str_replace(' xmlns:schema="schema" ', ' ', $xmlString);



  // set header type of XML
  header('Content-Type: application/xml; charset=UTF-8');
  //header('Content-Disposition: attachment; filename="data.rdf"');

  // RDF Save and print on screen
  // echo $rdf->saveXML();
  echo $xmlString;
  exit();



?>