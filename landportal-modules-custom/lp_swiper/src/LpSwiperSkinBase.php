<?php

namespace Drupal\lp_swiper;

use Drupal\Component\Plugin\PluginBase;

/**
 * Provides basic functionality for Views swiper skins.
 */
abstract class LpSwiperSkinBase extends PluginBase implements LpSwiperSkinInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getClass() {
    return $this->pluginDefinition['id'];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries() {
    return $this->pluginDefinition['libraries'];
  }

}
