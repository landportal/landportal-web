<?php

namespace Drupal\lp_swiper;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Provides an interface for a Views swiper widget.
 */
interface LpSwiperWidgetInterface extends PluginInspectionInterface, ConfigurableInterface, PluginFormInterface, DependentPluginInterface {

  /**
   * Check if the widget is compatible with the current view configuration.
   *
   * @return bool
   *   TRUE if the widget is compatible with the view.
   */
  public function checkCompatiblity($view);

}
