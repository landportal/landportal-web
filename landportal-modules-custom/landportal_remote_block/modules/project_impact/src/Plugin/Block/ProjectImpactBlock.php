<?php

namespace Drupal\project_impact\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Project impact' block.
 *
 * @Block(
 *   id = "region_project_impact_block",
 *   admin_label = @Translation("Region project impact block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProjectImpactBlock extends RemoteBlockBase {

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
    \Drupal::logger('my_module')->notice("ProjectN " . $this->contentNodeId);
    $loaded_node = Node::load($this->contentNodeId);
    \Drupal::logger('my_module')->notice("loaded_node " . serialize($loaded_node));
    if(isset($loaded_node)){
      $node_related_project_impact = $loaded_node->get('field_related_impact')->getValue();
      \Drupal::logger('my_module')->notice("node_related_project_impact " . serialize($node_related_project_impact));
      //$node_field_iqbal_testing_star = $loaded_node->get('field_iqbal_testing_star')->getValue();
      //\Drupal::logger('my_module')->notice("node_field_iqbal_testing_star " . serialize($node_field_iqbal_testing_star));      
    }
    $node_related_project_impact_value_arr = array();
    foreach ($node_related_project_impact as $key => $value) {
      $node_related_project_impact_value_arr[] = "item_id:" . $value['swid'];
    }
    \Drupal::logger('my_module')->notice("node_related_project_impact_value_arr " . serialize($node_related_project_impact_value_arr));
    //$node_related_project_impact_value_str = "";
    $str_item_ids = "";
    if(count($node_related_project_impact_value_arr) > 0){
      $str_item_ids = implode(" OR ", $node_related_project_impact_value_arr);
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
        '#theme' => 'project_impact_block',
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
