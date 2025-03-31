<?php

/**
 * Implements hook_views_data_alter().
 */
function landportal_provider_filter_views_data_alter(array &$data)
{

  // $data['taxonomy_term_field_data']['datasets_title'] = array(
  //   'title' => t('Filtered Datasets  title'),
  //   'group' => t('Custom Fields'),
  //   'filter' => array(
  //     'title' => t('Filtered Datasets  title'),
  //     'help' => t('Specify a list of Filtered Datasets titles.'),
  //     'field' => 'title',
  //     'id' => 'custom_views_datasets_title'
  //   ),
  // );

  $data['taxonomy_term_field_data']['datasets_names'] = array(
    'title' => t('List of Datasets title'),
    'group' => t('Custom Filters'),
    'filter' => array(
      'title' => t('Provider'),
      'help' => t('Filter by Provider'),
      'field' => 'field_dataset',
      'id' => 'custom_views_field_dataset'
    ),
  );


  $data['taxonomy_term_field_data']['datasets_names_page'] = array(
    'title' => t('List of DatasetsPage title'),
    'group' => t('Custom Filters Dataset Page'),
    'filter' => array(
      'title' => t('Provider'),
      'help' => t('Filter by Provider'),
      'field' => 'field_dataset',
      'id' => 'custom_views_field_dataset_page'
    ),
  );


  $data['taxonomy_term_field_data']['layer_names_page'] = array(
    'title' => t('List of LayerPage title'),
    'group' => t('Custom Filters Layer Page'),
    'filter' => array(
      'title' => t('Provider'),
      'help' => t('Filter by Provider'),
      'field' => 'field_dataset',
      'id' => 'custom_views_field_layer_page'
    ),
  );



  $data['taxonomy_term_field_data']['issue_names'] = array(
    'title' => t('List of Issue Name'),
    'group' => t('Custom Issue Filters'),
    'filter' => array(
      'title' => t('Issue'),
      'help' => t('Filter by Issue List'),
      'field' => 'field_related_themes',
      'id' => 'custom_views_field_related_themes'
    ),
  );

  $data['taxonomy_term_field_data']['issue_datasets_names'] = array(
    'title' => t('List of IssueDatasets Name'),
    'group' => t('Custom IssueDatasets Filters'),
    'filter' => array(
      'title' => t('Issue'),
      'help' => t('Filter by IssueDatasets List'),
      'field' => 'field_related_themes',
      'id' => 'custom_views_issue_Datasets_field_related_themes'
    ),
  );

  $data['taxonomy_term_field_data']['issue_layer_names'] = array(
    'title' => t('List of IssueLayer Name'),
    'group' => t('Custom IssueLayer Filters'),
    'filter' => array(
      'title' => t('Issue'),
      'help' => t('Filter by IssueLayer List'),
      'field' => 'field_related_themes',
      'id' => 'custom_views_issue_layer_field_related_themes'
    ),
  );
  
  $data['node_field_data']['event_issue'] = array(
    'title' => t('Event Issue'),
    'group' => t('Custom Event Issue name'),
    'filter' => array(
      'title' => t('Event Issue name'),
      'help' => t('Filter by EventIssue'),
      'field' => 'field_related_themes',
      'id' => 'custom_views_event_issue'
    ),
  );

  $data['node_field_data']['event_geo'] = array(
    'title' => t('Event Geographical'),
    'group' => t('Custom Event Geographical name'),
    'filter' => array(
      'title' => t('Event Geographical name'),
      'help' => t('Filter by EventGeographical'),
      'field' => 'field_geographical_focus',
      'id' => 'custom_views_event_geo'
    ),
  );

  $data['node_field_data']['event_date'] = array(
    'title' => t('Event Date'),
    'group' => t('Custom Event Date'),
    'filter' => array(
      'title' => t('Event Date List'),
      'help' => t('Filter by EventDate'),
      'field' => 'field_date',
      'id' => 'custom_views_event_date'
    ),
  );

}
