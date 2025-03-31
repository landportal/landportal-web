<?php
namespace Drupal\download_indicator\Controller;

/**
 * Class JsonApiIndicatorController
 * @package Drupal\download_indicator\Controller
 */

class DownloadIndicatorController {
  public function download() {
    return array(
      '#markup' => 'Now From DownloadIndicatorController.'
    );
  }
}