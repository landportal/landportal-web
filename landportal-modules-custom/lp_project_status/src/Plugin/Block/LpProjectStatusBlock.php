<?php

namespace Drupal\lp_project_status\Plugin\Block;

use Drupal\node\Entity\Node;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'lp_project_status' Block.
 *
 * @Block(
 *   id = "lp_project_status",
 *   admin_label = @Translation("Lp Project Status"),
 *   category = @Translation("Landportal project"),
 * )
 */
class LpProjectStatusBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_name = \Drupal::routeMatch()->getRouteName();
    $node_content = \Drupal::routeMatch()->getParameter('node');
    $node_content_type = "";
    $contentNodeId = "";
    $loaded_node = "";
    $CC = "C"; 
    if(isset($node_content)){
      $contentNodeId = $node_content->id();
      if(is_numeric($node_content->id())){
        $CC = "kkkk " . $contentNodeId; 
        $node_content_type = $node_content->bundle();
        $loaded_node = Node::load($contentNodeId);
      }
    }  
     
    $project_start_month_year = "";
    $project_end_month_year = "";
    $project_duration = "";
    $project_duration_start_value = ""; 
    $project_duration_end_value = ""; 
    $project_price_value = "";
    $project_price_currency = "";
    if ($node_content_type == 'project') {
      if($loaded_node == ""){
        //$CC = "abcdef"; 
      }
      else {
        $node_field_value_decimal = $loaded_node->get('field_value_decimal')->getValue();
        if(isset($node_field_value_decimal)){
          $node_field_value_decimal_value = $node_field_value_decimal[0]['value'];
          if(isset($node_field_value_decimal_value)){
            $project_price_value = $node_field_value_decimal_value;
          }
        }        
        $node_field_currency = $loaded_node->get('field_currency')->getValue();
        if(isset($node_field_currency)){
          //$project_price_value = "KLJ " . serialize($node_field_currency);
          $node_field_currency_value = $node_field_currency[0]['target_id'];
          if(isset($node_field_currency_value)){
            if(isset(Term::load($node_field_currency_value)->get('field_currency_symbol')->value)){
              $project_price_currency = Term::load($node_field_currency_value)->get('field_currency_symbol')->value;
            }
          }
        }   
        $node_field_project_duration = $loaded_node->get('field_project_duration')->getValue();
        if(isset($node_field_project_duration)){
          $node_field_project_duration_start_value = $node_field_project_duration[0]['value'];
          $node_field_project_duration_end_value = $node_field_project_duration[0]['end_value'];
          if(isset($node_field_project_duration_start_value) && isset($node_field_project_duration_end_value)){
            //$project_price_value = $project_price_value . serialize($node_field_project_duration_start_value) . serialize($node_field_project_duration_end_value);
            $project_duration_start_value = $node_field_project_duration_start_value;
            $project_start_month_year = date("m/y", strtotime($node_field_project_duration_start_value));
            $project_duration_end_value = $node_field_project_duration_end_value;
            $project_end_month_year = date("m/y", strtotime($node_field_project_duration_end_value));
            //if(isset(Term::load($node_field_currency_value)->get('field_currency_symbol')->value)){
             // $project_price_currency = Term::load($node_field_currency_value)->get('field_currency_symbol')->value;
            //}
          }
        }                       
      }
    }
    $build['#theme'] = 'lp_project_status_block';    
    if(($project_duration_start_value != "") && ($project_duration_end_value != "")){
      $startdate = $project_duration_start_value; // Value of start-date
      $enddate = $project_duration_end_value; // Value of end-date

      $project_status = "";
       if(strtotime($startdate) > date('U')) {
           $project_status = t("Planned");
       } else {
         if(strtotime($enddate) < date('U')) {
           # date is in the past
          $project_status = t("Completed");
        } else {
           $project_status = t("Active");
        }
      }      
      $build['#project_status'] = $project_status;
    }
    if($project_price_value != ""){
      $build['#project_price'] = $project_price_currency . $project_price_value;
    }
    if(($project_start_month_year != "") && ($project_end_month_year != "")){
      $build['#project_duration'] = $project_start_month_year . " - " . $project_end_month_year;
    }


    return $build;
  }

}
