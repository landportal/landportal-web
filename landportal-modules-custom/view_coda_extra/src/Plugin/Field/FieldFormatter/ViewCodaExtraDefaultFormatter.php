<?php
/**
 * @file
 * Contains \Drupal\ViewCodaExtra\Plugin\Field\FieldType\ViewCodaExtraDefaultFormatter.
 */

namespace Drupal\view_coda_extra\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;
use Drupal;

/**
 * Plugin implementation of the 'ContentSelectionDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ViewCodaExtraDefaultFormatter",
 *   label = @Translation("View Coda Extra"),
 *   field_types = {
 *     "ViewCodaExtra"
 *   }
 * )
 */
class ViewCodaExtraDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   * 
   * Inside this method we can customize how the field is displayed inside 
   * pages.
   */

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
      $visualization_type_arr = [];
      $indicator_sparql_field_id_for_table = [];
      $indicator_sparql_field_id_for_map = []; 
      // $dataset_portfolio_field_id = [];      
      $indicator_id_for_download = [];
      $check_vid = '';
      $field_id_dataset_portfolio_indicator = '';
      $field_id_dataset_portfolio_id = '';       
            
    foreach ($items as $delta =>$item) { // items foreach start
      if (!empty($item->indicator)) {  // check if items exist
        $indicator = $item->indicator;
        $visualization_type = $item->visualization;
       \Drupal::logger('1_indicator')->notice(serialize($visualization_type));
        // \Drupal::logger('$delta')->warning(serialize($item));


        //make array of visualization
        if (empty($visualization_type_arr)) { //check if for empty array
          foreach ($items as $i_tem) { // foreach start for visualization array
            if (!empty($i_tem->indicator)) {  // only if item exist selected
              $visualization_type_arr[] = $i_tem->visualization;
            }// only if item exist selected
          } // end foreach for visualization array
        }//end of check if for empty array

        $parentEntity = $items->getParent()->getEntity();
        $entity_name = $parentEntity->getEntityTypeId();
        if($entity_name == "node"){
          $dataset_field_id = $parentEntity->get('field_indicators')->indicator;
          preg_match('/\b(\d+)\b/', $dataset_field_id, $matches);
          if (!empty($matches[1])) {
            $dataset_field_tid = $matches[1];
            $fff = [$dataset_field_tid];
            $term_indicator_dataset_portfolio = \Drupal\taxonomy\Entity\Term::loadMultiple($fff);

            foreach($term_indicator_dataset_portfolio as $single_term_catalog){
              $field_id_dataset_portfolio_indicator = $single_term_catalog->get('field_id')->value;
            }
          }
        }
        if ($entity_name != "node") {
           $dataset_field_id = $parentEntity->get('field_id')->value;
           $field_id_get_indicator = $parentEntity->get('field_id')->value;
        }
        
        $check_vid = $parentEntity->vid->target_id;         
        //\Drupal::logger('check_vid')->warning(serialize($check_vid));

        $field_indicator = $parentEntity->get('field_indicators')->indicator;
        // $field_indicator_visualization = $items->visualization;

        if($field_indicator != null){//if start
          $otherFieldValue = $parentEntity->get('field_indicators')->first()->getValue();
          $text = $otherFieldValue['textarea'];
          $indicator_title = $otherFieldValue['indicator_title'];
          $indicator_description = $otherFieldValue['indicator_description'];
          $arr_text = json_decode($text);
        }//end if

        $indicator_title = $item->indicator_title;
        $indicator_description = $item->indicator_description;

        //Condition to Check visualization_type of indicator
        if( $visualization_type == 'map'){ //check condition for map

          include 'visualization_type/map.php';

        } // end if of MAP  


        //check  condition  for  htmlTable
        if ($visualization_type == 'htmlTable') {

          include 'visualization_type/htmlTable.php';

        } // end if of htmlTable


        //check  condition  for  htmlTable
        if ($visualization_type == 'ranking') {

          include 'visualization_type/ranking.php';

        } // end if of htmlTable



      } // end of if items exist

      $parentEntity1 = $items->getParent()->getEntity();
      $entity_name1 = $parentEntity1->getEntityTypeId();
      $get_bundle = $parentEntity1->bundle();
      //\Drupal::logger('check_vid_content_type')->notice(serialize($get_bundle));
      if($entity_name1 == "node"){
        if($get_bundle == 'dataset'){
          $catalog_field_dataset_portfolio = $parentEntity1->get('field_catalog')->target_id;
          // \Drupal::logger('check_vid_inner')->notice(serialize($catalog_field_dataset_portfolio));
          $term_catalog_field_dataset_portfolio = \Drupal\taxonomy\Entity\Term::loadMultiple([$catalog_field_dataset_portfolio]);
          foreach($term_catalog_field_dataset_portfolio as $single_term_catalog){
            $field_id_dataset_portfolio_id = $single_term_catalog->get('field_id')->value;
          }
        }
      }
        //\Drupal::logger('check_vid_fida')->notice(serialize($field_id_dataset_portfolio_id));
    } //items foreach end 

    // add Download Block 
    include 'visualization_type/downloadBlock.php';


    return $elements;
  } // function
  
} // class






