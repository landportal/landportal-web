
var narrative_child_below = "empty";
var navigate_string = Drupal.t('Navigate the portfolio');
var select = '<select id="jumpIssueChild"><option value="">' + navigate_string + '</option>';
var option = '';
var parentUpOption = '';
var child = '';
var childHref = '';
   jQuery(".issue-narrative-child-up .views-row .field-content").each(function(){
      var parentUp = jQuery(this).html();
      jQuery(parentUp).each(function(){
         var parentUpChild = jQuery(this).html();
         var parentUpChildHref = jQuery(this).attr('href');
         parentUpOption = "<option value="+parentUpChildHref+">"+parentUpChild+"</option>";
      });
   });
   // jQuery('option').on('css')
// jQuery("option").css({"color":"red", "background-color":"green"});
   jQuery(".issue-narrative-child-below .views-row .field-content").each(function() {
      var parent = jQuery(this).html();
      jQuery(parent).each(function() {
      child = jQuery(this).html();
      narrative_child_below = "notempty";
      childHref = jQuery(this).attr('href');
      option = option + ("<option value="+childHref+">"+child+"</option>"); 
     }); 
  });
if(narrative_child_below == "notempty") {
var selectEnd = '</select>';
var fullSelectList = select+parentUpOption+option+selectEnd;
 jQuery('.back-to-overview').append(fullSelectList);
}
jQuery('#jumpIssueChild').on('change', function() {
   var url = jQuery(this).val(); 
    if (url) { 
        window.location = url; 
    }
  return false;
});

// ******************************Inner Page </select> & </option> Start Here.**********************//
var narrativeSibilings = "empty";
var selectSibilings = '<select id="jumpIssueSibilings"><option class="d-1">Navigate the portfolio</option>';
var sibilingsOptionBelow = '';
var sibilingOption = '';
jQuery('.siblings-narrative-up .views-row .views-field-parent-issue .field-content').each(function(){
   var sibilingsParentUp = jQuery(this).html();
   jQuery(sibilingsParentUp).each(function(){
      var sibilingsChildUp = jQuery(this).html();
      narrativeSibilings = "nonEmpty";
      var sibilingsChildUpHerf = jQuery(this).attr('href');
      sibilingOption = "<option class='d-1' value="+sibilingsChildUpHerf+">"+sibilingsChildUp+"</option>";
   });
});
jQuery('.siblings-narrative-below .views-row .views-field-title .field-content').each(function(){
   var sibilingsParentBelow = jQuery(this).html();
   jQuery(sibilingsParentBelow).each(function(){
      var sibilingsChildBelow = jQuery(this).html();
      var sibilingsChildBelowHref = jQuery(this).attr('href');
      sibilingsOptionBelow = sibilingsOptionBelow + ("<option class='d-1' value="+sibilingsChildBelowHref+">"+sibilingsChildBelow+"</option>");
   });
});
if(narrativeSibilings == "nonEmpty"){
   var selectSibilingsClose = "</select>";
   var siblingSelectFull =selectSibilings+sibilingOption+sibilingsOptionBelow+selectSibilingsClose;
   jQuery('.back-to-overview').append(siblingSelectFull);
}
jQuery('#jumpIssueSibilings').on('change', function(){
   var sibilingUrl = jQuery(this).val();
   if(sibilingUrl){
      window.location = sibilingUrl;
   }
   return false;
});


// Back To OverView 
var langUrl = jQuery('.section__sidebar__aside .views-field-thematic-narrative-1 a').attr('href');
var simpleUrl = jQuery('.section__sidebar__aside .back-to-overview-issue a').attr('href', langUrl);

/*************************Twitter*****************************/
var twitter = jQuery('.social-media-sharing .twitter a').attr('href');
var twitterEnc = decodeURIComponent(twitter.replace(/&amp;/g, "and"));
jQuery('.social-media-sharing .twitter a').attr('href', twitterEnc);

/*************************Facebook*****************************/
var facebook = jQuery('.social-media-sharing .facebook-share a').attr('href');
var facebookEnc = decodeURIComponent(facebook.replace(/&amp;/g, "and"));
jQuery('.social-media-sharing .facebook-share a').attr('href', facebookEnc);

/*************************LinkedIn*****************************/
var linkedIn = jQuery('.social-media-sharing .linkedin a').attr('href');
var linkedInEnc = decodeURIComponent(linkedIn.replace(/&amp;/g, "and"));
jQuery('.social-media-sharing .linkedin a').attr('href', linkedInEnc);

/*************************Gmail*****************************/
var gmail = jQuery('.social-media-sharing .email a').attr('href');
var gmailEnc = decodeURIComponent(gmail.replace(/&amp;/g, "and"));
jQuery('.social-media-sharing .email a').attr('href', gmailEnc);


/************************Project Landing Page Coding Start Here****************************/
var projectInclude = jQuery('.this-project-include .skin-default .text-long p').text();
if(projectInclude == ''){
   jQuery('.this-project-include').remove();   
}else{
   jQuery('.related-projects').remove();
}


