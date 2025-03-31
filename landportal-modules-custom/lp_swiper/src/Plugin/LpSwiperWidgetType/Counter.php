<?php

namespace Drupal\lp_swiper\Plugin\LpSwiperWidgetType;

use Drupal\lp_swiper\LpSwiperWidgetTypeBase;

/**
 * Provides a counter widget type.
 *
 * @LpSwiperWidgetType(
 *   id = "lp_swiper_slide_counter",
 *   label = @Translation("Slide counter"),
 *   accepts = {
 *     "transitionBegin" = {"required" = TRUE},
 *     "goToSlide",
 *     "previousSlide",
 *     "nextSlide"
 *   },
 *   calls = {}
 * )
 */
class Counter extends LpSwiperWidgetTypeBase {
}
