<?php

namespace Drupal\lp_swiper;

/**
 * Provides a class for CRUD operations on path aliases.
 */
interface FormatAddonsNameInterface {

  /**
   * Format callback manipulate addons names.
   */
  public function format($subject);

}
