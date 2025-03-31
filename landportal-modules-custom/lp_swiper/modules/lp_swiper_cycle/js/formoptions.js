/**
 *  @file
 * Javascript to enhance the views swiper cycle form options.
 */

/**
 * This will set our initial behavior, by starting up each individual swiper.
 */
(function ($) {

  // Since Drupal 7 doesn't support having a field based on one of 3 values of
  // a select box we need to add our own JavaScript handling.
  Drupal.behaviors.lpSwiperCycleAmountAllowedVisible = {
    attach: function (context) {

      // If necessary at start hide the amount allowed visible box.
      var type = $(":input[name='style_options[lp_swiper_cycle][pause_when_hidden_type]']").val();
      if (type == 'full') {
        $(":input[name='style_options[lp_swiper_cycle][amount_allowed_visible]']").parent().hide();
      }

      // Handle dependency on action advanced checkbox.
      $(":input[name='style_options[lp_swiper_cycle][action_advanced]']").change(function () {
        processValues('action_advanced');
      });

      // Handle dependency on pause when hidden checkbox.
      $(':input[name="style_options[lp_swiper_cycle][pause_when_hidden]"]').change(function () {
        processValues('pause_when_hidden');
      });

      // Handle dependency on pause when hidden type select box.
      $(":input[name='style_options[lp_swiper_cycle][pause_when_hidden_type]']").change(function () {
        processValues('pause_when_hidden_type');
      });

      // Process our dependencies.
      function processValues(field) {
        switch (field) {
          case 'action_advanced':
            if (!$(':input[name="style_options[lp_swiper_cycle][action_advanced]"]').is(':checked')) {
              $(":input[name='style_options[lp_swiper_cycle][amount_allowed_visible]']").parent().hide();
              break;
            }
          case 'pause_when_hidden':
            if (!$(':input[name="style_options[lp_swiper_cycle][pause_when_hidden]"]').is(':checked')) {
              $(":input[name='style_options[lp_swiper_cycle][amount_allowed_visible]']").parent().hide();
              break;
            }
          case 'pause_when_hidden_type':
            if ($(":input[name='style_options[lp_swiper_cycle][pause_when_hidden_type]']").val() == 'full') {
              $(":input[name='style_options[lp_swiper_cycle][amount_allowed_visible]']").parent().hide();
            }
            else {
              $(":input[name='style_options[lp_swiper_cycle][amount_allowed_visible]']").parent().show();
            }
        }
      }
    }
  }

  // Manage advanced options.
  Drupal.behaviors.lpSwiperCycleOptions = {
    attach: function (context) {
      if ($(":input[name='style_options[lp_swiper_cycle][advanced_options]']").length) {
        $(":input[name='style_options[lp_swiper_cycle][advanced_options]']").parent().hide();

        $(":input[name='style_options[lp_swiper_cycle][advanced_options_entry]']").parent().after(
          '<div style="margin-left: 10px; padding: 10px 0;">' +
            '<a id="edit-style-options-lp-swiper-cycle-advanced-options-update-link" href="#">' + Drupal.t('Update Advanced Option') + '</a>' +
          '</div>'
        );

        $("#edit-style-options-lp-swiper-cycle-advanced-options-table").append('<tr><th colspan="2">' + Drupal.t('Applied Options') + '</th><tr>')

        var initialValue = $(":input[name='style_options[lp_swiper_cycle][advanced_options]']").val();
        var advancedOptions = JSON.parse(initialValue);
        for (var option in advancedOptions) {
          lpSwiperCycleAdvancedOptionsAddRow(option);
        }

        // Add the remove event to the advanced items.
        lpSwiperCycleAdvancedOptionsRemoveEvent();

        $(":input[name='style_options[lp_swiper_cycle][advanced_options_choices]']").change(function () {
          var selectedValue = $(":input[name='style_options[lp_swiper_cycle][advanced_options_choices]'] option:selected").val();
          if (typeof advancedOptions[selectedValue] !== 'undefined') {
            $(":input[name='style_options[lp_swiper_cycle][advanced_options_entry]']").val(advancedOptions[selectedValue]);
          }
          else {
            $(":input[name='style_options[lp_swiper_cycle][advanced_options_entry]']").val('');
          }
        });

        $('#edit-style-options-lp-swiper-cycle-advanced-options-update-link').click(function () {
          var option = $(":input[name='style_options[lp_swiper_cycle][advanced_options_choices]']").val();
          if (option) {
            var value = $(":input[name='style_options[lp_swiper_cycle][advanced_options_entry]']").val();

            if (typeof advancedOptions[option] == 'undefined') {
              lpSwiperCycleAdvancedOptionsAddRow(option);
              lpSwiperCycleAdvancedOptionsRemoveEvent()
            }
            advancedOptions[option] = value;
            lpSwiperCycleAdvancedOptionsSave();
          }

          return false;
        });
      }

      function lpSwiperCycleAdvancedOptionsAddRow(option) {
        $("#edit-style-options-lp-swiper-cycle-advanced-options-table").append(
          '<tr id="lp-swiper-cycle-advanced-options-table-row-' + option + '">' +
            '<td>' + option + '</td>' +
            '<td style="width: 20px;">' +
              '<a style="margin-top: 6px" title="Remove ' + option + '" alt="Remove ' + option + '" class="views-hidden views-button-remove lp-swiper-cycle-advanced-options-table-remove" id="lp-swiper-cycle-advanced-options-table-remove-' + option + '" href="#"><span>Remove</span></a>' +
            '</td>' +
          '</tr>'
        );
      }

      function lpSwiperCycleAdvancedOptionsRemoveEvent() {
        $('.lp-swiper-cycle-advanced-options-table-remove').unbind().click(function () {
          var itemID = $(this).attr('id');
          var uniqueID = itemID.replace('lp-swiper-cycle-advanced-options-table-remove-', '');
          delete advancedOptions[uniqueID];
          $('#lp-swiper-cycle-advanced-options-table-row-' + uniqueID).remove();
          lpSwiperCycleAdvancedOptionsSave();
          return false;
        });
      }

      function lpSwiperCycleAdvancedOptionsSave() {
        var advancedOptionsString = JSON.stringify(advancedOptions);
        $(":input[name='style_options[lp_swiper_cycle][advanced_options]']").val(advancedOptionsString);
      }
    }
  }

/*Select One Pager Option, 
if one pager is checked then it will automaticaly unchecked the other pager.*/
$(document).on("ajaxComplete", function( event, request, settings) {
  $(".form-item--style-options-widgets-top-lp-swiper-pager-enable input").change(function(){
    if(this.checked){
     $(".form-item--style-options-widgets-bottom-lp-swiper-pager-enable input").prop('checked',false); 
    }
  });

  $(".form-item--style-options-widgets-bottom-lp-swiper-pager-enable input").change(function(){
    if(this.checked){
     $(".form-item--style-options-widgets-top-lp-swiper-pager-enable input").prop('checked',false); 
    }
  });
});

})(jQuery, Drupal);
