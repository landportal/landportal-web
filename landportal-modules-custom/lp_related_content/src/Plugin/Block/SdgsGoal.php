<?php

namespace Drupal\lp_related_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

/**
 * Provides a 'Custom Block' block.
 *
 * @Block(
 *   id = "sdgsgoal_block",
 *   admin_label = @Translation("SdgsGoal"),
 *   category = @Translation("Custom")
 * )
 */
class SdgsGoal extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    $tree = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree('sdgs');
    $output = '';
    $currentParent = '';
    foreach ($tree as $term) {
      if ($term->depth == 0) {
        // Parent term
        if ($output != '') {
          // $output .= '</p>';
          $output .= '</div>';
        }
        
        $class_name = $term->name;
        $class_names = strtolower($class_name);
        $class_name_hypn = str_replace(' ', '-', $class_names);
        //\Drupal::logger('SDGi')->notice(serialize($class_name_hypn));
        $output .= '<div class="sdgs-goal-wrapper '.$class_name_hypn.'">';
        $parent_sdg_type = '';
        $parent_sdg_id = '';
        $icon = '';
        $parent_term_id = $term->tid;
        $parent_tid = \Drupal\taxonomy\Entity\Term::loadMultiple([$parent_term_id]);
        foreach ($parent_tid as $parent_single_tid) {
          $parent_sdg_type = $parent_single_tid->get('field_sdgs_type')->value;
          $parent_sdg_id = $parent_single_tid->get('field_id')->value;

          $image = $parent_single_tid->get('field_image')->entity;
          $image_url = '';

          if ($image) {
            $image_url = file_create_url($image->getFileUri());
            $icon = '<img src="' . $image_url . '" alt="' . $image->alt . '" title="' . $image->title . '" style="width:50px;height:50px;" />';
          }
          //\Drupal::logger('SDGi')->notice(serialize($term->name));
        }
        $output .= '<h4>' . $icon . '<span>' . $parent_sdg_type . ' ' . $parent_sdg_id . ': </span>' . '<span>' . $term->name . '</span></h4>';
      } else {
        // Child or grandchild term
        $output .= '<div><p>';
        // $output .= 'Read More first-child = ';
        if ($term->depth > 1) {
          $output .= str_repeat('', $term->depth - 1);
        }
        // $output .= $term->name;
        // $output .= $term->tid;

        // Load content of type 'sdgi'
        $nodes = \Drupal::entityTypeManager()
          ->getStorage('node')
          ->loadByProperties(['type' => 'sdgi']);

        // Check if the current taxonomy term's tid matches the target_id in any sdgi node
        $matched = false;
        $matched_node_url = '';
        foreach ($nodes as $node) {
          if ($node->field_sdg->target_id == $term->tid) {
            $matched = true;
            $matched_node_url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()])->toString();
            break;
          }
        }
        if ($term->depth > 0) {
          //$output .= ' (' . $term->depth . ')';
          $child_term_id = $term->tid;
          $child_tid = \Drupal\taxonomy\Entity\Term::loadMultiple([$child_term_id]);
          foreach ($child_tid as $child_single_tid) {
            $sdg_type = $child_single_tid->get('field_sdgs_type')->value;
            $sdg_id = $child_single_tid->get('field_id')->value;
            $output .= '<span>' . $sdg_type . '</span>' . ' ' . '<span>' . $sdg_id . '</span>';
            // \Drupal::logger('SDGi')->notice(serialize($child_single_tid->get('field_sdgs_type')->value));
            // \Drupal::logger('SDGi')->notice(serialize($child_single_tid->get('field_id')->value));
          }
        $output .= '<p>' . $term->description__value . '</p>';
        if ($matched) {
          $output .= '<p class="sdgi-readmore"><a href="' . $matched_node_url . '">Read More</a></p>';
        }
        }
        if ($term->parent) {
          $output .= ' - Parent: ' . $currentParent;
        }
        $output .= '</p></div>';
      }
    }
    if ($output != '') {
      $output .= '</div>';
    }
    return [
      '#markup' => $this->t($output),
    ];
  }
}
