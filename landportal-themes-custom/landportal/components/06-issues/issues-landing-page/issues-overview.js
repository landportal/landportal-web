var readMoreUrl1 = jQuery('.background-overview-full-content .views-field-view-node a').attr('href');
var hreflang = jQuery('.background-overview-full-content .views-field-view-node a').attr('hreflang');

var url = window.location.pathname;
var segments = url.split('/');
var countrycode = segments[1];
if(hreflang == 'und'){
	if(countrycode != 'issues'){
		var readMoreUrl = jQuery('.background-overview-full-content .views-field-view-node a').attr('href', "/" + countrycode + readMoreUrl1);
	}
}

//alert('hmm');