<?php

/**
 * @file
 * Drush integration for Lp Swiper.
 *
 * Specifically downloading of required and optional JavaScript libraries.
 */

use Drush\Drush;

/**
 * Implements hook_drush_command().
 */
function lp_swiper_cycle_drush_command() {
  $items = [];

  // The key in the $items array is the name of the command.
  $items['lp-swiper-cycle-cycle'] = [
    'description' => dt('Download and install the jQuery Cycle library.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'arguments' => [
      'path' => dt('Optional. The path to install the jQuery Cycle library in. If omitted Drush will use the default location.'),
    ],
    'aliases' => ['dl-cycle'],
  ];
  $items['lp-swiper-cycle-json2'] = [
    'description' => dt('Download and install the JSON2 library.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'arguments' => [
      'path' => dt('Optional. The path to install the JSON2 library in. If omitted Drush will use the default location.'),
    ],
    'aliases' => ['dl-json2'],
  ];
  $items['lp-swiper-cycle-hoverintent'] = [
    'description' => dt('Download and install the jquery.hoverIntent library.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'arguments' => [
      'path' => dt('Optional. The path to install the jquery.hoverIntent library in. If omitted Drush will use the default location.'),
    ],
    'aliases' => ['dl-hoverintent'],
  ];
  $items['lp-swiper-cycle-pause'] = [
    'description' => dt('Download and install the jQuery.pause library.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'arguments' => [
      'path' => dt('Optional. The path to install the jQuery.pause library in. If omitted Drush will use the default location.'),
    ],
    'aliases' => ['dl-pause'],
  ];
  $items['lp-swiper-cycle-lib'] = [
    'description' => dt('Download and install the jQuery Cycle, jQuery hoverIntent and JSON2 libraries.'),
    'bootstrap' => DRUSH_BOOTSTRAP_DRUSH,
    'aliases' => ['dl-cycle-lib'],
  ];
  return $items;
}

/**
 * Implements hook_drush_help().
 */
function lp_swiper_cycle_drush_help($section) {
  switch ($section) {
    case 'drush:lp-swiper-cycle-cycle':
      return dt('Download and install the jQuery Cycle library from http://malsup.github.com/jquery.cycle.all.js, default location is libraries/jquery.cycle.');

    case 'drush:lp-swiper-cycle-hoverintent':
      return dt('Download and install the jQuery hoverIntent library from https://raw.githubusercontent.com/briancherne/jquery-hoverIntent/master/jquery.hoverIntent.js, default location is libraries/jquery.cycle.');

    case 'drush:lp-swiper-cycle-pause':
      return dt('Download and install the jQuery pause library from https://raw.githubusercontent.com/tobia/Pause/master/jquery.pause.js, default location is libraries/jquery.pause.');

    case 'drush:lp-swiper-cycle-json2':
      return dt('Download and install the JSON2 library from https://github.com/douglascrockford/JSON-js/, default location is libraries/json2.');

    case 'drush:lp-swiper-cycle-lib':
      return dt('Download and install the jQuery Cycle, jQuery hoverIntent and JSON2 libraries.');
  }
}

/**
 * Command to download the jQuery Cycle library.
 */
function drush_lp_swiper_cycle_cycle() {
  _drush_lp_swiper_cycle_install_library(
    'jQuery Cycle',
    'libraries/jquery.cycle',
    'jquery.cycle.all.js',
    'https://raw.githubusercontent.com/malsup/cycle/3.0.3/jquery.cycle.all.js',
    func_get_args()
  );
}

/**
 * Command to download the JSON2 library.
 */
function drush_lp_swiper_cycle_json2() {
  _drush_lp_swiper_cycle_install_library(
    'JSON2',
    'libraries/json2',
    'json2.js',
    'https://raw.githubusercontent.com/douglascrockford/JSON-js/master/json2.js',
    func_get_args()
  );
}

/**
 * Command to download the jQuery.hoverIntent library.
 */
function drush_lp_swiper_cycle_hoverintent() {
  _drush_lp_swiper_cycle_install_library(
    'jQuery HoverIntent',
    'libraries/jquery.hoverIntent',
    'jquery.hoverIntent.js',
    'https://raw.githubusercontent.com/briancherne/jquery-hoverIntent/master/jquery.hoverIntent.js',
    func_get_args()
  );
}

/**
 * Command to download the jQuery.pause library.
 */
function drush_lp_swiper_cycle_pause() {
  _drush_lp_swiper_cycle_install_library(
    'jQuery Pause',
    'libraries/jquery.pause',
    'jquery.pause.js',
    'https://raw.githubusercontent.com/tobia/Pause/master/jquery.pause.js',
    func_get_args()
  );
}

/**
 * Command to download all libraries.
 */
function drush_lp_swiper_cycle_lib() {
  drush_lp_swiper_cycle_cycle();
  drush_lp_swiper_cycle_json2();
  drush_lp_swiper_cycle_hoverintent();
  drush_lp_swiper_cycle_pause();
}

/**
 * Implements drush_MODULE_pre_pm_enable().
 */
function drush_lp_swiper_cycle_pre_pm_enable() {
  $modules = drush_get_context('PM_ENABLE_MODULES');
  if (in_array('lp_swiper_cycle', $modules) && !drush_get_option('skip')) {
    drush_lp_swiper_cycle_lib();
  }
}

/**
 * Helper function to download a library in the given directory.
 */
function _drush_lp_swiper_cycle_install_library($name, $path, $filename, $url, $args) {
  // Check if path provided as an arg and use that instead of default if so.
  if (!empty($args[0])) {
    $path = $args[0];
  }

  // Create the path if it does not exist.
  if (!is_dir($path)) {
    drush_op('mkdir', $path, 0755, TRUE);
    \Drupal::logger(dt('Directory @path was created', ['@path' => $path]), 'success');
  }

  // Be sure we can write in the directory.
  $perms = substr(sprintf('%o', fileperms($path)), -4);
  if ($perms !== '0755') {
    Drush::process('chmod 755 ' . $path);
  }

  $dir = getcwd();

  // Download the JavaScript file.
  if (is_file($path . '/' . $filename)) {
    \Drupal::logger(dt('@name appears to be already installed.', [
      '@name' => $name,
    ]), 'ok');
  }
  elseif (drush_op('chdir', $path) && Drush::process('wget --no-check-certificate ' . $url)) {
    \Drupal::logger(dt('The latest version of @name has been downloaded to @path', [
      '@name' => $name,
      '@path' => $path,
    ]), 'success');
  }
  else {
    \Drupal::logger(dt('Drush was unable to download the @name library to @path', [
      '@name' => $name,
      '@path' => $path,
    ]), 'error');
  }

  chdir($dir);

  // Restore the previous permissions.
  Drush::process('chmod ' . $perms . ' ' . $path);
}
