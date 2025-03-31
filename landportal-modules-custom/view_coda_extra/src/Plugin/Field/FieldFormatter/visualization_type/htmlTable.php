 <?php

  $indicator_ids_arr = array();
  // Check indicator
  if ($indicator != null) {
    preg_match_all('/(?<=\()\d+(?=\))/', $indicator, $matches);
    $indicator_ids_arr = $matches[0];
    \Drupal::logger('indicator_ids_arr-numbers')->warning(serialize($indicator_ids_arr));
  }

  // Start table
  $full_html_table = '<div id="html-table-description">' . $indicator_description . '</div><div id="html-table">';
  $full_html_table .= '<table class="table">';
  $full_html_table .= '<thead><tr>
            <th>Indicator</th>
            <th>Min-Max<br/>Number of years</th>
            <th><span class="hidden-xs">Countries</span> / <acronym title="Number of Observations">Obs</acronym></th>
            <th>Min / Max<span class="hidden-xs"> Value</span></th>
          </tr></thead>';
  $full_html_table .= '<tbody>';

  // $indicator_sparql_field_id_for_table = [];
  // Loop through indicator IDs
  foreach ($indicator_ids_arr as $key => $indicator_id) {

    $load_indicator_term = \Drupal\taxonomy\Entity\Term::load($indicator_id);

    // Indicator Name
    $indicator_name = $load_indicator_term->name->getString();
    // $indicatorTitle = $load_indicator_term->field_indicators->indicator_title;

    \Drupal::logger('load_indicator_term')->warning(serialize($load_indicator_term));

    $indicator_description_indicator = $load_indicator_term->field_description->value;

    $indicator_description_strip = htmlspecialchars(strip_tags($indicator_description_indicator), ENT_QUOTES);

    // print(serialize($indicator_description));
    // print("</br> next1 </br></br>");
    $indicator_unit = $load_indicator_term->field_unit->value;

    // Get Dataset Name
    $dataset_id = $load_indicator_term->field_dataset->target_id;
    $dataset_load = \Drupal\taxonomy\Entity\Term::load($dataset_id);
    $dataset_name = $dataset_load->name->value;

    // Get Source Name
    $dataset_field_orgref = $dataset_load->field_orgref->target_id;
    $dataset_field_orgref_load = \Drupal\node\Entity\Node::load($dataset_field_orgref);
    $field_orgref_name = $dataset_field_orgref_load->title->value;

    // Visualization type of indicator
    $visualization_type = $load_indicator_term->field_indicators->visualization;

    $indicator_sparql_field_id = $load_indicator_term->get('field_id')->getString();

    if (!in_array($indicator_sparql_field_id, $indicator_sparql_field_id_for_table)) {
      // If it's not in the distinct array, add it
      $indicator_sparql_field_id_for_table[] = $indicator_sparql_field_id;
    }
    //\Drupal::logger('indicator_sparql_field_id_for_table')->notice(serialize($indicator_sparql_field_id_for_table));

    // Add row to the table
    $indicator_path = '/taxonomy/term/' . $indicator_id;
    $full_html_table .= '<tr lbid="' . $indicator_sparql_field_id . '">
              <td>
                <span class="indicator">
                  <span>
                    <a href="' . $indicator_path . '">' . $indicator_name . '</a>
                    <span class="bi bi-question-circle-fill" data-toggle="tooltip" data-placement="top" title=""  data-original-title="' . $indicator_description_strip . '"></span>
                    <span class="bi bi-info-circle-fill" data-toggle="tooltip" data-placement="top" title="" data-original-title="Unit: ' . $indicator_unit . ' Dataset: ' . $dataset_name . ' Source: ' . $field_orgref_name . '"></span>
                  </span>
                </span>
              </td>
            </tr>';
  } // end of foreach loop

  // Close table
  $full_html_table .= '</tbody></table>';
  $full_html_table .= '</div>';

  $elements[$key][$delta . 'indicator_html_table'] = [
    '#type' => 'item',
    '#markup' => '<h2>' . $indicator_title . '</h2>' . $full_html_table,
    '#attached' => [
      'library' => [
        'view_coda_extra/my_library',
        'view_coda_extra/lp-related-content-edit',
        'view_coda_extra/indicators',
      ],
      'drupalSettings' => [
        'my_library' => [
          'indicator_table_id' => $indicator_sparql_field_id_for_table,
          'dataset_field_id' => $dataset_field_id,
          'check_vid' => $check_vid,
          'visualization_type' => $visualization_type,
          'visualization_type_arr' => $visualization_type_arr,
        ],
      ],
      'library' => ['view_coda_extra/lp-related-content-edit'],
    ],
  ];

  ?>