/* Hide the title of 'this project is part of' when this field is empty*/
//var thisProjectIsPartOf = jQuery('.logo-full-background .views-field-this-project-is-part-of .field-content').text();
var thisProjectIsPartOf = jQuery('.logo-full-background .views-field-programme-logo .field-content').text();
if(thisProjectIsPartOf == ''){
   // jQuery('.views-field-this-project-is-part-of .field-content').parent().parent().remove();
   jQuery('.logo-full-background .views-field-programme-logo .field-content').parent().parent().remove();
}

/*adding class to block field*/
var nthChild = jQuery('.logo-full-background .block:nth-child(2)').text();
if(nthChild == ''){
   jQuery('.logo-full-background .views-field-this-project-is-part-of').addClass('extra-two');
   jQuery('.logo-full-background .views-field-nothing').addClass('extra');
}
/*.project-value-duration display none if it is not availible*/
var status = jQuery('.project-landing-image .project-value-duration h2').text();
if(status == ''){
   jQuery('.project-landing-image .project-value-duration').css("display", "none");
}

/************************Scorll to top coding is start here**********************/
    jQuery("body").append('<button type="button" class="scroll-top"></button>');
    jQuery('.scroll-top').hide();
    jQuery(window).scroll(function(){
        if (jQuery(this).scrollTop() > 5) {
            jQuery('.scroll-top').show().fadeIn();
        } else {
            jQuery('.scroll-top').fadeOut().hide();
        }
    });
    jQuery(".scroll-top").click(function() {
        jQuery("html, body").animate({ 
            scrollTop: 0 
        }, "slow");
        return false;
    });

/*******************get langcode from url in project content types nodes***************/


var projectInclude = window.location.pathname;
var projectIndex = projectInclude.split('/');
var actuallang = projectIndex[1];

var langcode = '';

/*this project include*/
jQuery( ".this-project-include a.page-title__link" ).each(function() {
 langcode = jQuery( this ).attr( "href" );
});
if(actuallang != 'community' && actuallang != 'node'){
   jQuery( ".this-project-include a.page-title__link" ).attr('href',"/"+actuallang+langcode);
}

/*related project*/
jQuery( ".related-projects a.page-title__link" ).each(function() {
 langcode = jQuery( this ).attr( "href" );
});
if(actuallang != 'community' && actuallang != 'node'){
   jQuery( ".related-projects a.page-title__link" ).attr('href',"/"+actuallang+langcode);
}

/*************Add Class to pagination when there is bullet in the organization fields*************/

var num1 = 0;
jQuery('.project-multiple-item .block:nth-child(1) .swiper-wrapper .organisations-card__image_overlay .link').each(function(){
    num1++;
});
if(num1 == 1){
   jQuery('.project-multiple-item .block:nth-child(1) .swiper-pagination').addClass('empty-bullet');
}

var num2 = 0;
jQuery('.project-multiple-item .block:nth-child(2) .swiper-wrapper .organisations-card__image_overlay .link').each(function(){
    num2++;
});
if(num2 == 1){
   jQuery('.project-multiple-item .block:nth-child(2) .swiper-pagination').addClass('empty-bullet');
}

var num3 = 0;
jQuery('.project-multiple-item .block:nth-child(3) .swiper-wrapper .organisations-card__image_overlay .link').each(function(){
    num3++;
});
if(num3 == 1){
   jQuery('.project-multiple-item .block:nth-child(3) .swiper-pagination').addClass('empty-bullet');
}


/************* Hide "Related Projects" Field when it is empty **************/

var relatedProject = jQuery('.related-projects .skin-default .views-field-related-projects .field-content').html();
if(relatedProject == ''){
   jQuery('.related-projects').css('display', 'none');
}else{
   //alert('Not empty');
}

/************this project include and related project'classes are not same in login page and logout page '*********/

var tray = jQuery('body').hasClass('toolbar-tray-open');

if(tray == false){
   /*this project include classes*/   
   jQuery('.project-currency-background div:nth-child(2) div:first-child').css({
      'display' : 'none'
   });
   jQuery('.project-currency-background').css('position', 'relative');
   jQuery('.project-currency-background div:nth-child(2) div:last-child').css({
      'position' : 'absolute',
      'bottom' : '0'
   });

   var currencyValuelogoutInclude = '';
   jQuery('.project-currency-background div:nth-child(2) div:last-child').each(function(){
      currencyValuelogoutInclude = jQuery(this).text();
   });
   var valueStringLogoutInclude = Drupal.t("Value");
   jQuery('.project-currency-background div:nth-child(2) div:last-child').text(valueStringLogoutInclude+': '+currencyValuelogoutInclude);

   jQuery('.project-currency-background div:nth-child(3)').css('display', 'none');
   jQuery('.project-currency-background div div:nth-child(4)').css('display', 'none');

   /***related project classes**/
   jQuery('.related-project-currency div:nth-child(2) div:first-child').css('display', 'none');
   jQuery('.related-project-currency').css('position', 'relative');
   jQuery('.related-project-currency .grid__item .block:nth-child(3) div div:last-child').css({
      'position' : 'absolute',
      'bottom' : '0'
   });

   var currencyValuelogout = '';
   jQuery('.related-projects .related-project-currency .grid__item .block:nth-child(3) div div:last-child').each(function(){
      currencyValuelogout = jQuery(this).text();
   });
   var valueStringLogout = Drupal.t("Value");
   jQuery('.related-projects .related-project-currency .grid__item .block:nth-child(3) div div:last-child').text(valueStringLogout+': '+currencyValuelogout);

   jQuery('.related-project-currency .grid__item .block:nth-child(3) div div:first-child').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(2)').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(1)').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(4)').css('display', 'none');
   jQuery('.related-project-currency div h2').css('display', 'none');
}

