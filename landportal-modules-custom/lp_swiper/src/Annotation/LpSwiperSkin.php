<?php

namespace Drupal\lp_swiper\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a swiper skin annotation object.
 *
 * @Annotation
 */
class LpSwiperSkin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the swiper skin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A list of libraries this swiper skin needs to attach.
   *
   * @var string[]
   */
  public $libraries;

}
