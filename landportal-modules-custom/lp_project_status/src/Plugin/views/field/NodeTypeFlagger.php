<?php

/**
 * @file
 * Definition of Drupal\lp_project_status\Plugin\views\field\NodeTypeFlagger
 */

namespace Drupal\lp_project_status\Plugin\views\field;

use Drupal\node\Entity\Node;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\taxonomy\Entity\Term;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("node_type_flagger")
 */
class NodeTypeFlagger extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['project_detail'] = array('default' => 'status');

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $types = NodeType::loadMultiple();
   // \Drupal::logger('shahitesting')->notice("worka " . serialize($types));

    //$types
    $options = [];
    $options = array('status' => "Project Status" , 'value' => "Project Value" , 'duration' => "Project Duration");
    foreach ($types as $key => $type) {
      \Drupal::logger('shahitesting')->notice("workb " . serialize($key));
      //$options[$key] = $type->label();
      \Drupal::logger('shahitesting')->notice("workc " . serialize($type->label()));



    }
    $form['project_detail'] = array(
      '#title' => $this->t('Which node type should be flagged?'),
      '#type' => 'select',
      '#default_value' => $this->options['project_detail'],
      '#options' => $options,
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {
    // $rest = 'field_project_duration_1';
    $loaded_node = $this->getEntity($values);


//////////////////////////////// Variables Declares\\\\\\\\\\\\\\\\\\\\\\

    $project_start_month_year = "";
    $project_end_month_year = "";
    $project_duration = "";
    $project_duration_start_value = ""; 
    $project_duration_end_value = ""; 
    $project_price_value = "";
    $project_price_currency = "";
    $project_status = "";
    $project_price = "";

    //// here is gettype() function means that its get content type \\\\\\\\\\\\\\\\\\\\\ 

    if ($loaded_node->getType() == 'project') { // loaded_node store the content type 
      if($loaded_node == ""){ // condition that if loaded_node is empty \\\\\\\\\\\\\\\\\\
      }
      else {
        /// node_field_value_decimal stored the field_value_decimal value\\\\\\\\\\\\\\\\
        $node_field_value_decimal = $loaded_node->get('field_value_decimal')->getValue();
        if(isset($node_field_value_decimal)){    // condition if node_field_value_decimal has value
          $node_field_value_decimal_value = $node_field_value_decimal[0]['value'];
          if(isset($node_field_value_decimal_value)){ // condition if node_field_value_decimal_value has value
            $project_price_value = $node_field_value_decimal_value;
          }
        }
      ////// node_field_currency stored the field_currency value\\\\\\\\\\\\\\\\\\\\        
        $node_field_currency = $loaded_node->get('field_currency')->getValue();
        if(isset($node_field_currency)){ 
          $node_field_currency_value = $node_field_currency[0]['target_id'];
          if(isset($node_field_currency_value)){
         /// condition and taxonomy term load and get value of this field_currency_symbol \\\\\\\\\   
            if(isset(Term::load($node_field_currency_value)->get('field_currency_symbol')->value)){
              $project_price_currency = Term::load($node_field_currency_value)->get('field_currency_symbol')->value;
            }
          }
        }   
        /// node_field_project_duration get value and stored value of this field_project_duration \\\\\\

        $node_field_project_duration = $loaded_node->get('field_project_duration')->getValue();
        if(isset($node_field_project_duration)){
       //// node_field_project_duration_start_value stored node_field_project_duration 0 index value \\\\\\   
          $node_field_project_duration_start_value = $node_field_project_duration[0]['value'];
          $node_field_project_duration_end_value = $node_field_project_duration[0]['end_value'];
/////isset condition node_field_project_duration_start_value and node_field_project_duration_end_value////
            ////// has correct value than concatinate the following variables \\\\\\\\\\\\\\\\\\
          if(isset($node_field_project_duration_start_value) && isset($node_field_project_duration_end_value)){
            $project_duration_start_value = $node_field_project_duration_start_value;
            $project_start_month_year = date("m/y", strtotime($node_field_project_duration_start_value));
            $project_duration_end_value = $node_field_project_duration_end_value;
            $project_end_month_year = date("m/y", strtotime($node_field_project_duration_end_value));
          }
        }                       
      }
    }   
    ///Here is condition to check if project_duration_start_value and project_duration_end_value\\\\\\\
    //////// is  not empty\\\\\\\\\\\\\\\\
    if(($project_duration_start_value != "") && ($project_duration_end_value != "")){
      $startdate = $project_duration_start_value; // Value of start-date
      $enddate = $project_duration_end_value; // Value of end-date

////////////////////Project Status code///////////////////////////


       if(strtotime($startdate) > date('U')) { // condition startdate is greater than date
           $project_status = "Planned";
       } else {
         if(strtotime($enddate) < date('U')) { // condition startdate is less than date
           # date is in the past
          $project_status = "Completed";
        } else {
           $project_status = "Active";
        }
      }      
    }

   //// if project_price_value is not empty than concatinate the project currency and project value\\\\\\\\\
    if($project_price_value != ""){
      $project_price = $project_price_currency . $project_price_value;
    }
  /// if project_start_month_year and project_end_month_year is not empty than concatinate \\\\\\  
    if(($project_start_month_year != "") && ($project_end_month_year != "")){
      $project_duration = $project_start_month_year . " - " . $project_end_month_year;
    }

/////////////////////////////////////Result Return Here \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    //$testing = $node->get('field_project_duration_1')->getValue();
    if ('status' == $this->options['project_detail']) {
      return $this->t($project_status);
    }  
    else if ('value' == $this->options['project_detail']) {
      return $this->t($project_price);
    }
    else if ('duration' == $this->options['project_detail']) {
      return $this->t($project_duration);
    }         
    else {
      return $this->t('');
    }
  }
}