else{
   /***this project include***/
   jQuery('.project-currency-background div:nth-child(1) div:nth-child(3) div:first-child').css('display','none');
   jQuery('.project-currency-background div:nth-child(5)').css('display', 'none');
   jQuery('.project-currency-background div:nth-child(4)').css('display', 'none');
   jQuery('.project-currency-background').css('position', 'relative');
   jQuery('.project-currency-background div:nth-child(1) div:nth-child(3) div:last-child').css({
   'position' : 'absolute',
   'bottom' : '0'
   });
   var valueLogoutInclude = ''; 
   jQuery('.this-project-include .project-currency-background div:nth-child(1) div:nth-child(3) div:last-child').each(function(){
     valueStringLogoutInclude = jQuery(this).text();
   });
   var valueStringIncludeLogout = Drupal.t("Value");
   jQuery('.this-project-include .project-currency-background div:nth-child(1) div:nth-child(3) div:last-child').text(valueStringIncludeLogout+': '+valueStringLogoutInclude)

   /***related project ***/
   jQuery('.related-project-currency div div h2').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(2)').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(4)').css('display', 'none');
   jQuery('.related-project-currency .grid__item .block:nth-child(1)').css('display', 'none');
   // jQuery('.related-project-currency div div:nth-child(4) div:first-child').css('display', 'none');
   // jQuery('.related-project-currency').css('position', 'relative');
   jQuery('.related-project-currency .grid__item .block:nth-child(3)').css({
      'position' : 'absolute',
      'bottom' : '0'
   });
      var currencyValue = '';
      jQuery('.related-project-currency div div:nth-child(3) div:last-child').each(function(){
         currencyValue = jQuery(this).text();
      });
      var valueString = Drupal.t("Value");
      jQuery('.related-project-currency div div:nth-child(3) div:last-child').text(valueString+': '+currencyValue);

   }

 var druationString = Drupal.t("Duration");
var durationText = '';
jQuery('.related-projects .related-project-duration div').each(function(){
  durationText =  jQuery(this).text();
  jQuery(this).text(druationString+': '+durationText);
});

var durationTextinclude = '';
jQuery('.this-project-include .project-duration-background div').each(function(){
   durationTextinclude = jQuery(this).text();
   jQuery(this).text(druationString+': '+durationTextinclude);
});

/**** Add Comma in Value Field Prices 'related project'*****/
 var valPrice = '';
 jQuery('.related-projects .container .grid__item .block .layout .related-project-value div').each(function(){
    valPrice =  jQuery(this).text();
    jQuery(this).html(Number(valPrice).toLocaleString('en'));
 });

/** Add Comma in Value field Price 'this project include'**/
 var valPriceinlcude = '';
 jQuery('.this-project-include .container .grid__item .block .views-row .field-content .layout .project-value-background div').each(function(){
    valPriceinlcude = jQuery(this).text();
    jQuery(this).html(Number(valPriceinlcude).toLocaleString('en'));
 });

/***** top on page *****/

var landingPagePrice = jQuery('.project-landing-image .block:nth-child(2) .project-value-duration .row .project-price h2').text();

if (landingPagePrice != '-') {
    var element = jQuery('.project-landing-image .block:nth-child(2) .project-value-duration .row .project-price h2');
    var text = element.text();
    var numericValue = parseFloat(text.replace(/[^0-9.-]+/g,""));

    if (!isNaN(numericValue)) {
        var currencySign = text.replace(/[0-9,.]/g, ''); // Extract the currency sign
        var formattedValue = currencySign + numericValue.toLocaleString('en');
        
        // Check if the numericValue is a whole number (no decimal point)
        if (numericValue % 1 === 0) {
            // Add .00
            element.text(formattedValue + '.00');
        } else {
            // Don't add .00
            element.text(formattedValue);
        }
    }
}





/************** working on this page for redirection 'community/projects' ************/

jQuery(document).keypress(function (e) {
   if (e.which == 13) {
      var enterTxt = jQuery("#Search").val();
      var url =  window.location.pathname;
      var langCodeUrl = url.split('/')[1];

      if(langCodeUrl == 'community'){
         window.location = "/search-projects?key="+ enterTxt;
      }
      else{
         window.location = "/"+langCodeUrl+"/search-projects?key="+ enterTxt;
      }  
   }
});

jQuery('.sec-style-- #Search-btn').on('click', function(){

   var enterTxt = jQuery("#Search").val();
   var url =  window.location.pathname;
   var langCodeUrl = url.split('/')[1];
   if(langCodeUrl == 'community'){
      window.location = "/search-projects?key="+ enterTxt;
   }
   else{
      window.location = "/"+langCodeUrl+"/search-projects?key="+ enterTxt;
   }
  return false;
});


