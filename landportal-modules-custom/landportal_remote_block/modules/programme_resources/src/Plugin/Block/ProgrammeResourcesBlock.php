<?php

namespace Drupal\programme_resources\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Programme resources' block.
 *
 * @Block(
 *   id = "region_programme_resources_block",
 *   admin_label = @Translation("Region programme resources block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProgrammeResourcesBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_libraries';
  /**
   * {@inheritdoc}
   */
  public function build() {
    $loaded_term = Term::load($this->taxonomyTerm);
    //\Drupal::logger('my_module')->notice("loaded_node " . serialize($loaded_term));
    if(isset($loaded_term)){
      $term_related_project_resources = $loaded_term->get('field_related_resources')->getValue();
      //\Drupal::logger('my_module')->notice("term_related_project_resources " . serialize($term_related_project_resources));     
    }
    $term_related_project_resources_value_arr = array();
    foreach ($term_related_project_resources as $key => $value) {
      $term_related_project_resources_value_arr[] = "item_id:" . $value['swid'];
    }
    \Drupal::logger('my_module')->notice("term_related_project_resources_value_arr " . serialize($term_related_project_resources_value_arr));
    //$node_related_project_resources_value_str = "";
    $str_item_ids = "";
    if(count($term_related_project_resources_value_arr) > 0){
      $str_item_ids = implode(" OR ", $term_related_project_resources_value_arr);
    }
    else{
      $str_item_ids = "item_id:1";
    }
    \Drupal::logger('my_module')->notice("str_item_ids " . serialize($str_item_ids));    
    
    //$str_item_ids = "item_id:100575 OR item_id:100215 OR item_id:100045";
    $items = $this->connectionService->getContentResultsOrganizations($this->indexName,
      [
        'is_status' => 1,
        //'im_field_related_themes' => "7344",
        //'im_field_related_themes' => $this->taxonomyTerm,
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      100,
      $str_item_ids
    );
    if (count($items)) {
      $build = [
        '#theme' => 'programme_resources_block',
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
