<?php 

    $indicator_term_id_arr = array();
    // Check indicator field 
    if ($indicator != null) {
      preg_match_all('/(?<=\()\d+(?=\))/',$indicator, $matches);
      $indicator_term_id_arr = $matches[0];
    }

    $term = \Drupal\taxonomy\Entity\Term::loadMultiple($indicator_term_id_arr);

    // foreach get term values from field_id
    if (empty($indicator_sparql_field_id_for_map)) { //if empty array sparql_field          
      foreach ($term as $key => $terms) { // foreach start for sparql_field array
        $indicator_sparql_field_id_for_map[] = $terms->get('field_id')->getString();
      }// end foreach for sparql_field array
    }//end of check if empty array sparql_field

    // $var_key = $delta . '1';
    if ($item->textarea) {
      $elements[$delta]['textarea'] = [
      '#type' => 'item',
      ];
    }

    $elements[$delta]['indicator_title'] = [
    '#type' => 'item',
    '#markup' => '<h6>' . $indicator_title . '</h6>'.'<p>'.$indicator_description .'</p>',
    ];

    if(isset($arr_text->loadIndicators)){
      if($arr_text->loadIndicators == true){
        if ($item->indicator != "") {
          $elements[$delta]['coda'] = [
            '#type' => 'select',
            '#id' => '-indicator',
            '#name' => 'indicator',
            '#prefix' =>'<div class="indicator-name">',
            '#options' => array(
              '' => t('Select an Indicator'),
            ),
            '#suffix' => '</div>',
          ]; 
        }
      }
    }

    if(isset($arr_text->loadYears)){
      if($arr_text->loadYears == true){
        if ($item->indicator != "") {
          $elements[$delta]['year'] = [
            '#type' => 'select',
            '#id' => '-year',
            '#name' => 'year',          
            '#prefix' =>'<div class="indicator-year">',
            '#options' => array(
              '' => t('Select a year'),
            ),
            '#suffix' => '</div>',
            '#attached' => [
              'library' => ['view_coda_extra/indicators'],
            ],
          ];        
        }
      }
    }
  
    if ($item->indicator) {
      $hide_chart_title = false;
      $selected_year = "";        
      $legend_status = false;
      $nav_status = false;
      if(isset($arr_text->hideTitle)){        
        $hide_chart_title = true;
      }  
      if(isset($arr_text->year)){        
        $selected_year = $arr_text->year;
      }              
      if(isset($arr_text->map->legend)){        
        $legend_status = true;
      }
      if(isset($arr_text->map->nav)){        
        $nav_status = true;
      }        

      $elements[$delta]['indicator'] = [
        '#type' => 'item',
        '#prefix' => '<div id="container"></div>',
        '#attached' => [
          'library' => [
              'view_coda_extra/my_library',
              'view_coda_extra/lp-related-content-edit',
              'view_coda_extra/indicators',
            ],
          'drupalSettings' => [
            'my_library' => [  
              'indicator_map_id' => $indicator_sparql_field_id_for_map,
              // 'dataset_field_id' => $dataset_field_id,
              'hide_chart_title' => $hide_chart_title,
              'selected_year' => $selected_year,
              'legend_status' => $legend_status,
              'nav_status' => $nav_status,
              // 'check_vid' => $check_vid,
              'visualization_type' => $visualization_type,
              'visualization_type_arr' => $visualization_type_arr,
            ],
          ],
          'library' => ['view_coda_extra/lp-related-content-edit'],
        ],
      ];
      
    }


?>