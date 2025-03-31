// jQuery( document ).ready(function() {
/*****Reset Button on "By Implementing organization" *****/
jQuery(document).on('change', ".search-projects-view #block-implementingorganization select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 //var urlTrm = url.replace('?key=&', '');

	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "By issue" *****/
jQuery(document).on('change', ".search-projects-view #block-byissues select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);

	 console.log(JSON.stringify(final));
});

/*****Reset Button on "By geographical focus" *****/
jQuery(document).on('change', ".search-projects-view #block-geographicalscope select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "By concepts" *****/
jQuery(document).on('change', ".search-projects-view #block-relatedconcepts-2 select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "By funder" *****/
jQuery(document).on('change', ".search-projects-view #block-bydonors select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "By year range" *****/
jQuery(document).on('change', ".search-projects-view #block-projectduration select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "By End Date" *****/
jQuery(document).on('change', ".search-projects-view #block-projectdurationenddate select.item-list__dropdown", function(){
	 var sel = jQuery(this).val();
	 var txtrep = sel.replace('/search-projects?', '');
	 var url = jQuery(location).attr("href");
	 var reset = '?key=&';
	 var final = jQuery(location).attr("href", url+reset+txtrep);
});

/*****Reset Button on "Programme" *****/
// jQuery(document).on('change')

// var a = jQuery(".search-projects-view #block-programme").text();
// alert(a);

jQuery(document).on('change', ".search-projects-view #block-programme .item-list__dropdown", function(){
	var abc = jQuery(this).val();
	var urltxt = abc.replace('/search-projects?', '');
	var programmeurl = jQuery(location).attr("href");
	var resetbutton = '?key=&';
	var finalbutton = jQuery(location).attr("href", programmeurl+resetbutton+urltxt);
	});

/****classes are not same in login page and logout page*******/

var tray = jQuery('body').hasClass('toolbar-tray-open');
if(tray == false){
	//alert('this is logout Page');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(2)').css('display', 'none');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:first-child').css('display', 'none');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(4)').css('display', 'none');
	jQuery('.views-field-field-currency .field-content h2').parent().css('position', 'relative');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:last-child').addClass('currency-sign-logout');

}
else{
	//alert('this login page');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(2)').css('display', 'none');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(3) div div:first-child').css('display', 'none');
	jQuery('.views-field-field-currency .field-content .grid__item .block:nth-child(4)').css('display', 'none');
	jQuery('.views-field-field-currency .field-content div:nth-child(3) div:nth-child(2)').addClass('currency-sign');

}

/* Adding comma in digists*/
var valueProject = '';
jQuery('.search-projects-view #block-mainpagecontent .views-field-field-value-decimal span').each(function(){
	valueProject = jQuery(this).text();
	jQuery(this).html(Number(valueProject).toLocaleString('en'));
});

/*********Replacing counter header in "/search-projects" Page*******/
var lowerHeader = jQuery('.search-projects-view #block-mainpagecontent header').html();
var upperHeader = jQuery('.search-projects-view #block-views-block-search-projects-solr-block-1 header').html(lowerHeader);

jQuery(lowerHeader).append(upperHeader);

jQuery(upperHeader).css("display","block");

/***********Langcode change for image link and also for title link /search-projects**********/

var urlSearchProject = window.location.pathname;
var urlSearchProjectSplit = urlSearchProject.split('/');
var projectLangCode =  urlSearchProjectSplit[1];
jQuery('.search-projects-view #block-mainpagecontent .views-view-grid .views-field-project-image span a').each(function(){
	var projectCommumityAttr = jQuery( this ).attr('href');
	if(projectLangCode == 'fr' || projectLangCode == 'pt' || projectLangCode == 'es'){
		jQuery(this).attr('href', '/'+projectLangCode+projectCommumityAttr);
	}
});
jQuery('.search-projects-view #block-mainpagecontent .views-view-grid .views-field-title span a').each(function(){
	var projectCommumityAttr = jQuery( this ).attr('href');
	if(projectLangCode == 'fr' || projectLangCode == 'pt' || projectLangCode == 'es'){
		jQuery(this).attr('href', '/'+projectLangCode+projectCommumityAttr);
	}
});

/******************* Counter Display none when search page has no project  *************************/


var xyz = jQuery('.search-projects-view #block-views-block-search-projects-solr-block-1 header').html();

   // var axc = '       Displaying  0 -  0  of  0    ';

   // alert(xyz.charAt(19));

if(xyz.charAt(19) == 0){
   	// alert("yes");
  jQuery('.search-projects-view #block-views-block-search-projects-solr-block-1 header').replaceWith(jQuery('#block-mainpagecontent h3').html());
   	// jQuery('#block-mainpagecontent .contextual-region p').html();

   jQuery("#block-views-block-search-projects-solr-block-1").css({
   'color' : 'white',
   'font-weight' : '600'
});
}

/******************** Remove 0 value from search project page *****************/


  // jQuery('.search-projects-view #block-mainpagecontent .views-field-field-value-decimal span').each(function(){

  	// var asd = jQuery(this).text();
  	// if(asd == 0){
  	// 	jQuery(this).css("display","none");
  	// }

  // });

/******************** Programme  *****************/




 //alert('hmm'); 
// });