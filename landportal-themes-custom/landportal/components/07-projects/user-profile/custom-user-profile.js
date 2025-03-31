/******** Silde up and down in user profile **************/

// jQuery(".layout__region--second .user-upper-section-detail h2").click(function() {
//   jQuery(".layout__region--second .user-upper-section-website").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-affiliation").slideToggle();
//   jQuery(".layout__region--second .upper-section-user-affiliation").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-location").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-country").slideToggle(); 
//   jQuery(".layout__region--second .user-upper-section-street").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-postal-code").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-city").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-country-code").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-location-name").slideToggle();
//   jQuery(".layout__region--second .user-upper-section-public-email").slideToggle();
// });

jQuery(".layout__region--second .user-upper-section-detail h2").click(function() {
  jQuery(this).closest('.layout__region--second').children().not('.title-of-name, .user-first-name, .user-last-name, .user-upper-section-detail, .user-profile-description').slideToggle();
});


 /******** Silde left and right in user profile for topics and regions **************/
                      
jQuery(document).ready(function(){
  jQuery(".layout__region--first .block:nth-child(2) h2").click(function(){
    jQuery(".layout__region--first .block:nth-child(3)").animate({
      width: "toggle"  
    });
     jQuery(".layout__region--first .block:nth-child(4)").animate({ 
      width: "toggle"  
    });
      jQuery(".layout__region--first .block:nth-child(5)").animate({
      width: "toggle"  
    });
  });
});    


/*****  Remove Topics and Region when region empty *************/

var regions = jQuery('.user-profile .layout__region--first .user-countries, .user-profile .layout__region--first .user-themes, .user-profile .layout__region--first .user-concepts').text();   
   if(regions == ''){
      jQuery('.user-profile .layout__region--first h2').hide();
   }

/*****  Remove Layout-second when user have no name  *************/

var userProfile = jQuery('.user-profile .layout__region--second .user-first-name').text();
  if(userProfile == ''){
    jQuery(".layout__region--second").hide();
  }

var lastName = jQuery('.user-profile .user-last-name div').text();
if(!lastName == ''){
  jQuery('.user-profile .user-first-name').css('background', 'inherit');
}else{
  jQuery('.user-profile .user-first-name').css('float', 'inherit');
}
//alert(lastName);