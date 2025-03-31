<?php

namespace Drupal\lp_swiper;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Provides an interface for a Views swiper widget type.
 */
interface LpSwiperWidgetTypeInterface extends PluginInspectionInterface, ConfigurableInterface, PluginFormInterface, DependentPluginInterface {

  /**
   * Check if the widget type is compatible with the selected swiper.
   *
   * @return bool
   *   TRUE if the widget type is compatible with the swiper.
   */
  public function checkCompatiblity($swiper);

}
