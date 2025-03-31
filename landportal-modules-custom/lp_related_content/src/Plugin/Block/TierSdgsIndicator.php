<?php

namespace Drupal\lp_related_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;

/**
 * Provides a 'Custom Block' block.
 *
 * @Block(
 *   id = "Tiersdgsindicator_block",
 *   admin_label = @Translation("TierSdgsIndicator"),
 *   category = @Translation("Custom")
 * )
 */
class TierSdgsIndicator extends BlockBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get nodes of the "sdgi" content type.
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'sdgi']);

    // Initialize arrays to store parent terms and their associated child terms.
    $parentGroups = [];
    $parentTitles = [];

    foreach ($nodes as $node) {
        $field_id_child = '';
        $top_level_parent_title = '';
        $nids = $node->get('nid')->getValue();
        $nid = $nids[0]['value'];
        $url = Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString();

        // Get the field value from each node.
        $title = $node->get('title')->value;
        $field_sdg = $node->get('field_sdg')->getValue();
        $field_status = $node->get('field_sdgs_tier')->getValue();

        $tid_status = '';
        if(isset($field_status[0]['target_id'])){
            $tid_status = $field_status[0]['target_id'];            
        }

        $term_status = Term::loadMultiple([$tid_status]);
        $tier_name = '';
        foreach($term_status as $single_term_status){
            $tier_name = $single_term_status->get('name')->value;
        }

        // Extract the target_id from the field value.
        $field_sdg_tid = $field_sdg[0]['target_id'];
        $child_term = Term::loadMultiple([$field_sdg_tid]);

        foreach ($child_term as $child_term_single) {
            // Get the top-level parent term.
            $top_level_parent = $this->getTopLevelParent($child_term_single);

            // Get the parent term title.
            $top_level_parent_title = $top_level_parent->label();

            // Group terms with the same parent.
            $parentGroups[$top_level_parent_title][] = [
                'title' => $title,
                'field_sdg_tid' => $field_sdg_tid,
                'field_id_child' => $child_term_single->get('field_id')->value,
                'parent_image' => $this->getParentImage($top_level_parent),
                'tier_names' => $tier_name,
                'node_path' => $url,
            ];

            // Store parent titles to display them only once.
            if (!in_array($top_level_parent_title, $parentTitles)) {
                $parentTitles[] = $top_level_parent_title;
            }
        }
    }

    // Initialize a wrapper div.
    $build = [
      '#markup' => '<div class="sdg-wrapper">',
    ];

    foreach ($parentGroups as $parentTitle => $childTitles) {
      // Add the grouped and formatted items to the build array.
      $build['#markup'] .= '<div class="parent-wrapper">';
      $build['#markup'] .= '<div class="icon"><img src="' . $childTitles[0]['parent_image'] . '" alt="' . $parentTitle . '" width="100" height="100"></div>';

        foreach ($childTitles as $childInfo) {
            $build['#markup'] .= '<div class="child-wrapper">';
            $build['#markup'] .= '<span><a href="'.$childInfo['node_path'].'">'.$childInfo['field_id_child'].'</a></span>';

            // Add the tier divs with 'active' class if tier_names has content.
            $build['#markup'] .= '<div class="tier-3' . (!empty($childInfo['tier_names']) && $childInfo['tier_names'] == 'Tier 3' ? ' active' : '') . '">' . ($childInfo['tier_names'] == 'Tier 3' ? $childInfo['tier_names'] : '&nbsp;') . '</div>';
            $build['#markup'] .= '<div class="tier-2' . (!empty($childInfo['tier_names']) && $childInfo['tier_names'] == 'Tier 2' ? ' active' : '') . '">' . ($childInfo['tier_names'] == 'Tier 2' ? $childInfo['tier_names'] : '&nbsp;') . '</div>';
            $build['#markup'] .= '<div class="tier-1' . (!empty($childInfo['tier_names']) && $childInfo['tier_names'] == 'Tier 1' ? ' active' : '') . '">' . ($childInfo['tier_names'] == 'Tier 1' ? $childInfo['tier_names'] : '&nbsp;') . '</div>';

            $build['#markup'] .= '</div>';
        }


      $build['#markup'] .= '</div>';
    }

    // Close the wrapper div.
    $build['#markup'] .= '</div>';

    return $build;
  }

  /**
   * Gets the top-level parent term.
   */
  protected function getTopLevelParent($term) {
    // Initialize an array to store all parent terms.
    $parents = [];

    // Loop to find all parent terms.
    while ($parent_tid = $term->get('parent')->target_id) {
        // Load the parent term.
        $parent_term = Term::load($parent_tid);

        // Add the parent term to the array of parents.
        $parents[] = $parent_term;

        // Set the parent as the current term for the next iteration.
        $term = $parent_term;
    }

    // The last item in the array is the top-level parent.
    return end($parents);
  }

  /**
   * Gets the image URL for the parent term.
   */
  protected function getParentImage($term) {
    // Replace 'field_image' with the actual field name for the image.
    $image_field = $term->get('field_image')->entity;
    if ($image_field) {
        $image_url = $image_field->getFileUri();
        return file_create_url($image_url);
    }

    // Default image if the field is empty.
    return 'path/to/default/image.jpg';
  }
}
