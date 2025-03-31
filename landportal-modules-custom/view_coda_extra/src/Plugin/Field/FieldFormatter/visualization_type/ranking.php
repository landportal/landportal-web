<?php
if($check_vid == 'datasets'){
  $chart_html =  '<div id="vc1" class="entity entity-field-collection-item field-collection-item-field-visualization view-coda view-coda-ranking clearfix"><ul id="indicator-ranking-wrapper" class="view-coda-vis"> </ul></div>';

      $elements[33]['ranking_module'] = [
        '#type' => 'item',
        '#markup' => $chart_html,
        '#attached' => [
          'library' => [
            'view_coda_extra/my_library',
            'view_coda_extra/lp-related-content-edit',
            'view_coda_extra/indicators',
          ],
          'drupalSettings' => [
            'my_library' => [
              'indicator_ranking_id' => [$dataset_voc_field_id],
              'iso3' => 'PER',
              'visualization_type' => $visualization_type,
            ],
          ],
        ],
      ];
}
if($entity_name == "node"){
  $chart_html =  '<div id="vc1" class="entity entity-field-collection-item field-collection-item-field-visualization view-coda view-coda-ranking clearfix"><ul id="indicator-ranking-wrapper" class="view-coda-vis"> </ul></div>';

      $elements[33]['ranking_module'] = [
        '#type' => 'item',
        '#markup' => $chart_html,
        '#attached' => [
          'library' => [
            'view_coda_extra/my_library',
            'view_coda_extra/lp-related-content-edit',
            'view_coda_extra/indicators',
          ],
          'drupalSettings' => [
            'my_library' => [
              'indicator_ranking_id' => [$field_id_dataset_portfolio_indicator],
              'iso3' => 'PER',
              'visualization_type' => $visualization_type,
            ],
          ],
        ],
      ];
}
if($check_vid == 'indicators'){
  $chart_html =  '<div id="vc1" class="entity entity-field-collection-item field-collection-item-field-visualization view-coda view-coda-ranking clearfix"><ul id="indicator-ranking-wrapper" class="view-coda-vis"> </ul></div>';

      $elements[33]['ranking_module'] = [
        '#type' => 'item',
        '#markup' => $chart_html,
        '#attached' => [
          'library' => [
            'view_coda_extra/my_library',
            'view_coda_extra/lp-related-content-edit',
            'view_coda_extra/indicators',
          ],
          'drupalSettings' => [
            'my_library' => [
              'indicator_ranking_id' => [$field_id_get_indicator],
              'iso3' => 'PER',
              'visualization_type' => $visualization_type,
            ],
          ],
        ],
      ];
}
?>