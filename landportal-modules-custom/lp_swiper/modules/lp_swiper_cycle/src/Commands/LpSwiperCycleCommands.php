<?php

namespace Drupal\lp_swiper_cycle\Commands;

use Drush\Commands\DrushCommands;
use Drush\Drush;

/**
 * Drush commands for Lp Swiper Cycle.
 */
class LpSwiperCycleCommands extends DrushCommands {

  /**
   * Download and install the jQuery Cycle library.
   *
   * @command views:swiper:cycle
   * @aliases dl-cycle,lp-swiper-cycle-cycle
   */
  public function downloadCycle() {
    $this->installLibrary(
      'jQuery Cycle',
      'libraries/jquery.cycle',
      'jquery.cycle.all.js',
      'https://raw.githubusercontent.com/malsup/cycle/3.0.3/jquery.cycle.all.js'
    );
  }

  /**
   * Download and install the JSON2 library.
   *
   * @command views:swiper:json2
   * @aliases dl-json2,lp-swiper-cycle-json2
   */
  public function downloadJson2() {
    $this->installLibrary(
      'JSON2',
      'libraries/json2',
      'json2.js',
      'https://raw.githubusercontent.com/douglascrockford/JSON-js/master/json2.js'
    );
  }

  /**
   * Download and install the jquery.hoverIntent library.
   *
   * @command views:swiper:hoverintent
   * @aliases dl-hoverintent,lp-swiper-cycle-hoverintent
   */
  public function downloadHoverIntent() {
    $this->installLibrary(
      'jQuery HoverIntent',
      'libraries/jquery.hoverIntent',
      'jquery.hoverIntent.js',
      'https://raw.githubusercontent.com/briancherne/jquery-hoverIntent/master/jquery.hoverIntent.js'
    );
  }

  /**
   * Download and install the jQuery.pause library.
   *
   * @command views:swiper:pause
   * @aliases dl-pause,lp-swiper-cycle-pause
   */
  public function downloadPause() {
    $this->installLibrary(
      'jQuery Pause',
      'libraries/jquery.pause',
      'jquery.pause.js',
      'https://raw.githubusercontent.com/tobia/Pause/master/jquery.pause.js'
    );
  }

  /**
   * Download and install the jQuery Cycle.
   *
   * Download and install JQuery hoverIntent, JSON2 and Pause libraries.
   *
   * @command views:swiper:lib
   * @aliases dl-cycle-lib,lp-swiper-cycle-lib
   */
  public function downloadLib() {
    $this->downloadCycle();
    $this->downloadHoverIntent();
    $this->downloadJson2();
    $this->downloadPause();
  }

  /**
   * Helper function to download a library in the given directory.
   */
  protected function installLibrary($name, $path, $filename, $url) {
    // Create the path if it does not exist.
    if (!is_dir($path)) {
      drush_op('mkdir', $path, 0755, TRUE);
      $this->logger()->info(dt('Directory @path was created', ['@path' => $path]));
    }

    // Be sure we can write in the directory.
    $perms = substr(sprintf('%o', fileperms($path)), -4);
    if ($perms !== '0755') {
      Drush::process('chmod 755 ' . $path);
    }
    else {
      $perms = NULL;
    }

    $dir = getcwd();

    // Download the JavaScript file.
    if (is_file($path . '/' . $filename)) {
      $this->logger()->notice(dt('@name appears to be already installed.', [
        '@name' => $name,
      ]));
    }
    elseif (drush_op('chdir', $path) && Drush::process('wget ' . $url)) {
      $this->logger()->success(dt('The latest version of @name has been downloaded to @path', [
        '@name' => $name,
        '@path' => $path,
      ]));
    }
    else {
      $this->logger()->warning(dt('Drush was unable to download the @name library to @path', [
        '@name' => $name,
        '@path' => $path,
      ]));
    }

    chdir($dir);

    // Restore the previous permissions.
    if ($perms) {
      Drush::process('chmod ' . $perms . ' ' . $path);
    }
  }

}
