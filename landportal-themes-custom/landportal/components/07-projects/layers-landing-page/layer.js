
/******************** Select list ********************************/
var startOpt = '';
var defaultOpt = Drupal.t('Choose other indicator from this dataset');

var select = '<select id="dataset-jump-menu"> <option value="">'+ defaultOpt +'</option>';
jQuery('.dataset-select-list .views-field-field-name .field-content a').each(function(){
	var layerText = jQuery(this).text();
	var layerVal = jQuery(this).attr('href');

	startOpt = startOpt + ('<option value='+ layerVal +'>' + layerText + '</option>');
});

var selectEnd = '</select>';
var full = select + startOpt + selectEnd;
jQuery('.dataset-select-list').append(full);

jQuery('#dataset-jump-menu').on('change', function() {
	var url = jQuery(this).val(); 
		if (url) { 
		    window.location = url; 
		}
		return false;
});


/************************* Add link on the licence Text ******************/

var licence = jQuery('.views-field-field-doc-licencing .layout--onecol .block:first-child a').attr('href');
//alert(licence);
var licenceText = jQuery('.views-field-field-doc-licencing .layout--onecol .block:last-child >div >div:last-child').text();
//jQuery(this).html(licence + licenceText);
var addLink = '<a href = "'+licence+'">' + licenceText + '</a>';

jQuery('.views-field-field-doc-licencing .layout--onecol .block:last-child >div').html(addLink);



var abc = jQuery('.views-field-field-doc-licencing .field-content .block:last-child div a').text();
//alert(abc);
if(abc == ''){
	jQuery('.views-field-field-doc-licencing ').css('display', 'none');
}


/************ Remove links from concepts tax when there are no content in view **********/

jQuery('.home-slider').each(function() {
    var concepts = jQuery(this).find('.layout--onecol .title').length > 0;
    if (!concepts) {
        jQuery(this).css('display', 'none');
        jQuery('.home-slider-library').css('display', 'block');
    }
});



// alert('hmm');  