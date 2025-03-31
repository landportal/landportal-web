  //alert('hmm');

  // Show content of the first .sdgs-goal-wrapper by default
  jQuery(".sdgs-goal-wrapper:first-child div").show();

  jQuery(".sdgs-goal-wrapper:not(:first-child) div").hide();

  jQuery(".sdgs-goal-wrapper h4").click(function () {
    jQuery(".sdgs-goal-wrapper div").not(jQuery(this).siblings("div")).slideUp();
    jQuery(this).siblings("div").slideToggle(600, "linear");
  });

  /****************  SDGI select list *********************/
  var startopt = "";
  var select_list_default = Drupal.t("Jump to SDGs page...");

  var select =
    '<select class="SDGI-select-list"><option value="">' +
    select_list_default +
    "</option>";
  jQuery(".Jump-menu-SDGI .views-field-title a").each(function () {
    var SDGIText = jQuery(this).text();
    var SDGIValue = jQuery(this).attr("href");
    startopt =
      startopt + ('<option value="' + SDGIValue + '">' + SDGIText + "</option>");
  });

  var selectEnd = "</select>";
  var fullSelectList = select + startopt + selectEnd;
  jQuery(".Jump-menu-SDGI").append(fullSelectList);

  jQuery(".SDGI-select-list").each(function () {
    jQuery(this).on("change", function () {
      var url = jQuery(this).val();
      if (url) {
        window.location = url;
      }
      return false;
    });
  });

  /****  '/organization landing page'  ***/

  /* By type of organization */
  var select_list_default_org = Drupal.t("- Choose -");
  var orgStartOpt = "";
  var orgSelect =
    '<select class="orgRefByType"><option value="">' +
    select_list_default_org +
    "</option>";
  jQuery(".orgref-by-type .views-row span a").each(function () {
    var orgLink = jQuery(this).attr("href");
    var orgTitle = jQuery(this).text();
    var orgNid = jQuery(this)
      .closest(".views-row")
      .find(".views-field-nid span")
      .text();
    orgStartOpt =
      orgStartOpt +
      ('<option value="' +
        orgLink +
        '">' +
        orgTitle +
        " " +
        orgNid +
        "</option>");
  });
  var orgSelectEnd = "</select>";
  var fullOrgSelectList = orgSelect + orgStartOpt + orgSelectEnd;
  jQuery(".orgref-by-type").append(fullOrgSelectList);

  jQuery(".orgRefByType").each(function () {
    jQuery(this).on("change", function () {
      var url = jQuery(this).val();
      if (url) {
        window.location = url;
      }
      return false;
    });
  });

  /* By Issue */
  var orgStartOpt = "";
  var orgSelect =
    '<select class="orgRefByIssue"><option value="">' +
    select_list_default_org +
    "</option>";
  jQuery(".orgref-by-issue .views-row span a").each(function () {
    //var orgLink = jQuery(this).attr("href");
    var orgLink = jQuery(this).closest(".views-row").find(".views-field-nothing span").text();
    var orgTitle = jQuery(this).text();
    var orgNid = jQuery(this)
      .closest(".views-row")
      .find(".views-field-nid span")
      .text();
    orgStartOpt =
      orgStartOpt +
      ('<option value="' +
        orgLink +
        '">' +
        orgTitle +
        " " +
        orgNid +
        "</option>");
  });
  var orgSelectEnd = "</select>";
  var fullOrgSelectList = orgSelect + orgStartOpt + orgSelectEnd;
  jQuery(".orgref-by-issue").append(fullOrgSelectList);

  jQuery(".orgRefByIssue").each(function () {
    jQuery(this).on("change", function () {
      var url = jQuery(this).val();
      if (url) {
        window.location = url;
      }
      return false;
    });
  });

  /* By geographical focus */
  var geoOption = "";
  var geoSelect =
    '<select class="orgRefByGeo"><option value="">' +
    select_list_default_org +
    "</option>";
  jQuery(".orgref-by-geoghraphical .views-row a").each(function () {
    var orgRefGeoTitle = jQuery(this).text();
    // var orgRefGeoLink = jQuery(this).attr("href");
    var orgRefGeoLink = jQuery(this).closest('.views-row').find('.views-field-nothing span').text();
    var orgGeoNid = jQuery(this)
      .closest(".views-row")
      .find(".views-field-nid span")
      .text();
    geoOption =
      geoOption +
      ("<option value=" +
        orgRefGeoLink +
        ">" +
        orgRefGeoTitle +
        " " +
        orgGeoNid +
        "</option>");
  });
  var orgSelectEnd = "</select>";
  var fullOrgSelectlist = geoSelect + geoOption + orgSelectEnd;
  jQuery(".orgref-by-geoghraphical").append(fullOrgSelectlist);

  jQuery(".orgRefByGeo").each(function () {
    jQuery(this).on("change", function () {
      var url = jQuery(this).val();
      if (url) {
        window.location = url;
      }
      return false;
    });
  });

  // Add toggle for Landing Page Accordion
  jQuery(".accordion-class-for-community div .primary-underline").click(
    function () {
      // alert("test");
      var jQuerycontent = jQuery(this).parent().children(":not(.primary-underline)");
      jQuerycontent.toggleClass("hidden");
      jQuerycontent.slideToggle();
      jQuery(this).toggleClass("open");
    }
  );


  /*********************** Add slideToggle in WhoWeAre page ************************/

  jQuery(document).ready(function () {
    var jQueryprimaryUnderlines = jQuery("[about='/who-we-are'] .section__sidebar__main .primary-underline, [about='/fr/who-we-are'] .section__sidebar__main .primary-underline, [about='/pt/who-we-are'] .section__sidebar__main .primary-underline, [about='/es/who-we-are'] .section__sidebar__main .primary-underline");

    jQueryprimaryUnderlines.each(function (index) {
      var jQuerycontent = jQuery(this).parent().children(":not(.primary-underline)");

      // Set the default state of the first .primary-underline content to be open
      if (index === 0) {
        jQuerycontent.show().addClass("open").removeClass("hidden");
        jQuery(this).addClass("active"); // Add "active" class for the initially open section
      } else {
        jQuerycontent.hide().addClass("hidden").removeClass("open");
      }

      // Click event handling
      jQuery(this).click(function () {
        var isOpen = jQuerycontent.is(":visible");

        // Check if any other content is open, then hide it
        if (!isOpen) {
          var jQueryopenContent = jQueryprimaryUnderlines.parent().children(":not(.primary-underline).open");
          jQueryopenContent.slideUp('slow').removeClass("open").addClass("hidden");
          jQueryprimaryUnderlines.removeClass("active"); // Remove "active" class from other sections
        }

        // Slide up initially for the first .primary-underline, then toggle between slide up and slide down
        jQuerycontent.slideToggle('slow', function () {
          isOpen = jQuerycontent.is(":visible");
          jQuery(this).toggleClass("open", isOpen).toggleClass("hidden", !isOpen);

          // Add the "active" class only to the currently open primary-underline
          jQuery(this).siblings(".primary-underline").toggleClass("active", isOpen);
        });
      });
    });
  });


  /*********************** Add slideToggle in LandVoc Community of Experts page ************************/

  jQuery(document).ready(function() {
    // Select the elements
    var accordion = jQuery('[about="/voc/landvoc/experts-community"] .section__sidebar__main .resp-accordion');
    var tabContent = jQuery('[about="/voc/landvoc/experts-community"] .section__sidebar__main .resp-tab-content');
    var tabActive = jQuery('[about="/voc/landvoc/experts-community"] .section__sidebar__main .resp-tab-active');
    // Add "active" class by default
    tabActive.addClass('active').css('background-color', '#DBDBDB');
    // Click event without debounce
    accordion.click(function() {
      tabContent.slideToggle({
        complete: function() {
          var isVisible = tabContent.is(':visible');

          if (isVisible) {
            tabActive.addClass('active').css('background-color', '#DBDBDB');
          } else {
            tabActive.removeClass('active').css('background-color', '#fff');
          }
        }
      });
    });
  });


  var typingTimer; // Timer identifier
  var doneTypingInterval = 500; // Time in milliseconds (0.5 seconds)

  // Function to perform search
  function performSearch() {
    var name = jQuery('.users-profile-page #views-exposed-form-user-profile-block-1 #edit-name').val().trim();
    
    // Check if the length of the entered name is two or more characters
    if(name.length >= 1) {
      // Trigger a click event on the submit button
      jQuery('.users-profile-page #views-exposed-form-user-profile-block-1 #edit-submit-user-profile').trigger('click');
    }
  }

  // Event handler for input on the edit-name input field
  jQuery('.users-profile-page #views-exposed-form-user-profile-block-1 #edit-name').on('input', function() {
    // Clear the previous timer
    clearTimeout(typingTimer);
    
    // Set a new timer
    typingTimer = setTimeout(performSearch, doneTypingInterval);
  });

  // Reset the timer on each keypress
  // jQuery('.users-profile-page #views-exposed-form-user-profile-block-1 #edit-name').on('keypress', function() {
  //   clearTimeout(typingTimer);
  // });

  /***************** Add country narrative link to the country overview link ***************/
  var countryNarrative = [];
  jQuery(".homepage-country .views-field-geographical-focus a").each(function(){
      countryNarrative.push(jQuery(this).attr('href'));
  });

  var countryOverviewAnchors = jQuery('.homepage-country .views-field-picture a');
  countryOverviewAnchors.each(function(index){
      jQuery(this).attr('href', countryNarrative[index]);
  });
    
    jQuery(document).ready(function() {
      let countriesArray = ["Select","NOT LISTED","Afghanistan", "Aland Islands", "Albania", "Algeria", "Amazonia", "American Samoa", "Andorra", "Angola", "Anguilla", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bonaire, Sint Eustatius and Saba", "Bosnia and Herzegovina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "British Virgin Islands", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Cook Islands", "Costa Rica", "Côte d'Ivoire", "CPLP", "CPLP countries", "Croatia", "Cuba", "Curaçao", "Cyprus", "Czech Republic", "Democratic People's Republic of Korea", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "England", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern and Antarctic Lands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard Island and McDonald Islands", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao S.A.R", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "North Macedonia", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn Islands", "Poland", "Portugal", "Puerto Rico", "Qatar", "Republic of Korea", "Réunion", "Romania", "Russia", "Rwanda", "Saint Barthélemy", "Saint Helena, Ascension and Tristan da Cunha", "Saint Kitts and Nevis", "Saint Lucia", "Saint Pierre and Miquelon", "Saint Vincent and the Grenadines", "Saint-Martin", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Scotland", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Sint Maarten", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and South Sandwich Islands", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard and Jan Mayen", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States Minor Outlying Islands", "United States of America", "United States Virgin Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Wales", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];

      let countryWrapper = jQuery('.user-form #edit-country-wrapper');
      let dropdownIcon = jQuery('<b>')
      countryWrapper.append(dropdownIcon)

      let requiredIcon=jQuery('<span>',{'class':'required-icon'})
      requiredIcon.text('*')
      countryWrapper.append(requiredIcon)

      let dropDown = jQuery('<div>', { 'class': 'dropdown' });
      countryWrapper.append(dropDown)

      let inputElement = jQuery('<input>', { 'type': 'text', 'class': 'drop-down-input' });
      dropDown.append(inputElement)

      let selectList = jQuery('<ul>', { 'class': 'select-list' })
      dropDown.append(selectList)

      jQuery.each(countriesArray, function (index, item) {
        let listItem;
        if (index === 0) {
          listItem =   jQuery('<li>', {'class': 'active hovered'}).text(item).css({'background-color': 'lightgray'
            });
        } else {
            listItem = jQuery('<li>').text(item);
        }
        selectList.append(listItem);
      });

      let mainInput = jQuery('.user-form #edit-country-wrapper .form-item input');
      mainInput.prop('readonly', true);
      var value = jQuery('.select-list li.active').text();
      jQuery(mainInput).val(value);
      let activeLi=jQuery('.select-list li.active')

      let isFirstClick = true;
      jQuery(mainInput).on("click", function(){
          if(isFirstClick) {
              isFirstClick = true;
          } else {
              jQuery(this).toggleClass("main-input-border-color");
          }
      });

      jQuery(mainInput).click(function () {
        jQuery('.dropdown').toggle();
        jQuery('.select-list li.active').addClass('hovered')
        mainInput.toggleClass('toggleInput');
        dropdownIcon.toggleClass('drop-down-icon') 
        inputElement.val('');
        jQuery('.drop-down-input').addClass('drop-down-input-focus')
        inputElement.focus();
        selectList.show()
        jQuery(inputElement).on('input',function(){
          if(jQuery(this).val()===''){
            jQuery('#ui-id-3').hide()
            jQuery('.select-list').show()
          }
          else
          {
            jQuery('#ui-id-3').show()
            jQuery('.select-list').hide()
          }
        })
      });
          
      jQuery('.select-list').on('click', 'li', function () {
        mainInput.toggleClass('toggleInput');
        dropdownIcon.toggleClass('drop-down-icon')
        jQuery('.dropdown').hide();
        let mainInputValue = jQuery(this).text();
        jQuery(mainInput).val(mainInputValue);
        jQuery('.select-list li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.select-list li').css('background-color', '');
        jQuery('.select-list li.active').css('background-color', 'lightgray');
        jQuery(this).addClass('hovered')
      
      })

      jQuery('.select-list').on('mouseover', 'li', function () {
        jQuery('.select-list li.active').removeClass('hovered');
      });

      /***************************autocomplete****************/
      /*apply this condition to prevent error in console*/
      
      if (jQuery(".user-form #edit-country-wrapper .drop-down-input").length) { 
        jQuery(".user-form #edit-country-wrapper .drop-down-input").autocomplete({
          source: countriesArray,
          select: function (event, ui) {
            var selectedCountry = ui.item.value;
            // Set the value of the main input field
            jQuery(mainInput).val(selectedCountry);
            jQuery('.user-form #edit-country-wrapper .drop-down-input').val('');
            // Hide the dropdown
            jQuery('.dropdown').hide();
            // Toggle classes
            dropdownIcon.toggleClass('drop-down-icon');
            mainInput.toggleClass('toggleInput');
            // return false; // Prevent the default behavior of the Autocomplete widget
          }
        });
      }
      let uiAutocompleteElement = jQuery('#ui-id-3');
      // Detach the element from its current location
      uiAutocompleteElement.detach();
      let autocompleteList = jQuery('.user-form #edit-country-wrapper .dropdown');
      autocompleteList.append(uiAutocompleteElement);
      

    });

    jQuery(document).ready(function(){
      let button1 = jQuery('<button/>', {
        text: 'Public profile',
        id: 'btn1',
      });

      let button2 = jQuery('<button/>', {
          text: 'More about you',
          id: 'btn2',
          
      });

      jQuery('.user-form .layout__region--top').append(button1);
      jQuery('.user-form .layout__region--top').append(button2);
      jQuery('.user-form  input,.user-form button').blur();
      jQuery('.user-form .layout__region--top #btn1').focus();

      jQuery('.user-form .layout__region--top #btn1').click(function(e){
        e.preventDefault()
        jQuery(this).css({
          'background': '#fff',
          'border-color': '#EBEBE5',
          'border-top-color': '1px rgb(235, 235, 229)',
          'color': '#000'
        });
        jQuery('.user-form .layout__region--top #btn2').css({
          'background': '#eee',
          'color': '#06C',
        });
        
        jQuery('.user-form .layout__region--second-above .field--name-field-position').hide();
        jQuery('.user-form .layout__region--second-above .field--name-public-email').hide();
        jQuery('.user-form .layout__region--second-above .field--name-field-orgref').hide();
        jQuery('.user-form .layout__region--second-above .field--name-field-sector').hide();
        jQuery('.user-form .layout__region--second-above .field--name-field-occupation').hide();
        jQuery('.user-form .layout__region--second-above .field--name-geographical-focus').hide();
        jQuery('.user-form .layout__region--second-above .field--name-themes').hide();
        jQuery('.user-form .layout__region--second-above .field--name-related-concepts').hide();
        jQuery('.user-form .layout__region--second-above .field--name-website').hide();

        jQuery('.user-form .layout__region--second-above .field--name-title').show();
        jQuery('.user-form .layout__region--second-above .field--name-first-name').show();
        jQuery('.user-form .layout__region--second-above .field--name-last-name').show();
        jQuery('.user-form .layout__region--second-above .field--name-hide-profile').show();
        jQuery('.user-form .layout__region--second-above .field--name-description').show();
        jQuery('.user-form .layout__region--second-above .field--name-country').show();
        jQuery('.user-form .layout__region--second-above .field--name-location-name').show();
        jQuery('.user-form .layout__region--second-above .field--name-street').show();
        jQuery('.user-form .layout__region--second-above .field--name-city').show();
        jQuery('.user-form .layout__region--second-above .field--name-postal-code').show();

      });
      jQuery('.user-form .layout__region--top #btn2').click(function(e){
        e.preventDefault();
        jQuery(this).css({
          'background': '#fff',
          'border-color': '#EBEBE5',
          'border-top-color': '1px rgb(235, 235, 229)',
          'color': '#000'
        });
        jQuery('.user-form .layout__region--top #btn1').css({
          'background': '#eee',
          'color': '#06C',
        });
        jQuery('.user-form .layout__region--second-above .field--name-field-position').show();
        jQuery('.user-form .layout__region--second-above .field--name-public-email').show();
        jQuery('.user-form .layout__region--second-above .field--name-field-orgref').show();
        jQuery('.user-form .layout__region--second-above .field--name-field-sector').show();
        jQuery('.user-form .layout__region--second-above .field--name-field-occupation').show();
        jQuery('.user-form .layout__region--second-above .field--name-geographical-focus').show();
        jQuery('.user-form .layout__region--second-above .field--name-themes').show();
        jQuery('.user-form .layout__region--second-above .field--name-related-concepts').show();
        jQuery('.user-form .layout__region--second-above .field--name-website').show();

        jQuery('.user-form .layout__region--second-above .field--name-title').hide();
        jQuery('.user-form .layout__region--second-above .field--name-first-name').hide();
        jQuery('.user-form .layout__region--second-above .field--name-last-name').hide();
        jQuery('.user-form .layout__region--second-above .field--name-hide-profile').hide();
        jQuery('.user-form .layout__region--second-above .field--name-description').hide();
        jQuery('.user-form .layout__region--second-above .field--name-country').hide();
        jQuery('.user-form .layout__region--second-above .field--name-location-name').hide();
        jQuery('.user-form .layout__region--second-above .field--name-street').hide();
        jQuery('.user-form .layout__region--second-above .field--name-city').hide();
        jQuery('.user-form .layout__region--second-above .field--name-postal-code').hide();
      })

    });

    jQuery(document).ready(function() {
      jQuery('.user-form .field--type-entity-reference option[value="_none"]').remove();
      jQuery('.user-form .field--type-entity-reference select').select2();
    });
    jQuery(document).ready(function() {
      jQuery('.language-switcher-language-url li.ru').remove();
      jQuery('.language-switcher-language-url li.nl').remove();
    });

    
/*** Accordion ****/
jQuery(".dataset-portfolio .dataset-accordian > div > div .primary-underline").click(function () {
  var $content = jQuery(this).parent().children(":not(.primary-underline)");
  $content.toggleClass("hidden");
  $content.slideToggle();
  jQuery(this).toggleClass("open");
});