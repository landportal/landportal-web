//alert('hmm');  

var startOption = '';
startWord = Drupal.t('Jump to SDGs page...');

var select = '<select class="SDGI-select-list"> <option value=" ">' + startWord + '</option >';
jQuery('.Jump-menu-SDGI .views-field-title a').each(function(){
	var nodeContent = jQuery(this).text();
	//alert(nodeContent);
	var contentAttr = jQuery(this).attr('href');
	//alert(contentAttr);
	startOption = startOption + ('<option value="'+ contentAttr +'">' + nodeContent + '</option>');
	//alert(startOption);
});

var selectEnd = '</select>';
var nodeSdgiSelectList = select + startOption +  selectEnd;
jQuery('.Jump-menu-SDGI').append(nodeSdgiSelectList);

jQuery('.SDGI-select-list').each(function() {
    jQuery(this).on('change', function() {
        var url = jQuery(this).val(); 
        if (url) { 
            window.location = url;  
        }
        return false;
    });
});


/******************* Accordion for SDGi Node Pages **************************/

// jQuery('.dataset-portfolio .dataset-accordian > div > div .primary-underline').click(function(){
// 	var nodeContent = jQuery(this).parent().children(":not(.primary-underline)");
// 	//alert(nodeContent);
// 	$nodeContent.toggleClass("hidden");
//     $nodeContent.slideToggle();
//   	jQuery(this).toggleClass("open"); 
// });


// jQuery('.dataset-portfolio .primary-underline').click(function(){
//     var nodeContent = jQuery(this).text();
//     alert(nodeContent);
// });

// alert('accordion');

jQuery(".dataset-portfolio .dataset-accordian > div > div .primary-underline").click(function () {
  var $content = jQuery(this).parent().children(":not(.primary-underline)");
  $content.toggleClass("hidden");
  $content.slideToggle();
  jQuery(this).toggleClass("open");
});


/***************** add counter to mini pager ******************/
jQuery(document).ajaxComplete(function(event, xhr, settings) {
  jQuery('.News-and-Blog nav ul li:contains("Page")').addClass('page-pager'); 
  var footer = jQuery('.News-and-Blog footer').html();
  jQuery('.News-and-Blog nav ul .page-pager').html(footer);
});



 jQuery(document).ready(function() {
    // Iterate through each div with class 'content'
    jQuery('.News-and-Blog .views-row .views-field-rendered-entity .field-content .layout--onecol .block:not(:nth-child(2))').each(function() {
      // Check if the content of the div contains any digit
      if (/\d/.test(jQuery(this).text())) {
        // Add a class 'contains-digit' to the div if it contains a digit
       jQuery(this).addClass('contains-digit');
      }
    });
  });

//alert('hmm');