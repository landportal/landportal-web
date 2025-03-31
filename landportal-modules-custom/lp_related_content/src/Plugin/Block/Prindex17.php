<?php

namespace Drupal\lp_related_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Custom Block' block.
 *
 * @Block(
 *   id = "prindex17_block",
 *   admin_label = @Translation("Prindex17"),
 *   category = @Translation("Custom")
 * )
 */
class Prindex17 extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $current_node_id = NULL;
    $node = \Drupal::routeMatch()->getParameter('node');
    
    if ($node instanceof Node) {
      $current_node_id = $node->id();
      // Check if the current node ID matches the target node ID.
      if(!empty($current_node_id)){
        if ($current_node_id == 62780) {

          // Load the block content entity with ID 30.
          $block = \Drupal\block_content\Entity\BlockContent::load(74);

          // Check if the block content entity exists.
          if ($block) {
            // Render the full block content entity.
            $rendered_block = \Drupal::entityTypeManager()
              ->getViewBuilder('block_content')
              ->view($block);

            // Return the rendered block.
            return $rendered_block;
          }
        }
      }
    }
    // Return an empty array if the block content entity doesn't exist.
    return [];
  }

}
