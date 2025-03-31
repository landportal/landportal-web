<?php

namespace Drupal\lp_related_content\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * Provides a 'Custom Block' block.
 *
 * @Block(
 *   id = "datasetportfolioselect_block",
 *   admin_label = @Translation("DatasetPortfolioSelect"),
 *   category = @Translation("Custom")
 * )
 */
class DatasetPortfolioSelect extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $current_node_id = NULL;
    $node = \Drupal::routeMatch()->getParameter('node');

    if ($node instanceof Node) {
      $current_node_id = $node->id();

      // Load the node based on the node ID.
      $current_node = Node::load($current_node_id);

      // Access multi-value field 'field_indicators'.
      $indicators = $current_node->get('field_indicators')->getValue();

      // Extract 'indicator' values and explode into individual options.
      $indicatorOptions = [
        '' => 'Choose other indicator from this dataset',
      ];

      foreach ($indicators as $indicator) {
        $indicatorsArray = explode(', ', $indicator['indicator']);
        foreach ($indicatorsArray as $individualIndicator) {
          // Extract the ID from the parentheses and ensure it is numeric.
          preg_match('/\((\d+)\)/', $individualIndicator, $matches);
          $indicatorId = !empty($matches[1]) && is_numeric($matches[1]) ? $matches[1] : '';

          // Create link format if ID is numeric.
          if (is_numeric($indicatorId)) {
            $url = \Drupal\Core\Url::fromRoute('entity.taxonomy_term.canonical', ['taxonomy_term' => $indicatorId])->toString();
            $label = preg_replace('/\(\d+\)$/', '', $individualIndicator); // Remove the ID from the label.
            $indicatorOptions[$url] = $label;
          }
        }
      }

      // Example: Return a select list with indicator titles and links.
      return [
        '#markup' => $this->t('Node ID: @nid'),
        '#attached' => [
          'library' => ['core/drupal.form'],
        ],
        '#type' => 'select',
        '#title' => $this->t('Select Indicator'),
        '#options' => $indicatorOptions,
      ];
    }

    // Return an empty array if the block content entity doesn't exist.
    return [];
  }

}
