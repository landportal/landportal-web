<?php

namespace Drupal\programme_impact\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Programme impact' block.
 *
 * @Block(
 *   id = "region_programme_impact_block",
 *   admin_label = @Translation("Region Programme impact block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProgrammeImpactBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_blogs';
  /**
   * {@inheritdoc}
   */
  public function build() {
    // Logs a notice
    //\Drupal::logger('my_module')->notice("ProjectA");
    \Drupal::logger('my_module')->notice("ProjectN " . $this->taxonomyTerm);
    $loaded_term = Term::load($this->taxonomyTerm);
    \Drupal::logger('my_module')->notice("loaded_term " . serialize($loaded_term));
    if(isset($loaded_term)){
      $term_related_project_impact = $loaded_term->get('field_related_impact')->getValue();
      \Drupal::logger('my_module')->notice("term_related_project_impact " . serialize($term_related_project_impact));     
    }
    $term_related_project_impact_value_arr = array();
    foreach ($term_related_project_impact as $key => $value) {
      $term_related_project_impact_value_arr[] = "item_id:" . $value['swid'];
    }
    \Drupal::logger('my_module')->notice("term_related_project_impact_value_arr " . serialize($term_related_project_impact_value_arr));
    //$node_related_project_impact_value_str = "";
    $str_item_ids = "";
    if(count($term_related_project_impact_value_arr) > 0){
      $str_item_ids = implode(" OR ", $term_related_project_impact_value_arr);
    }
    else{
      $str_item_ids = "item_id:1";
    }
    \Drupal::logger('my_module')->notice("str_item_ids_content " . serialize($str_item_ids));    
    
    //$str_item_ids = "item_id:100575 OR item_id:100215 OR item_id:100045";
    $items = $this->connectionService->getContentResultsRelatedContent($this->indexName,
      [
        'is_status' => 1,
        //'im_field_related_themes' => "7344",
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      100,
      $str_item_ids
    );
    if (count($items)) {
      $build = [
        '#theme' => 'programme_impact_block',
        '#items' => $items,
        '#more_url' => '#',
        '#more_label' => "Browse all",
        '#region_name' => "hi",
      ];
    }
    else{
      $build = array();
    }
    return $build;
  }

}
