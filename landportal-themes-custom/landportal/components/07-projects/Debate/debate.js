//alert('hmm'); 


var event = jQuery('.debate .blog-post-upper-image .event-street div').text();
//alert('with divss = '+event);
var date = jQuery('.debate .blog-post-upper-image .event-date').append('<span class="event-time">'+event+ '</span>'); 

// jQuery(document).ready(function() {
//     var line = jQuery('.location-group').text().trim();
    
//     if (line === '') { 
//         jQuery('.location-group').css('display', 'none');
//     }
// });
 
/*********** Add Read more text in the comment section **********************/

jQuery(document).ready(function () {
    jQuery('.views-field-comment-body .field-content').append('<a class="read-moree">Read more</a>');

    jQuery('.views-field-comment-body .field-content .read-moree').click(function () {
        var parentContainer = jQuery(this).closest('.field-content');

        parentContainer.css({
            'max-height': function (index, value) {
                return value === 'inherit' ? '' : 'inherit';
            },
            'overflow': function (index, value) {
                return value === 'inherit' ? '' : 'inherit';
            },
            'position': function (index, value) {
                return value === 'inherit' ? '' : 'inherit';
            },
        });

        jQuery(this).css('opacity', '0');

        jQuery(this).removeClass('read-moree');
    }); 
});


/*********** Remove special character from the material field **********************/

jQuery('.event-material span a').each(function(){
	var material = jQuery(this).text();
	material = material.replace(/[^a-zA-Z0-9-_.]/g, '');

	material = material.replace(/20/g, ' ');

	jQuery(this).text(material);

	//jQuery(this).attr('href', material); 
});

/*Add Active Class On Debate nodes <li>*/
jQuery('.new_page_node_debate li.landportal-menu__item--debate').addClass('landportal-menu__item--active');


/// hide faciliator when empty ////

var faciliator = jQuery('.join-debate-view-custom .debate-facilitators, .join-debate-view-custom .debate-orgref').text().trim();
    if(faciliator === ''){
        jQuery('.join-debate-view-custom .debate-facilitators, .join-debate-view-custom .debate-orgref').hide();
    }