<?php

namespace Drupal\lp_swiper;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manager for Lp Swiper Skin plugins.
 */
class LpSwiperSkinPluginManager extends DefaultPluginManager {

  /**
   * Constructs a new LpSwiperSkinPluginManager.
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
    parent::__construct('Plugin/LpSwiperSkin', $namespaces, $module_handler, 'Drupal\lp_swiper\LpSwiperSkinInterface', 'Drupal\lp_swiper\Annotation\LpSwiperSkin');

    $this->alterInfo('lp_swiper_skin_info');
    $this->setCacheBackend($cache_backend, 'lp_swiper_skin');
  }

}
