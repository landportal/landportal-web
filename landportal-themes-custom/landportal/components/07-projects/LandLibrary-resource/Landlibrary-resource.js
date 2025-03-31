//alert('welcome Boss in the landlibrary resource ct');    

/*********** Remove field url and add custom static text and then open it in new window *******/

var resource = jQuery('.library-resource-url a').attr('href');
jQuery('.library-resource-url').append('<span class="resource-url"><a href="'+ resource +'">Download the resource</a></span>');

jQuery('.resource-url').on('click',function(e){
	e.preventDefault();
	window.open(jQuery('.resource-url a').attr('href'));
});


/*********** Remove field url and add custom static text and then open it in new window *******/

var origionally = jQuery('.library-originally a').attr('href');
jQuery('.library-originally').append('<span class="origional-url"><a href="'+ origionally +'">Access original publishing page</a></span>');

jQuery('.origional-url').on('click',function(e){
	e.preventDefault();
	window.open(jQuery('.origional-url a').attr('href'));
});   

var uploadFile = jQuery('.library-upload-file a').attr('href');
//alert(uploadFile);
jQuery('.library-upload-file').append('<span class="upload-url"><a href="'+ uploadFile +'">Download file</a></span>');


/********* Add file size ************/
jQuery(document).ready(function() {
  var fileUrl = jQuery('.upload-url a').attr('href'); // Get the URL of the file

  jQuery.ajax({
    url: fileUrl,
    type: 'HEAD', // Send a HEAD request to get the file headers
    success: function(data, status, xhr) {
      var fileSize = xhr.getResponseHeader('Content-Length'); // Get the file size from the response headers

      // Display the file size
      jQuery('.upload-url').append(' (' + formatFileSize(fileSize) + ')');
    }
  });

  function formatFileSize(size) {
    if (size < 1024) {
      return size + ' B';
    } else if (size < 1048576) {
      return (size / 1024).toFixed(2) + ' KB';
    } else if (size < 1073741824) {
      return (size / 1048576).toFixed(2) + ' MB';
    } else {
      return (size / 1073741824).toFixed(2) + ' GB';
    }
  }
});



jQuery(document).ready(function() {
  jQuery('.upload-url a').on('click', function(e) {
    e.preventDefault(); // Prevent the default navigation behavior

    var fileUrl = jQuery(this).attr('href'); // Get the URL of the file

    // Fetch the file as a blob
    fetch(fileUrl)
      .then(response => response.blob())
      .then(blob => {
        // Create a temporary link element
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.setAttribute('download', ''); // Set the download attribute to trigger the download

        // Simulate a click event on the temporary link
        link.click();

        // Clean up the object URL after a short delay
        setTimeout(function() {
          window.URL.revokeObjectURL(link.href);
        }, 100);    
      });
  });
});
    

/*********** Add acronym to the Corporate Author(s) field *************************/

// jQuery(document).ready(function() {
//   var acronymAdded = false;

//   jQuery('.library-publisher-block-1 .layout--onecol').hover(
//     function() {
//       if (!acronymAdded) {

//        var acronym = jQuery('.library-publisher-block-1 .layout--onecol .block:nth-child(3) div').text();
//         jQuery('.library-publisher-block-1 .layout--onecol .block:nth-child(2) span').append('<span class="acronym-text">'+acronym+'</span>');
//        acronymAdded = true;
//       }
//     },
//     function() {
//       acronymAdded = false;
//       jQuery('.library-publisher-block-1 .layout--onecol .block:nth-child(2) span span').remove();
//     }
//   );
// });
// var ac = ;
// jQuery('.library-publisher-block-1 .layout--onecol').each(function () {
//   jQuery(this).find('.block:nth-child(3) div').each(function () {
//      ac = jQuery(this).text();

//   });
// });
// jQuery('.library-publisher-block-1 .layout--onecol .block:nth-child(2) span').append('<span class="acronym-text">'+ac+'</span>');
// alert(ac);

  
/*********** Add acronym to the Publishe(s) field *************************/

jQuery(document).ready(function() {
  jQuery('.library-publisher-block .layout--onecol').hover(
    function() {
      var spanCount = jQuery('.library-publisher-block .layout--onecol .block:nth-child(2) span').length;
      
      if (spanCount === 1) {
        var acro = jQuery('.library-publisher-block .layout--onecol .block:nth-child(3) div').text();
        jQuery('.library-publisher-block .layout--onecol .block:nth-child(2) span').append('<span class="acronym-text">'+acro+'</span>');
      }
    },
    function() {
      var spanCount = jQuery('.library-publisher-block .layout--onecol .block:nth-child(2) span').length;
      
      if (spanCount === 2) {
        jQuery('.library-publisher-block .layout--onecol .block:nth-child(2) span span').last().remove();
      }
    }
  );
});

/*********** Add acronym to the Data provider field *************************/

jQuery(document).ready(function() {
  jQuery('.library-provider-block .layout--onecol').hover(    
    function() {
      var spanCount = jQuery('.library-provider-block .layout--onecol .block:nth-child(2) span').length;
      
      if (spanCount === 1) {
        var dataAcronym = jQuery('.library-provider-block .layout--onecol .block:nth-child(3) div').text();
        jQuery('.library-provider-block .layout--onecol .block:nth-child(2) span').append('<span class="acronym-text">'+dataAcronym+'</span>');
      }
    },
    function() {
      var spanCount = jQuery('.library-provider-block .layout--onecol .block:nth-child(2) span').length;
      
      if (spanCount === 2) {  
        jQuery('.library-provider-block .layout--onecol .block:nth-child(2) span span').last().remove();
      }
    }
  );
});    
    

/*Add Active Class On Library <li>*/
jQuery('.new_page_node_landlibrary_resource li.landportal-menu__item--library').addClass('landportal-menu__item--active');


/*************  Remove the Related categories text when both are none ***************/
var themes = jQuery('.landLibrary-body-section .related-themes a').text();
var concepts = jQuery('.landLibrary-body-section .related-concepts a').text();

if(themes === '' && concepts === ''){
  jQuery('.related-categories').hide();
}

/*************  Remove the Resource information text when all of them are none ***************/
var date = jQuery('.library-date-of-publication').text();
var iSBN = jQuery('.Library-ISBN').text();
var pages = jQuery('.library-pages').text();
var copyRight = jQuery('.library-copyRight').text();
var resource = jQuery('.library-resource-lang').text();
var license = jQuery('.library-license').text();

if(date === '' && iSBN === '' && pages === '' && copyRight === '' && resource === '' && license === ''){
  jQuery('.library-resource-information').hide();
}

 //alert('hem');  