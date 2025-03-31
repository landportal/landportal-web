

jQuery(document).ready(function() {
    jQuery('.form-element--type-select-multiple option[value="_none"]').remove();
    jQuery('.field--type-entity-reference select').select2();
    jQuery('#user-register-form .field--type-entity-reference select').select2();
    jQuery('#user-register-form .field--type-entity-reference select').on('click',function(){
       console.log('clicked')
    })
});

jQuery(document).ready(function() {   
    let firstInput = jQuery('#node-landlibrary-resource-form #edit-title-field-wrapper .form-text');
    let secondInput=jQuery('#node-landlibrary-resource-form #edit-title-wrapper .form-text')
    let submitBtn=jQuery('#node-landlibrary-resource-form #edit-actions #edit-submit')

    submitBtn.on('click',function(){
        let firstInputValue = firstInput.val();
        secondInput.val(firstInputValue);
    })
});



