<?php

namespace Drupal\lp_swiper;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manager for Lp Swiper Widget plugins.
 */
class LpSwiperWidgetPluginManager extends DefaultPluginManager {

  /**
   * Constructs a new LpSwiperWidgetPluginManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/LpSwiperWidget', $namespaces, $module_handler, 'Drupal\lp_swiper\LpSwiperWidgetInterface', 'Drupal\lp_swiper\Annotation\LpSwiperWidget');
    $this->alterInfo('lp_swiper_widget_info');
    $this->setCacheBackend($cache_backend, 'lp_swiper_widget');
  }

  /**
   * Gets the definition of all or filtered plugins for this type.
   *
   * @param mixed $type
   *   A string or an array of types to filter on.
   *
   * @return mixed
   *   An array of plugin definitions. Keys are plugin IDs.
   */
  public function getDefinitions($type = NULL) {
    $definitions = parent::getDefinitions();

    // Filter widgets to keep only required types.
    if (!empty($type)) {
      foreach ($definitions as $widgetId => $widgetInfo) {
        if ((is_array($type) && !in_array($widgetInfo['type'], $type)) || (is_string($type) && $widgetInfo['type'] !== $type)) {
          unset($definitions[$widgetId]);
        }
      }
    }

    return $definitions;
  }

}
