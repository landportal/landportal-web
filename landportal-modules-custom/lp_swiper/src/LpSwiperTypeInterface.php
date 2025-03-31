<?php

namespace Drupal\lp_swiper;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Provides an interface for Views swiper types.
 */
interface LpSwiperTypeInterface extends PluginInspectionInterface, ConfigurableInterface, PluginFormInterface, DependentPluginInterface {}
