/**
 * @file
 * Attaches jump menu behaviors to the country selectors.
 */
(function ($, Drupal) {

  "use strict";

  Drupal.behaviors.landportalCountryJumpMenu = {
    attach: function (context, settings) {
      $('select.landportal-country-jumpmenu').on('change', function (e) {
        location.href = this.value;
      });
    }
  };

})(jQuery, Drupal);
