// alert('shahi testing');


 
/********** Remove members title when no user name ************/

 jQuery('.Org-member .user-first-last-name').each(function(){
 	var members = jQuery(this).text();
 	// alert(members);
 		if(members == ' '){
 			jQuery('.Org-member').hide(); 
 		}
 });

 /********** Remove axtra anchor tag from regions label *****************/

 var region = jQuery('.organization-full-content .layout__region--second .organization-regions a[href="https://landmatrix.org"]').remove();
 

var organizationTitle = jQuery('.organization-heading-title .container span').text();
var resoucesTitle = jQuery('.library-resources-by-provider header .organization-title-url').text();
jQuery('.library-resources-by-provider header .organization-title-url').text(organizationTitle+' '+resoucesTitle);
// alert(resoucesTitle);
