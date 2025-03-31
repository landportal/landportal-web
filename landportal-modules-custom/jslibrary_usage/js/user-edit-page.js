// #node-landlibrary-resource-form #edit-title-field-wrapper .form-text 
// #node-landlibrary-resource-form #edit-title-wrapper .form-text
jQuery(document).ready(function() {
    console.log('Document is ready'); // Check if the document ready function is firing
    let firstInput = jQuery('#node-landlibrary-resource-form #edit-title-field-wrapper .form-text');
    firstInput.on('click',function(){
        alert('first input clicked')
    })
});
