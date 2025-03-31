// alert('hello boss in event');

var location = jQuery('.blog-post-upper-image .event-location div').text();
var street = jQuery('.blog-post-upper-image .event-street div').text();
var postal = jQuery('.blog-post-upper-image .event-postal div').text();
var city = jQuery('.blog-post-upper-image .event-city div').text();
var state = jQuery('.blog-post-upper-image .event-state div').text();
var country = jQuery('.blog-post-upper-image .event-country div').text();
   
  
var eventDatehtml = jQuery('.blog-post-upper-image .event-date').html();
jQuery('.blog-post-upper-image .event-date').append('<div class="location-group"> <div class="inner-group"> </div> <span class="location">'+location+'</span> <div> <span class="street">'+street+'</span><div>  <span class="postal">'+postal+'</span> <span class="city">'+city+'</span> <span class="state">'+state+'</span> <div class="country">'+country+'<div>  </div> ');  

         
// jQuery(document).ready(function() {
// 	var eventCity = jQuery('.blog-post-upper-image .event-city div').text();
// 	if (eventCity != ''){
// 		var dateEvent = jQuery('.blog-post-upper-image .event-date').css('border-bottom-right-radius','0');
// 	}
// });

jQuery(document).ready(function() {
var dot = jQuery('.location-group .state').text();
	if(dot == ''){
		jQuery('.location-group .state').hide();
	}
});


jQuery(document).ready(function() {
    var line = jQuery('.location-group').text().trim();
    
    if (line === '') { 
        jQuery('.location-group').css('display', 'none');
    }
});

/***  Related events Field  ***/
var typeLable = jQuery('.related-events-grid .views-field-type span.field-content').text();
var childClass = jQuery('.related-events-grid').children();

    var childClass = jQuery('.related-events-grid .views-row').children('.views-field-field-related-events');
    if (childClass.length > 0) {
        //alert('The .views-field-field-related-events class exists within .related-events-grid');
    }else{
        jQuery('.related-events-grid .views-field-type span.field-content').css('display', 'none');
        jQuery('.related-events-grid header').css('display', 'none');
    }

 //alert(childClass);