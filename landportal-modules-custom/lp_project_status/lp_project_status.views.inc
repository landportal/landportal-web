<?php

/**
 * Implements hook_views_data_alter().
 */
function lp_project_status_views_data_alter(array &$data) {

  $data['node']['node_type_flagger'] = array(
    'title' => t('Project detail'),
    'group' => t('Global'),
    'field' => array(
      'title' => t('Project detail'),
      'help' => t('Get project detail.'),
      'id' => 'node_type_flagger',
    ),
  );

  $data['node_field_data']['node_type_filter'] = array(
    'title' => t('Enhanced node type filter'),
    'group' => t('Global'),
    'filter' => array(
      'title' => t('Enhanced node type filter'),
      'help' => t('Provides a custom filter for nodes by their type.'),
      'field' => 'type',
      'id' => 'string'
    ),
  );

  $data['node__field_project_duration']['project_status'] = array(
    'title' => t('Project status'),
    'group' => t('Global'),
    'filter' => array(
      'title' => t('Project status'),
      'help' => t('Please select the project status.'),
      'field' => 'field_project_duration_value',
      'id' => 'lp_project_status_type_of_project'
    ),
  );
}