/*************Working on "/community/project" to get langcode************/

var urlSearchCommunity = window.location.pathname;
var urlSearchCommunitySplit = urlSearchCommunity.split('/');
var communityLangCode =  urlSearchCommunitySplit[1];
jQuery('.community-projects-landing-page .sec-style-- .views-element-container .field-content a').each(function(){
   var projectIssueAttr = jQuery( this ).attr('href');
   if(communityLangCode != 'community' && communityLangCode != 'node'){
      jQuery(this).attr('href', "/"+communityLangCode+projectIssueAttr);
   }
});


/************** This project include value translate issue ****************/


var urlget = window.location.pathname;
var urlgetSplit = urlget.split('/');
var projectLangCode =  urlgetSplit[1];
// alert(projectLangCode);

jQuery('.this-project-include .project-value-background').each(function(){
   var projectCommunityAttr = jQuery( this ).attr('href');
   if(projectLangCode == 'fr'){
      jQuery('.this-project-include .project-value-background').css('padding-left','70px');
   }
});


/*********************** Related project value Translate issue ******************/


var projectUrl1 = window.location.pathname;
var projectUrlSplit = projectUrl1.split('/');
var projectLangCode =  projectUrlSplit[1];

jQuery('.related-projects .related-project-value').each(function(){
   var projectCommunityAttr = jQuery( this ).attr('href');
   if(projectLangCode == 'fr'){
      jQuery('.related-projects .related-project-value').css('padding-left','70px');
   }
});


/*********************** programme login & logout classes ******************/


var loginclass = jQuery("body").hasClass("toolbar-horizontal");

if(loginclass == true){
   // alert("login");
   jQuery(".views-field-field-currency h2").css('display', 'none');
   jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(1)").css('display', 'none');
   jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(2)").css('display', 'none');
   jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:first-child").css('display', 'none');
   jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(4)").css('display', 'none');
   // jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:first-child").css({
   //    'color' : '#777'
   // });
   var abc = Drupal.t("Value");
   // var xyz = jQuery(".programme-projects-background .container .grid__item .block .skin-default .views-field-field-currency .field-content div:nth-child(3) div:nth-child(2)").text();
   jQuery(".project-in-this-programme .container .grid__item .block .skin-default .views-field-field-currency .field-content div:nth-child(3) div:nth-child(2)").each(function(){
      var xyz = jQuery(this).text();
      jQuery(this).text(abc+": "+xyz);
   });
}
else{

   // jQuery('.programme-counter-background .container .grid__item .block:nth-child(1) .views-field-programme-value span').css('display', 'none');

     // alert("logout");
    jQuery(".views-field-field-currency h2").css('display', 'none');
    jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(1)").css('display', 'none');
    jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(2)").css('display', 'none');
    jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:first-child").css('display','none');
    jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(4)").css('display','none');
    jQuery(".views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:last-child").css({
      'color' : '#777'
    });

    jQuery(".project-in-this-programme .container .grid__item .block .skin-default .views-field-field-currency .field-content").css('position', 'relative');

    var valueTxT = Drupal.t("Value");
    jQuery(".views-field-field-currency .field-content  .grid__item .block:nth-child(3) div div:last-child").each(function(){
      var valclass = jQuery(this).text();
      jQuery(this).text(valueTxT+": "+valclass);

    });  
}


/*******************Addition of Library resources on Programme Page************************/

var manuallyAddRes = 0;
jQuery('.project-disclaimer-azure-full .container .block .views-field-title-field a').each(function(){
   var rr = jQuery(this).text();
    manuallyAddRes ++;
});

var res = 0;
jQuery('.programme-counter-background .result-of-resource .views-field-delta').each(function(){
   var resources = jQuery(this).text();
   res += Number(resources);
});

//alert(manuallyAddRes);

var a = manuallyAddRes;
var b = res;
var c = (+a) + (+b);

var relatedresouce = Drupal.t("related resources available");
 jQuery('.result-of-resource').append('<span>'+c+'</span><p>'+relatedresouce+'</p>');


/****** Remove white space from Projects in this programme ************/

jQuery(".project-in-this-programme .container .grid__item .block .views-field-body h2").each(function(){
    var b = jQuery(this).html(function(i, html){
      return html.replace(/&nbsp;/g, '');
    });
});

jQuery(".project-in-this-programme .container .grid__item .block .views-field-body span").each(function(){
    var b = jQuery(this).html(function(i, html){
      return html.replace(/&nbsp;/g, '');
    });
});


/****************** 'Total Countries involved' Block in 'Programme' *******************/
jQuery('.programme-counter-background .views-field-project-regions .field-content:contains("544")').parent().remove();
jQuery('.programme-counter-background .views-field-project-regions .field-content:contains("7286")').parent().remove();
jQuery('.programme-counter-background .views-field-project-regions .field-content:contains("1542")').parent().remove();
var countCountries = 0;
jQuery('.programme-counter-background .views-field-project-regions .field-content').each(function(){
   countCountries++;
});
//var totalCountry = Drupal.t("total countries involved");
//jQuery('.programme-counter-background .total-countries').append('<span>'+countCountries+'</span><p>'+totalCountry+'</p>');
jQuery('.programme-counter-background .container .grid__item .block:nth-child(3) header').text(countCountries);

  /********** Add Duration word in project in this programme **/

