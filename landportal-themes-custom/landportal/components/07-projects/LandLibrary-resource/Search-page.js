// alert('Hi boss in library search page');   

var searchText = '';
jQuery(document).ready(function() {

    searchText = jQuery('#edit-search-api-fulltext').val();

    if (searchText) {
        jQuery('.bread-crumb').before('<li class="testing">' + searchText +  ' <h5> Library search </h5>' +'</li>');
        jQuery('#block-landlibrarysearch').css('display', 'none');
        jQuery('.main--with-sidebar .main-content--with-sidebar #block-mainpagecontent .views-row').css('display', 'block');
        jQuery('.main--with-sidebar .pager').css('display', 'block');
        jQuery('.main--with-sidebar header span').css('display', 'block');
    } else {
        jQuery('.testing').css('display', 'none');      
    }  
});
   
 
  jQuery(document).ready(function() {
    if (jQuery('#block-landlibrarysearch').css('display') !== 'none') {
        jQuery('.main--with-sidebar .main-sidebar').css('margin-top','28px');
    }else{
    }
});



/************* hide the block when click on anchor tags **************/
jQuery(document).ready(function() {
    var currentUrl = window.location.href;
    var lib = currentUrl.split('library/search')[1];
    if(lib != ''){
       // alert('present');
        jQuery('#block-landlibrarysearch').css('display', 'none');
        jQuery('.main--with-sidebar .main-content--with-sidebar #block-mainpagecontent .views-row').css('display', 'block');
        jQuery('.main--with-sidebar .pager').css('display', 'block');  
   }else{

   }
});


/******************** For Search page breadcrumb ********************/

// jQuery(document).ready(function() {
//     jQuery('.main-sidebar .facets-widget-links a').click(function() {
//         var selectedItem = jQuery(this).text();
           
//         var storedItems = JSON.parse(localStorage.getItem('selectedItems')) || [];
//         storedItems.push(selectedItem);
//         localStorage.setItem('selectedItems', JSON.stringify(storedItems));

//         appendSelectedItems(storedItems);  

//     });

//     // Append selected items to .testing h5
//     function appendSelectedItems(items) {
//         var itemsToAppend = '';

//         items.forEach(function(item) {
//             var extractedString = item.replace(/\s*\([^)]*\)\s*$/, '');
//             itemsToAppend += '<li class="next-anchor">' + extractedString + '</li>';

//         });

//         jQuery('.testing h5').before(itemsToAppend);
//     }

//     // On page load, append previously selected items
//     var storedItems = JSON.parse(localStorage.getItem('selectedItems')) || [];
//     appendSelectedItems(storedItems);
// });










 //alert('hmm');   


      

