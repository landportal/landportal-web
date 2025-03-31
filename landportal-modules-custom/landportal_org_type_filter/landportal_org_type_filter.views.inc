<?php

/**
 * Implements hook_views_data_alter().
 */
function landportal_org_type_filter_views_data_alter(array &$data){


  $data['taxonomy_term_field_data']['org_type_name'] = array(
    'title' => t('List of orgnization type name'),
    'group' => t('Custom Filters'),
    'filter' => array(
      'title' => t('Orgnization Types'),
      'help' => t('Filter by Organization type'),
      'field' => 'field_related_themes',
      'id' => 'custom_views_org_type'
    ),
  );

  $data['taxonomy_term_field_data']['license_resource'] = array(
    'title' => t('List of License name'),
    'group' => t('Custom Filters License name'),
    'filter' => array(
      'title' => t('License of the resource'),
      'help' => t('Filter by License'),
      'field' => 'field_doc_licencing',
      'id' => 'custom_views_license_resource'
    ),
  );

  $data['taxonomy_term_field_data']['license_resource_theme'] = array(
    'title' => t('List of LicenseTheme name'),
    'group' => t('Custom Filters License Theme name'),
    'filter' => array(
      'title' => t('License of the resource'),
      'help' => t('Filter by LicenseTheme'),
      'field' => 'field_doc_licencing',
      'id' => 'custom_views_license_resource_theme'
    ),
  );

  $data['taxonomy_term_field_data']['resource_type'] = array(
    'title' => t('Resource type'),
    'group' => t('Custom Resource type name'),
    'filter' => array(
      'title' => t('Resource type of the Library'),
      'help' => t('Filter by Resource type'),
      'field' => 'field_doc_type',
      'id' => 'custom_views_resource_type'
    ),
  );
}