var ProgrammeDuration = Drupal.t("Duration");
var DurationValue = '';
jQuery('.project-in-this-programme .container .views-field-field-project-duration').each(function(){
DurationValue = jQuery(this).text();
jQuery(this).text(ProgrammeDuration+': '+DurationValue); 
});


// var totalValue = Drupal.t('programme total value');
// jQuery('.programme-counter-background .container .grid__item .block:nth-child(1)').append("<span>"+totalValue+"</span>");
var totalDuration = Drupal.t('programme duration');
jQuery('.programme-counter-background .container .grid__item .block:nth-child(2)').append("<span>"+totalDuration+"</span>");



/********* Add space between value and currency sign in programme ************/

var programmeurl = window.location.pathname;
var programmeurlsplite = programmeurl.split('/');
var programmelangcode = programmeurlsplite[1];

jQuery('.project-in-this-programme .views-field-field-value-decimal .field-content ').each(function(){
   var programmefrvalue = jQuery(this).attr('href');
   if(programmelangcode == 'fr'){
      jQuery('.project-in-this-programme .views-field-field-value-decimal .field-content').css('padding-left', '7px');
   }
});
  
/************* Add landvoc bar in narrative page ******************/

var landvocbar = jQuery('.programme-landvoc-bar .layout__region--second .views-row .field-content a').text();
   
   if(landvocbar != ''){
     jQuery('.programme-landvoc-bar').css('display', 'flex');
   }
   else{
      jQuery('.programme-landvoc-bar').css('display', 'none');
   }

/***********  Add duration in programme in project landing page ***********/

var programmeDurationValue = Drupal.t('Duration');
var programmeDuration = '';

jQuery('.programmes-in-the-project-landing-page .container .views-field-programme-duration').each(function(){
   var durationDigits = jQuery(this).text();
   // alert(durationDigits);

   jQuery(this).text(programmeDurationValue+' : ' +durationDigits);
});

/***********  Add Value word in programme in project landing page ***********/

var programmeValueWord = Drupal.t("Value");
var programmeValue = '';

jQuery('.programmes-in-the-project-landing-page .views-field-currency-of-programme-value div:nth-child(3)  div:nth-child(2)').each(function(){
  var programmeValueDigits = jQuery(this).text();
  // alert(programmeValueDigits);
  jQuery(this).text(programmeValueWord+ ': '+programmeValueDigits );

});  

/********* Login or logout class of programmes in project landing page ***********/
 var loginClass = jQuery('body').hasClass('toolbar-horizontal');


 if(loginClass == true){
   var loginclass = jQuery('.programme-counter-background .block:nth-child(1) .views-row .views-field-currency-of-programme-value div:nth-child(3) div:nth-child(2)').html();
   jQuery('.programme-counter-background .container .grid__item .block:nth-child(1) .views-field-programme-value').append('<span>'+loginclass+'</span>');    
}
 else{
   var loginclass = jQuery('.programme-counter-background .block:nth-child(1) .field-content div:nth-child(2) div:nth-child(2)').html();
   jQuery('.programme-counter-background .container .grid__item .block:nth-child(1) .views-field-programme-value').append('<span>'+loginclass+'</span>');
 }

/*********** Insert comma in programme total value *********/
 // var zerosadd = '.00';
var programmetotalvalue = '';
 jQuery('.programme-counter-background .block:nth-child(1) .views-field-programme-value .field-content').each(function(){
   programmetotalvalue =  jQuery(this).text();
   jQuery(this).text(Number(programmetotalvalue).toLocaleString('en'));
 });

 /************** Back to programme overveiw button ***************/

 var rightUrl = jQuery('.programme-narrative-logo .section__sidebar__aside .block:nth-child(1) .views-field-link-to-narrative-1 a').attr('href');
 var backbutton = jQuery('.programme-narrative-logo .section__sidebar__aside .block:nth-child(1) .back-to-overview-issue a').attr('href',rightUrl);

 /*************** Login or Logout classes in project landing page for programme **************/

 var programmeSection = jQuery('body').hasClass('toolbar-horizontal');

 if(programmeSection == true){
   jQuery('.programmes-in-the-project-landing-page .views-field-currency-of-programme-value h2').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content .grid__item .block:nth-child(2)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content .grid__item .block:nth-child(3) div div:first-child').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content .grid__item .block:nth-child(4)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content .grid__item .block:nth-child(1)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content .grid__item .block:nth-child(3) div div:last-child').css({
      'color' : '#777'
   });
 } 

 else{
   jQuery('.programmes-in-the-project-landing-page .views-field-currency-of-programme-value h2').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content  .grid__item .block:nth-child(2)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content  .grid__item .block:nth-child(3) div div:first-child').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content  .grid__item .block:nth-child(1)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content  .grid__item .block:nth-child(4)').css('display','none');
   jQuery('.views-field-currency-of-programme-value .field-content  .grid__item .block:nth-child(3) div div:last-child').css({
      'color' : '#777'
   });

