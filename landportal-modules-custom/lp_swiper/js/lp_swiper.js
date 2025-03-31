/**
 * @file
 */

(function ($, Drupal, drupalSettings) {
  'use strict';
  Drupal.lpSwiper = Drupal.lpSwiper || {};
  var pagerLocation;
  var slideNum;
  var error;
  var excludeMethods;
  /**
   * Lp Swiper Controls.
   */
  Drupal.lpSwiperControls = Drupal.lpSwiperControls || {};

  /**
   * Implement the play hook for controls.
   */
  Drupal.lpSwiperControls.play = function (options) {
    // Route the control call to the correct control type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperControls[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperControls[options.swiperID].top.type].play == 'function') {
        Drupal[drupalSettings.lpSwiperControls[options.swiperID].top.type].play(options);
        // alert('swiper');
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperControls[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperControls[options.swiperID].bottom.type].play == 'function') {
        Drupal[drupalSettings.lpSwiperControls[options.swiperID].bottom.type].play(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Implement the pause hook for controls.
   */
  Drupal.lpSwiperControls.pause = function (options) {
    // Route the control call to the correct control type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperControls[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperControls[options.swiperID].top.type].pause == 'function') {
        Drupal[drupalSettings.lpSwiperControls[options.swiperID].top.type].pause(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperControls[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperControls[options.swiperID].bottom.type].pause == 'function') {
        Drupal[drupalSettings.lpSwiperControls[options.swiperID].bottom.type].pause(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Lp Swiper Text Controls.
   */

  // Add views slieshow api calls for views swiper text controls.
  Drupal.behaviors.lpSwiperControlsText = {
    attach: function (context) {

      // Process previous link.
      $('.lp_swiper_controls_text_previous:not(.lp-swiper-controls-text-previous-processed)', context).addClass('lp-swiper-controls-text-previous-processed').each(function () {
        var uniqueID = $(this).attr('id').replace('lp_swiper_controls_text_previous_', '');
        $(this).click(function () {
          Drupal.lpSwiper.action({"action": 'previousSlide', "swiperID": uniqueID});
          return false;
        });
      });

      // Process next link.
      $('.lp_swiper_controls_text_next:not(.lp-swiper-controls-text-next-processed)', context).addClass('lp-swiper-controls-text-next-processed').each(function () {
        var uniqueID = $(this).attr('id').replace('lp_swiper_controls_text_next_', '');
        $(this).click(function () {
          Drupal.lpSwiper.action({"action": 'nextSlide', "swiperID": uniqueID});
          return false;
        });
      });

      // Process pause link.
      $('.lp_swiper_controls_text_pause:not(.lp-swiper-controls-text-pause-processed)', context).addClass('lp-swiper-controls-text-pause-processed').each(function () {
        var uniqueID = $(this).attr('id').replace('lp_swiper_controls_text_pause_', '');
        $(this).click(function () {
          if (drupalSettings.lpSwiper[uniqueID].paused) {
            Drupal.lpSwiper.action({"action": 'play', "swiperID": uniqueID, "force": true});
          }
          else {
            Drupal.lpSwiper.action({"action": 'pause', "swiperID": uniqueID, "force": true});
          }
          return false;
        });
      });
    }
  };

  Drupal.lpSwiperControlsText = Drupal.lpSwiperControlsText || {};

  /**
   * Implement the pause hook for text controls.
   */
  Drupal.lpSwiperControlsText.pause = function (options) {
    var pauseText = Drupal.theme.lpSwiperControlsPause ? Drupal.theme('lpSwiperControlsPause') : '';
    var $element = $('#lp_swiper_controls_text_pause_' + options.swiperID);
    $element.find('a').text(pauseText);
    $element.removeClass('lp-swiper-controls-text-status-play');
    $element.addClass('lp-swiper-controls-text-status-pause');
  };

  /**
   * Implement the play hook for text controls.
   */
  Drupal.lpSwiperControlsText.play = function (options) {
    var playText = Drupal.theme.lpSwiperControlsPlay ? Drupal.theme('lpSwiperControlsPlay') : '';
    var $element = $('#lp_swiper_controls_text_pause_' + options.swiperID);
    $element.find('a').text(playText);
    $element.removeClass('lp-swiper-controls-text-status-pause');
    $element.addClass('lp-swiper-controls-text-status-play');
  };

  // Theme the resume control.
  Drupal.theme.lpSwiperControlsPause = function () {
    return Drupal.t('Resume');
  };

  // Theme the pause control.
  Drupal.theme.lpSwiperControlsPlay = function () {
    return Drupal.t('Pause');
  };

  /**
   * Lp Swiper Pager.
   */
  Drupal.lpSwiperPager = Drupal.lpSwiperPager || {};

  /**
   * Implement the transitionBegin hook for pagers.
   */
  Drupal.lpSwiperPager.transitionBegin = function (options) {
    // Route the pager call to the correct pager type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperPager != "undefined" && typeof drupalSettings.lpSwiperPager[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].transitionBegin == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].transitionBegin(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperPager != "undefined" && typeof drupalSettings.lpSwiperPager[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].transitionBegin == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].transitionBegin(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Implement the goToSlide hook for pagers.
   */
  Drupal.lpSwiperPager.goToSlide = function (options) {
    // Route the pager call to the correct pager type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].goToSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].goToSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].goToSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].goToSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Implement the previousSlide hook for pagers.
   */
  Drupal.lpSwiperPager.previousSlide = function (options) {
    // Route the pager call to the correct pager type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].previousSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].previousSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].previousSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].previousSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Implement the nextSlide hook for pagers.
   */
  Drupal.lpSwiperPager.nextSlide = function (options) {
    // Route the pager call to the correct pager type.
    // Need to use try catch so we don't have to check to make sure every part
    // of the object is defined.
    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].top.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].nextSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].top.type].nextSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }

    try {
      if (typeof drupalSettings.lpSwiperPager[options.swiperID].bottom.type != "undefined" && typeof Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].nextSlide == 'function') {
        Drupal[drupalSettings.lpSwiperPager[options.swiperID].bottom.type].nextSlide(options);
      }
    }
    catch (err) {
      // Don't need to do anything on error.
    }
  };

  /**
   * Lp Swiper Pager Fields.
   */

  // Add views slieshow api calls for views swiper pager fields.
  Drupal.behaviors.lpSwiperPagerFields = {
    attach: function (context) {
      // Process pause on hover.
      $('.lp_swiper_pager_field:not(.lp-swiper-pager-field-processed)', context).addClass('lp-swiper-pager-field-processed').each(function () {
        // Parse out the location and unique id from the full id.
        var pagerInfo = $(this).attr('id').split('_');
        var location = pagerInfo[2];
        pagerInfo.splice(0, 3);
        var uniqueID = pagerInfo.join('_');

        // Add the activate and pause on pager hover event to each pager item.
        if (drupalSettings.lpSwiperPagerFields[uniqueID][location].activatePauseOnHover) {
          $(this).children().each(function (index, pagerItem) {
            var mouseIn = function () {
              Drupal.lpSwiper.action({"action": 'goToSlide', "swiperID": uniqueID, "slideNum": index});
              Drupal.lpSwiper.action({"action": 'pause', "swiperID": uniqueID});
            };

            var mouseOut = function () {
              Drupal.lpSwiper.action({"action": 'play', "swiperID": uniqueID});
            };

            if (jQuery.fn.hoverIntent) {
              $(pagerItem).hoverIntent(mouseIn, mouseOut);
            }
            else {
              $(pagerItem).hover(mouseIn, mouseOut);
            }
          });
        }
        else {
          $(this).children().each(function (index, pagerItem) {
            $(pagerItem).click(function () {
              Drupal.lpSwiper.action({"action": 'goToSlide', "swiperID": uniqueID, "slideNum": index});
            });
          });
        }
      });
    }
  };

  Drupal.lpSwiperPagerFields = Drupal.lpSwiperPagerFields || {};

//////// Iqbal testing start

  /**
   * Lp Swiper Pager Fields.
   */

  // Add views slieshow api calls for views swiper pager fields.
  Drupal.behaviors.viewsIqbal = {
    attach: function (context) {
      // Process pause on hover.
      //alert("hello");
    }
  };

  Drupal.viewsIqbal = Drupal.viewsIqbal || {};




// (function ($, Drupal, drupalSettings) {
//   /**
//    * @namespace
//    */
//   Drupal.behaviors.lp_swiperAccessData = {
//     attach: function (context) {
//       var data = lp_swiperComputedData;
//       alert("hello 2");
//     }
//   };
// })(jQuery, Drupal, drupalSettings);

/////////////////////Fida Muhammad Start//////////////////////

(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.lp_swiperBehavior = {
    attach: function (context, settings) {
      var color_body = drupalSettings.lp_swiper.color_body;
      var mobile_color_body = drupalSettings.lp_swiper.mobile_color_body;
      var allswipers = drupalSettings.lp_swiper.allswipers;
      $.each( allswipers, function(outer_div, slides_per_view){
          const swiper = new Swiper('#lp_swiper_cycle_main_' + outer_div, {
            autoHeight: true,
            pagination: {
               el: '.swiper-paginationb' + outer_div,
                   clickable: true,
            },

          // Mobile version
          slidesPerView: mobile_color_body,
          spaceBetween: 10,
          slidesPerGroup: mobile_color_body,
          // Responsive breakpoints
          breakpoints: {

            // Desktop Version
            992: {
              slidesPerView: slides_per_view,
              spaceBetween: 10,
              slidesPerGroup: slides_per_view,
            },

          }


        });   
          //Swiper code end 
      });
    
      //Swiper code end   
    }
  };
})(jQuery, Drupal, drupalSettings);




///////////////////////Fida Muhammad End////////////////



//////// Iqbal testing end


  /**
   * Implement the transitionBegin hook for pager fields pager.
   */
  Drupal.lpSwiperPagerFields.transitionBegin = function (options) {
    for (pagerLocation in drupalSettings.lpSwiperPager[options.swiperID]) {
      if (drupalSettings.lpSwiperPager[options.swiperID]) {
        // Remove active class from pagers.
        $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').removeClass('active');

        // Add active class to active pager.
        $('#lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_' + options.slideNum).addClass('active');
      }
    }
  };


  /**
   * Implement the goToSlide hook for pager fields pager.
   */
  Drupal.lpSwiperPagerFields.goToSlide = function (options) {
    for (pagerLocation in drupalSettings.lpSwiperPager[options.swiperID]) {
      if (drupalSettings.lpSwiperPager[options.swiperID]) {
        // Remove active class from pagers.
        $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').removeClass('active');

        // Add active class to active pager.
        $('#lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_' + options.slideNum).addClass('active');
      }
    }
  };

  /**
   * Implement the previousSlide hook for pager fields pager.
   */
  Drupal.lpSwiperPagerFields.previousSlide = function (options) {
    for (pagerLocation in drupalSettings.lpSwiperPager[options.swiperID]) {
      if (drupalSettings.lpSwiperPager[options.swiperID]) {
        // Get the current active pager.
        var pagerNum = $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"].active').attr('id').replace('lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_', '');

        // If we are on the first pager then activate the last pager.
        // Otherwise activate the previous pager.
        if (pagerNum === 0) {
          pagerNum = $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').length() - 1;
        }
        else {
          pagerNum--;
        }

        // Remove active class from pagers.
        $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').removeClass('active');

        // Add active class to active pager.
        $('#lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_' + pagerNum).addClass('active');
      }
    }
  };

  /**
   * Implement the nextSlide hook for pager fields pager.
   */
  Drupal.lpSwiperPagerFields.nextSlide = function (options) {
    for (pagerLocation in drupalSettings.lpSwiperPager[options.swiperID]) {
      if (drupalSettings.lpSwiperPager[options.swiperID]) {
        // Get the current active pager.
        var pagerNum = $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"].active').attr('id').replace('lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_', '');
        var totalPagers = $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').length();

        // If we are on the last pager then activate the first pager.
        // Otherwise activate the next pager.
        pagerNum++;
        if (pagerNum === totalPagers) {
          pagerNum = 0;
        }

        // Remove active class from pagers.
        $('[id^="lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '"]').removeClass('active');

        // Add active class to active pager.
        $('#lp_swiper_pager_field_item_' + pagerLocation + '_' + options.swiperID + '_' + slideNum).addClass('active');
      }
    }
  };

  // Copy the pager hooks from fields pager to the bullets one.
  Drupal.lpSwiperPagerBullets = Drupal.lpSwiperPagerFields || {};

  /**
   * Lp Swiper Slide Counter.
   */

  Drupal.lpSwiperSlideCounter = Drupal.lpSwiperSlideCounter || {};

  /**
   * Implement the transitionBegin for the slide counter.
   */
  Drupal.lpSwiperSlideCounter.transitionBegin = function (options) {
    $('#lp_swiper_slide_counter_' + options.swiperID + ' .num').text(options.slideNum + 1);
  };

  /**
   * This is used as a router to process actions for the swiper.
   */
  Drupal.lpSwiper.action = function (options) {
    // Set default values for our return status.
    var status = {
      'value': true,
      'text': ''
    };

    // If an action isn't specified return false.
    if (typeof options.action == 'undefined' || options.action === '') {
      status.value = false;
      status.text = Drupal.t('There was no action specified.');
      return error;
    }

    // If we are using pause or play switch paused state accordingly.
    if (options.action === 'pause') {
      drupalSettings.lpSwiper[options.swiperID].paused = 1;
      // If the calling method is forcing a pause then mark it as such.
      if (options.force) {
        drupalSettings.lpSwiper[options.swiperID].pausedForce = 1;
      }
    }
    else if (options.action === 'play') {
      // If the swiper isn't forced pause or we are forcing a play then play
      // the swiper.
      // Otherwise return telling the calling method that it was forced paused.
      if (!drupalSettings.lpSwiper[options.swiperID].pausedForce || options.force) {
        drupalSettings.lpSwiper[options.swiperID].paused = 0;
        drupalSettings.lpSwiper[options.swiperID].pausedForce = 0;
      }
      else {
        status.value = false;
        status.text += ' ' + Drupal.t('This swiper is forced paused.');
        return status;
      }
    }

    // We use a switch statement here mainly just to limit the type of actions
    // that are available.
    switch (options.action) {
      case "goToSlide":
      case "transitionBegin":
      case "transitionEnd":
        // The three methods above require a slide number. Checking if it is
        // defined and it is a number that is an integer.
        if (typeof options.slideNum == 'undefined' || typeof options.slideNum !== 'number' || parseInt(options.slideNum) !== (options.slideNum - 0)) {
          status.value = false;
          status.text = Drupal.t('An invalid integer was specified for slideNum.');
        }
      case "pause":
      case "play":
      case "nextSlide":
      case "previousSlide":
        // Grab our list of methods.
        var methods = drupalSettings.lpSwiper[options.swiperID]['methods'];

        // If the calling method specified methods that shouldn't be called then
        // exclude calling them.
        var excludeMethodsObj = {};
        if (typeof options.excludeMethods !== 'undefined') {
          // We need to turn the excludeMethods array into an object so we can use the in
          // function.
          for (var i = 0; i < excludeMethods.length; i++) {
            excludeMethodsObj[excludeMethods[i]] = '';
          }
        }

        // Call every registered method and don't call excluded ones.
        for (var i = 0; i < methods[options.action].length; i++) {
          if (Drupal[methods[options.action][i]] !== 'undefined' && typeof Drupal[methods[options.action][i]][options.action] == 'function' && !(methods[options.action][i] in excludeMethodsObj)) {
            Drupal[methods[options.action][i]][options.action](options);
          }
        }
        break;

      // If it gets here it's because it's an invalid action.
      default:
        status.value = false;
        status.text = Drupal.t('An invalid action "@action" was specified.', {"@action": options.action});
    }
    return status;
  };

})(jQuery, Drupal, drupalSettings);
