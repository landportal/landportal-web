var startopt = '';
var navigate_select = Drupal.t('Choose other indicator from this dataset');

var select = '<select id="dataset-jump-menu"><option value="">' + navigate_select + '</option>';
jQuery('.dataset-select-list .views-field-field-name .field-content a').each(function(){
	var datasetText = jQuery(this).text();
	var datasetVal = jQuery(this).attr('href');
	startopt = startopt + ('<option value="'+datasetVal+'">' + datasetText + '</option>');
});

var selectEnd = '</select>';
var full = select + startopt + selectEnd;
jQuery('.dataset-select-list').append(full);

jQuery('#dataset-jump-menu').on('change', function() {
   var url = jQuery(this).val(); 
    if (url) { 
        window.location = url; 
    }
  return false;
});

//alert('shahi');

var license = jQuery('.section__sidebar__aside .dataset-license .views-field-field-acronym .field-content a').text();
//alert(license);

if (license == '') {
	jQuery('.section__sidebar__aside .dataset-license').css('display','none');
}


jQuery(document).ready(function() {
    var selectlist = jQuery('#dataset-jump-menu').text();
	//alert(selectlist);
	if(selectlist == 'Choose other indicator from this dataset'){
		jQuery('#dataset-jump-menu').css('display','none');
	}
});
 
/*******Accordian Functionality******/
jQuery('.dataset-accordian > div > div .primary-underline').click(function() {
	jQuery(this).parent().find('.image-content-right, ul').slideToggle();
	jQuery(this).toggleClass('open');
});


/*Hover effect on on Partner and Donors Section*/
jQuery('.dataset-full-style .container .section__sidebar__aside .dataset-partners-donors .views-row .field-content article > div').hover(
  function() {
    jQuery(this).find('.layout:nth-child(2)').show();
  },
  function() {
    jQuery(this).find('.layout:nth-child(2)').hide();
  }
);

/*If there is Donor or Partner is available then remove the dataset provider block*/
var datasetDonor = jQuery('.dataset-partners-donors .views-row .field-content article > div .layout:nth-child(2) .layout__region .block a').text();
if(!datasetDonor == ''){
	jQuery('.dataset-provider-aside').css('display', 'none');
}else{
	jQuery('.dataset-full-style .container .section__sidebar__aside .dataset-partners-donors').css('display', 'none');
}

/**** Check if an image exists within the element with class .dataset-banner-image****/

if (jQuery('.dataset-banner-image').find('img').length > 0) {
  jQuery('.dataset-full-style').addClass('other-style');
} 


// remove sahre icon from dataset certain pages /////////
jQuery(document).ready(function() {
  var shareIcon = jQuery('#block-showsourceanddisclaimerforprindexindicators').text();
    if(shareIcon != ''){
      jQuery('.social-media-lock').hide();
      jQuery('#block-addtoanysharebuttons-2').addClass('indi-share-icon'); 
    }
});




//alert('hmm');