var programmeAddValueWord = Drupal.t("Value");
jQuery('.programmes-in-the-project-landing-page .views-field-currency-of-programme-value div:nth-child(2) div:nth-child(2)').each(function(){
  var programmeAddValueDigits = jQuery(this).text();
  // alert(programmeValueDigits);
  jQuery(this).text(programmeAddValueWord+ ': '+programmeAddValueDigits);

});  
}

/****** Add some space in programme section in project landing page in diff language to value ***/

var addsomeSpace = window.location.pathname;
var programmeUrlurlspliteproject = addsomeSpace.split('/');
var programmeProjectLangcode = programmeUrlurlspliteproject[1];

//alert(programmeProjectLangcode);

if(programmeProjectLangcode == 'fr'){
   jQuery('.programmes-in-the-project-landing-page .views-field-programme-value').css('margin-left','2%'); 
}

/********* Add comma in programme section in project landing page ***********/
var addCommaInProgramme = '';
// var addzerosPro = '.00';
jQuery('.programmes-in-the-project-landing-page .container .views-field-programme-value .field-content').each(function(){
   var addCommaInProgramme = jQuery(this).text();
   // jQuery(this).text()
   jQuery(this).text(Number(addCommaInProgramme).toLocaleString('en'));   
});
 

/********* Add comma in programme parent pages in Projects in this programme ***********/ 
var ProgrammeParentPages = '';
jQuery('.project-in-this-programme .container .views-field-field-value-decimal .field-content').each(function(){
   ProgrammeParentPages = jQuery(this).text();
   jQuery(this).text(Number(ProgrammeParentPages).toLocaleString('en'));
});

/*******  Change read more button link when language change in Programme ***********/
var programmeReadMore = window.location.pathname;
var programmeReadMoreUrl = programmeReadMore.split('/');
var programmeReadMoreLang = programmeReadMoreUrl[1];
var exactallyUrl = jQuery('.programme-map-background .container .field-content a').attr('href');

// console.log("read more ="+programmeReadMore);
// console.log("read more ="+programmeReadMoreUrl);
// console.log("read more ="+programmeReadMoreLang);

if(programmeReadMoreLang != 'community'){
   jQuery('.programme-map-background .container .field-content a').attr('href','/'+programmeReadMoreLang+exactallyUrl);
}
else{
   jQuery('.programme-map-background .container .field-content a').attr('href');
}

/*******  Change back to overview button link when language change in Programme ***********/

var programmeBackToOverveiw = window.location.pathname;
var programmeBackToOverveiwUrl = programmeBackToOverveiw.split('/');
var programmeBackToOverveiwLang = programmeBackToOverveiwUrl[1];

var exactallyBackToOverView = jQuery('.programme-narrative-logo .section__sidebar__aside .back-to-overview-issue a').attr('href');
 
if(programmeBackToOverveiwLang != 'community'){
   jQuery('.programme-narrative-logo .section__sidebar__aside .back-to-overview-issue a').attr('href','/'+programmeBackToOverveiwLang+exactallyBackToOverView);
}
else{
   jQuery('.programme-narrative-logo .section__sidebar__aside .back-to-overview-issue a').attr('href');
}


/******* Change image link when language change in Programme ***********/
var programmeImage = window.location.pathname;
var programmeImageUrl = programmeImage.split('/');
var programmeImageLang = programmeImageUrl[1];
jQuery('.project-in-this-programme .container .views-field-project-image a').each(function(){
   var actuallImagesUrl = jQuery(this).attr('href');
   if(programmeImageLang == 'fr' || programmeImageLang == 'pt' || programmeImageLang == 'es'){
      jQuery(this).attr('href', '/'+programmeImageLang+actuallImagesUrl);
   }
});
/******* Change Title link when language change in Programme ***********/

jQuery('.project-in-this-programme .views-field-title .field-content a').each(function(){
   var actuallTitleUrl = jQuery(this).attr('href');
   if(programmeImageLang == 'fr' || programmeImageLang == 'pt' || programmeImageLang == 'es'){
      jQuery(this).attr('href', '/'+programmeImageLang+actuallTitleUrl);
   }
});


/******* Change image link when language change in Programme in Project landing page ***********/

var programmeImageProject = window.location.pathname;
var programmeImageProjectUrl = programmeImageProject.split('/');
var programmeImageProjectLang = programmeImageProjectUrl[1]; 

jQuery('.programmes-in-the-project-landing-page .views-field-programme-banner a').each(function(){
   var programmeImageProjectUrll = jQuery(this).attr('href');
   if(programmeImageProjectLang == 'fr' || programmeImageProjectLang == 'pt' || programmeImageProjectLang == 'es'){
      jQuery(this).attr('href', '/'+programmeImageProjectLang+programmeImageProjectUrll);
   }
});

/******* Change Title link when language change in Programme in Project landing page ***********/

var programmeTitleProject = window.location.pathname;
var programmeTitleProjectUrl = programmeTitleProject.split('/');
var programmeTitleProjectLang = programmeTitleProjectUrl[1];

