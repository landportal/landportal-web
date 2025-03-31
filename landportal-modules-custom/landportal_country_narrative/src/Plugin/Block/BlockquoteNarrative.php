<?php

namespace Drupal\landportal_country_narrative\Plugin\Block;

use Drupal\landportal_common\Plugin\Block\LandportalCountryNodeBlockBase;

/**
 * Provides a 'Narrative Blockquote' block.
 *
 * @Block(
 *   id = "country_narrative_blockquote",
 *   admin_label = @Translation("Country narrative blockquote"),
 *   category = @Translation("Country Narrative")
 * )
 */
class BlockquoteNarrative extends LandportalCountryNodeBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    if ($node = $this->getNode()) {
      /** @var \Drupal\Core\Entity\EntityViewBuilderInterface $view_builder */
      $view_builder = $this->entityTypeManager->getViewBuilder('node');
      $build['blockquote'] = $view_builder->view($node, 'blockquote');
    }

    return $build;
  }

}
