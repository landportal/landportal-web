<?php

namespace Drupal\lp_swiper;

/**
 * Provides an interface for Views swiper skins.
 */
interface LpSwiperSkinInterface {

  /**
   * Returns a array of libraries to attach when the skin is used.
   *
   * @return array
   *   The libraries to be attached.
   */
  public function getLibraries();

  /**
   * Returns a class to be added to templates.
   *
   * @return string
   *   The class name.
   */
  public function getClass();

}