jQuery('.programmes-in-the-project-landing-page .views-field-name .field-content a').each(function(){
   var programmeTitleProjectUrll = jQuery(this).attr('href');
   if(programmeTitleProjectLang == 'fr' || programmeTitleProjectLang == 'pt' || programmeTitleProjectLang == 'es'){
      jQuery(this).attr('href', '/'+programmeTitleProjectLang+programmeTitleProjectUrll);
   }
});

/******* Change Sub Pages link when language change in Programme Narrative page ***********/

var programmeSubPages = window.location.pathname;
var programmeSubPagesUrl = programmeSubPages.split('/');
var programmeSubPagesLang = programmeSubPagesUrl[1];

jQuery('.programme-narrative-logo .section__sidebar__main .block:nth-child(4) .views-field-title a').each(function(){
   var actuallSubPagesUrl = jQuery(this).attr('href');

   if(programmeSubPagesLang == 'fr' || programmeSubPagesLang == 'pt' || programmeSubPagesLang == 'es'){
      jQuery(this).attr('href', '/'+programmeSubPagesLang+actuallSubPagesUrl);
   }
});

/******* Change Logo link when language change in Programme Narrative page ***********/

var programmeLogo = window.location.pathname;
var programmeLogoUrl = programmeLogo.split('/');
var programmeLogoLang = programmeLogoUrl[1];

// console.log("lang on logo = "+programmeLogo)
// console.log("lang on logo = "+programmeLogoUrl);
// console.log("lang on logo = "+programmeLogoLang);

//var programmelogo = jQuery('.programme-narrative-logo .section__sidebar__aside .block:nth-child(1) .views-field-programme-logo a').attr('href');
//if(programmeLogoLang != 'community'){
//   jQuery('.programme-narrative-logo .section__sidebar__aside .block:nth-child(1) .views-field-programme-logo a').attr('href','/'+programmeLogoLang+programmelogo);
//}

var programmelogo = jQuery('.programme-narrative-logo .section__sidebar__main .views-field-programme-logo a').attr('href');
if(programmeLogoLang != 'community'){
   jQuery('.programme-narrative-logo .section__sidebar__main .views-field-programme-logo a').attr('href','/'+programmeLogoLang+programmelogo);
}

/******* Change Sub Pages link when language change in Programme Sub pages ***********/

var programmeNarrativeSubPages = window.location.pathname;
var programmeNarrativeSubPagesUrl = programmeNarrativeSubPages.split('/');
var programmeNarrativeSubPagesLang = programmeNarrativeSubPagesUrl[1];

jQuery('.programme-narrative-logo .section__sidebar__main .block:nth-child(5) .views-field-title a').each(function(){
   var actuallprogrammeNarrative = jQuery(this).attr('href');

   if(programmeNarrativeSubPagesLang == 'fr' || programmeNarrativeSubPagesLang == 'pt' || programmeNarrativeSubPagesLang == 'es'){
      jQuery(this).attr('href', '/'+programmeNarrativeSubPagesLang+actuallprogrammeNarrative);
   }
});

/******* Breadcrumb link change when lang change in All Programme's Pages  ***********/


var programmeBreadCrumb = window.location.pathname;
var programmeBreadCrumbUrl = programmeBreadCrumb.split('/');
var programmeBreadCrumbLang = programmeBreadCrumbUrl[1];

var ParentBreadCrumb = jQuery('.programme-breadcrumb-background header a:nth-child(1)').attr('href');
var ParentBreadCrumb2ndA = jQuery('.programme-breadcrumb-background header a:nth-child(2)').attr('href');
var ProgrammeNarrativeBreadCrumb = jQuery('.programme-narrative-breadcrumb header a:nth-child(1)').attr('href');
var ProgrammeNarrativeBreadCrumb2ndA = jQuery('.programme-narrative-breadcrumb header a:nth-child(2)').attr('href');
var ProgrammeNarrativeBreadCrumb4thA = jQuery('.programme-narrative-breadcrumb header a:nth-child(4)').attr('href');

/************************* Programme Parent Page *********************/

if(programmeBreadCrumbLang != 'community'){
   jQuery('.programme-breadcrumb-background header a:nth-child(1)').attr('href','/'+programmeBreadCrumbLang+ParentBreadCrumb);
}
if(programmeBreadCrumbLang != 'community'){
   jQuery('.programme-breadcrumb-background header a:nth-child(2)').attr('href','/'+programmeBreadCrumbLang+ParentBreadCrumb2ndA);
}

/************************* Programme Narrative Page *********************/

if(programmeBreadCrumbLang != 'community'){
   jQuery('.programme-narrative-breadcrumb header a:nth-child(1)').attr('href','/'+programmeBreadCrumbLang+ProgrammeNarrativeBreadCrumb);
}

if(programmeBreadCrumbLang != 'community'){
   jQuery('.programme-narrative-breadcrumb header a:nth-child(2)').attr('href','/'+programmeBreadCrumbLang+ProgrammeNarrativeBreadCrumb2ndA);
}

