<?php

namespace Drupal\landportal_country_narrative\Plugin\Block;

use Drupal\Core\Url;
use Drupal\landportal_common\Plugin\Block\LandportalCountryNodeBlockBase;

/**
 * Provides a 'Country Hero' block.
 *
 * @Block(
 *   id = "country_hero",
 *   admin_label = @Translation("Country hero"),
 *   category = @Translation("Country Landing Page")
 * )
 */
class CountryHero extends LandportalCountryNodeBlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    if ($node = $this->getNode()) {
      /** @var \Drupal\Core\Entity\EntityViewBuilderInterface $view_builder */
      $view_builder = $this->entityTypeManager->getViewBuilder('node');
      $build['narrative'] = $view_builder->view($node, 'hero');
    }
    else {
      // Build a minimal hero block when there is no narrative node.
      if ($name = $this->getCountryName()) {
        // Get the map file name.
        $image_path = $this->getCountryMapFile();
        $image_src = Url::fromUri(\Drupal::service('file_url_generator')->generateAbsoluteString($image_path))->toString();
        $image_alt = $this->t('Map of @country', ['@country' => $name]);
        $build['hero'] = [
          'country' => [
            '#markup' => $this->getCountryName(),
            '#prefix' => '<h1>',
            '#suffix' => '</h1>',
          ],
          'map' => [
            '#markup' => "<img src='$image_src' alt='$image_alt'/>",
          ],
        ];
      }
    }

    return $build;
  }

}
