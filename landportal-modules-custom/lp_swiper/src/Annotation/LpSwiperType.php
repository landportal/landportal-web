<?php

namespace Drupal\lp_swiper\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a swiper type annotation object.
 *
 * @Annotation
 */
class LpSwiperType extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the swiper type.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A list of actions this swiper type accepts.
   *
   * @var string[]
   */
  public $accepts;

  /**
   * A list of actions this swiper type implements.
   *
   * @var string[]
   */
  public $calls;

}