if(programmeBreadCrumbLang != 'community'){
   jQuery('.programme-narrative-breadcrumb header a:nth-child(4)').attr('href','/'+programmeBreadCrumbLang+ProgrammeNarrativeBreadCrumb4thA);
}

/***********  Back To About link change when lang changed in Programme Narrative *************/

var programmeBackToButton = window.location.pathname;
var programmeBackToButtonUrl = programmeBackToButton.split('/');
var programmeBackToButtonLang = programmeBackToButtonUrl[1];

var BackToButtonUrl = jQuery('.programme-narrative-logo .section__sidebar__main .block:nth-child(3) .views-field-parent-programme span a').attr('href');

if(programmeBackToButtonLang != 'community'){
   jQuery('.programme-narrative-logo .section__sidebar__main .block:nth-child(3) .views-field-parent-programme span a').attr('href','/'+programmeBackToButtonLang+BackToButtonUrl);
}

/***********  Add language code in project and programmes page breadcrumb *************/

var projAndProg = window.location.pathname;
var projAndProgUrl = projAndProg.split('/');
var projAndProgUrlLang = projAndProgUrl[1];

// alert(projAndProgUrlLang);

var projAndProgActuallUrl = jQuery('.sec-style-- .container .text-long:nth-child(2) a').attr('href');

if(projAndProgUrlLang != 'community'){
   jQuery('.sec-style-- .container .text-long:nth-child(2) a').attr('href','/'+projAndProgUrlLang+projAndProgActuallUrl);
}  

/***********  Add language code in legend output overview link in legend about page  *************/
var legendOutPut = window.location.pathname;
var legendOutPutUrl = legendOutPut.split('/');
var legendOutPutUrlLang = legendOutPutUrl[1];

// alert(legendOutPutUrlLang);

jQuery('.programme-narrative-main-body .make-list ul li a').each(function(){
   var actualllegendOutPutUrl = jQuery(this).attr('href');
   if(legendOutPutUrlLang == 'fr' || legendOutPutUrlLang == 'pt' || legendOutPutUrlLang == 'es'){
      jQuery(this).attr('href','/'+legendOutPutUrlLang+actualllegendOutPutUrl);
   }    
});

/********** add language code when lang chnage for about word in breadcrumb **********/

var aboutBread = window.location.pathname;
var aboutBreadurl = aboutBread.split('/');
var aboutBreadurlLang = aboutBreadurl[1];
 
var aboutBreadcrumb = jQuery('.programme-narrative-breadcrumb header a:nth-child(5)').attr('href');

if(aboutBreadurlLang != 'community'){
   jQuery('.programme-narrative-breadcrumb header a:nth-child(5)').attr('href','/'+aboutBreadurlLang+aboutBreadcrumb); 
}


 /********************show loading... word when page refresh *********************/

var ssa = jQuery('.sec-style-- #block-landbook-view-coda-lbvc-map-country-wrapper').text();
 // alert(ssa);  
   if(ssa == 'Loading map...'){
         jQuery('.programmes-in-the-project-landing-page header h2').append('<span> loading... </span>');    
      // alert('durring reload');
   } 
   // else if(ssa != 'Loading map...'){
   //       jQuery('.programmes-in-the-project-landing-page header h2 span ').hide();   
   //     // alert('after reload');
   // }
         
   // else{
   //    jQuery(window).on('load', function(e) {
   //       jQuery('.programmes-in-the-project-landing-page header h2 span ').hide();
   //    });
   // }


      jQuery(window).on('load', function(e) { 
         jQuery('.programmes-in-the-project-landing-page header h2 span ').hide();
      });



/********project*********** Remove both related content section if empty ***********project*********/
var projectRe = jQuery('.project-disclaimer-azure-full .Project-Sliders .views-field-title-field .field-content a').text();
if(projectRe == ''){
   jQuery('.project-disclaimer-azure-full .Project-Sliders').parent().css('display', 'none');
}

var projectContent = jQuery('.related-impact-background .Project-Sliders .views-row').html();
if (jQuery.trim(projectContent) === '') {
  jQuery('.related-impact-background .Project-Sliders').parent().css('display', 'none');
} 

/*'implementing organization', 'donors' and 'data provider' block */

/*if title is empty in all items*/
jQuery('.project-multiple-item .organisations-block').each(function(){
    var block = jQuery(this);
    var isEmpty = true;
    block.find('.views-field-title span').each(function() {
        if (jQuery(this).text().trim() !== '') {
            isEmpty = false;
            return false;
        }
    });
    if (isEmpty) {
        block.closest('.block').remove();
    }
});

/*if whole block is empty*/
jQuery('.project-multiple-item .organisations-block').each(function(){
   if (!jQuery(this).find('.skin-default').length) {
      var mainBlock = jQuery(this).closest('.organisations-block');
      mainBlock.closest('.block').remove();
   }
});


 ///////////////  Work again for logout page of the Project landing Page currency issue ///////////

var againCurry = jQuery('.programme-counter-background .views-field-currency-of-programme-value .library-license div>div:last-child').text();
jQuery('.programme-counter-background .views-field-programme-value span').text(againCurry);


//alert('hmm');