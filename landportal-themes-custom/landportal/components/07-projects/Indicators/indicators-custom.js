 // alert ("Shahi just testing");

var startopt = '';
var navigate_select = Drupal.t('Choose other indicator from this dataset');
var select = '<select id="indicators-select-list"><option value="">' + navigate_select + '</option>';
jQuery('.layout__region--second .block:nth-child(2) .views-field-field-name .field-content a').each(function(){
	
	var ab = jQuery(this).text();
	var abc = jQuery(this).attr('href');
	
	startopt = startopt + ('<option value="'+abc+'">' + ab + '</option>');
});

var selectEnd = '</select>';

var full = select + startopt + selectEnd;

jQuery('.indicator-jump-menu').append(full);
     
jQuery('#indicators-select-list').on('change', function() {
   var url = jQuery(this).val(); 
    if (url) { 
        window.location = url; 
    }
  return false;
});


/**************** ******************** Add link to acronym ************************************/

jQuery('.layout__region--second .views-field-field-doc-licencing-1 div:nth-child(4) div:nth-child(2)').addClass('acronym');


var login = jQuery('body').hasClass('toolbar-tray-open'); 
//alert(login);
if (login == true) {
    var acro1 = jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 h2 a').attr('href');
    jQuery(document).ready(function() {
      var divText1 = jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym').text();
        //alert(divText1);
      var anchorTag1 = jQuery('<a></a>').attr('href', acro1).text(divText1);
 
      jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym').html(anchorTag1);
    });
}

else{

  jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 div:nth-child(3) div:nth-child(2)').addClass('acronym');

  var acro = jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 h2 a').attr('href');

jQuery(document).ready(function() {
  var divText = jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym').text();
  var anchorTag = jQuery('<a></a>').attr('href', acro).text(divText);

    jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym').html(anchorTag);
  });
 }  


/******************  License remove ******************************/
var lincse = jQuery('.layout__region--second .views-field-field-doc-licencing-1 .field-content').text();
  if(lincse == ''){
    jQuery('.layout__region--second .views-field-field-doc-licencing-1').css('display','none');
  }

/******************* Remove CopyRight Tag when empty *********************************/
var copyRight = jQuery('.layout__region--second .indicator-data-provider .views-field-field-license-text .field-content').text();
  if(copyRight == ''){
    jQuery('.layout__region--second .indicator-data-provider .views-field-field-license-text').css('display','none');
  }


// var acr0nym = jQuery('.layout__region--second .provider-and-acronym .views-field-field-doc-licencing-1 .acronym').text();
// alert (acr0nym);


/**this is running on indicator edit page**/
jQuery('.form-item--name-0-value').css('display', 'none');
jQuery('#edit-field-name-0-value').keyup(function(){
  var fieldName = jQuery(this).val();
  jQuery('#edit-name-0-value').val(fieldName);
});


// remove dataset text if no data /////////
jQuery(document).ready(function() {
  var dataDefinition = jQuery('.Indicator-data-definition .views-row .views-field-field-observations .field-content').text();
    if(dataDefinition == ''){
      jQuery('.Indicator-data-definition').hide();
    }
});


// remove sahre icon from certain pages /////////
jQuery(document).ready(function() {
  var shareIcon = jQuery('#block-showsourceanddisclaimerforprindexindicators').text();
    if(shareIcon != ''){
      jQuery('.social-media-lock').hide();
      jQuery('#block-addtoanysharebuttons-2').addClass('indi-share-icon'); 
    }
});

  

  