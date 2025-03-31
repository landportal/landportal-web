<?php

namespace Drupal\lp_swiper;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manager for Lp Swiper widget type plugins.
 */
class LpSwiperWidgetTypePluginManager extends DefaultPluginManager {

  /**
   * Constructs a new LpSwiperWidgetTypePluginManager.
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
    parent::__construct('Plugin/LpSwiperWidgetType', $namespaces, $module_handler, 'Drupal\lp_swiper\LpSwiperWidgetTypeInterface', 'Drupal\lp_swiper\Annotation\LpSwiperWidgetType');
    $this->alterInfo('lp_swiper_widget_type_info');
    $this->setCacheBackend($cache_backend, 'lp_swiper_widget_type');
  }

}
