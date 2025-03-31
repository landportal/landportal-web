<?php

namespace Drupal\project_provider\Plugin\Block;
use Drupal\node\Entity\Node;
use Drupal\landportal_remote_block\Plugin\RemoteBlockBase;

/**
 * Provides a 'Project provider' block.
 *
 * @Block(
 *   id = "region_project_provider_block",
 *   admin_label = @Translation("Region project provider block"),
 *   category = @Translation("Landportal Remote")
 * )
 */
class ProjectProviderBlock extends RemoteBlockBase {

  /**
   * {@inheritdoc}
   */
  protected $indexName = 'remote_organizations';
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
      $node_related_project_provider = $loaded_node->get('field_related_provider')->getValue();
      \Drupal::logger('my_module')->notice("node_related_project_provider " . serialize($node_related_project_provider));
    }
    $node_related_project_provider_value_arr = array();
    foreach ($node_related_project_provider as $key => $value) {
      $node_related_project_provider_value_arr[] = "item_id:" . $value['swid'];
    }
    \Drupal::logger('my_module')->notice("node_related_project_provider_value_arr " . serialize($node_related_project_provider_value_arr));
    //$node_related_project_provider_value_str = "";
    $str_item_ids = "";
    if(count($node_related_project_provider_value_arr) > 0){
      $str_item_ids = implode(" OR ", $node_related_project_provider_value_arr);
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
      15,
      $str_item_ids
    );
    if (count($items)) {
      $build = [
        '#theme' => 'project_provider_block',
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
