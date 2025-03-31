<?php

namespace Drupal\project_resources\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Project resources' block.
 *
 * @Block(
 *   id = "region_project_resources_block",
 *   admin_label = @Translation("Region project resources block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProjectResourcesBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_libraries';
  /**
   * {@inheritdoc}
   */
  public function build() {
    // Logs a notice
    //\Drupal::logger('my_module')->notice("ProjectA");
    \Drupal::logger('my_module')->notice("ProjectN " . $this->contentNodeId);
    $loaded_node = Node::load($this->contentNodeId);
    \Drupal::logger('my_module')->notice("loaded_node " . serialize($loaded_node));
    if(isset($loaded_node)){
      $node_related_project_resources = $loaded_node->get('field_related_resources')->getValue();
      \Drupal::logger('my_module')->notice("node_related_project_resources " . serialize($node_related_project_resources));
      //$node_field_iqbal_testing_star = $loaded_node->get('field_iqbal_testing_star')->getValue();
      //\Drupal::logger('my_module')->notice("node_field_iqbal_testing_star " . serialize($node_field_iqbal_testing_star));      
    }
    $swid_weight_arr = array();
    $node_related_project_resources_value_arr = array();
    foreach ($node_related_project_resources as $key => $value) {
      $swid_weight_arr[] = $value['swid'];
      $node_related_project_resources_value_arr[] = "item_id:" . $value['swid'];
    }
    \Drupal::logger('my_module')->notice("node_related_project_resources_value_arr " . serialize($swid_weight_arr));
    //$node_related_project_resources_value_str = "";
    $str_item_ids = "";
    if(count($node_related_project_resources_value_arr) > 0){
      $str_item_ids = implode(" OR ", $node_related_project_resources_value_arr);
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
      ],
      [],
      ['ds_field_pubdate' => 'desc'],
      100,
      $str_item_ids
    );
    if (count($items)) {
      $build = [
        '#theme' => 'project_resources_block',
        '#items' => $items,
        '#more_url' => '#',
        '#more_label' => "Browse all",
        '#region_name' => $swid_weight_arr,
      ];
    }
    else{
      $build = array();
    }

    return $build;
  }

}
