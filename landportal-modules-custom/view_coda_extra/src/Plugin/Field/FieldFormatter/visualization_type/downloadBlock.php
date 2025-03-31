<?php
if($entity_name1 == "node"){
  if($get_bundle == 'dataset'){
    // \Drupal::logger('check_vid_download')->notice(serialize($field_id_dataset_portfolio_id));
    $elements[32][$delta . 'download_module'] = [
      '#type' => 'item',
      '#attached' => [
        'library' => [
           'view_coda_extra/my_library',
          'view_coda_extra/lp-related-content-edit',
          'view_coda_extra/indicators',
        ],
        'drupalSettings' => [
          'my_library' => [
            'dataset_portfolio_field_id' => $field_id_dataset_portfolio_id,
            'check_bundle' => 'dataset',
          ],
        ],
      ],
    ];
  }
}
else{  
  $elements[32][$delta . 'download_module'] = [
    '#type' => 'item',
    '#attached' => [
      'library' => [
         'view_coda_extra/my_library',
        // 'view_coda_extra/lp-related-content-edit',
        // 'view_coda_extra/indicators',
      ],
      'drupalSettings' => [
        'my_library' => [
          'indicator_download_id' => $field_id_get_indicator,
          'dataset_field_id' => $dataset_field_id,
          'check_vid' => $check_vid,
        ],
      ],
    ],
  ];

}
















//for Indicator Download 
    // $download_coda =  '<div id="block-landbook-view-coda-lbvc-download-ind"><div id="block-landbook-view-coda-lbvc-download-ind-wrapper"><p>Download this Indicator data.</p></div></div>';

      // $elements[$key][$delta . 'indicator_download'] = [
      //   '#type' => 'item',
      //   // '#markup' => $download_coda,
      //       '#attached' => [
      //         'library' => ['landbook_view_coda/lbvis.dl'],
      //         'library' => ['view_coda_extra/my_library'],
      //       //   'drupalSettings' => [
      //       //     'my_library' => [
      //       //       // 'indicator_download_id' => $indicator_sparql_field_id_for_map,
      //       //     ],
      //       //   'library' => ['view_coda_extra/lp-related-content-edit'],
      //       // ],            
      //     ]
      //    ];


//for Dataset Download 
    // $download_coda =  '<div id="block-landbook-view-coda-lbvc-download-cat"><div id="block-landbook-view-coda-lbvc-download-cat-wrapper"><p>Download this Dataset data..</p></div></div>';

    //   $elements[$key][$delta . 'cat_download'] = [
    //     '#type' => 'item',
    //     '#markup' => $download_coda,
    //         '#attached' => [
    //           'library' => ['landbook_view_coda/lbvis.dl'],
    //           'library' => ['view_coda_extra/my_library'],
    //         //   'drupalSettings' => [
    //         //     'my_library' => [
    //         //       // 'indicator_download_id' => $indicator_sparql_field_id_for_map,
    //         //     ],
    //         //   'library' => ['view_coda_extra/lp-related-content-edit'],
    //         // ],            
    //       ]
    //      ];


?>