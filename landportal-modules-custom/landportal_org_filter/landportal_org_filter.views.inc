<?php

/**
 * Implements hook_views_data_alter().
 */
function landportal_org_filter_views_data_alter(array &$data) {

   $data['node_field_data']['org_titles'] = array(
     'title' => t('Filtered Organization title'),
     'filter' => array(
       'title' => t('Filtered Organization title'),
       'help' => t('Specify a list of Filtered Organization titles.'),
       'field' => 'title',
       'id' => 'custom_views_org_titles'
     ),
   );

    $data['node_field_data']['org_types'] = array(
   'title' => t('Count Organization title'),
   'filter' => array(
     'title' => t('Count Organization title'),
     'help' => t('Count Specify a list of titles a node can have.'),
     'field' => 'field_organization_type',
     'id' => 'custom_views_org_types'
   ),
 );
}
