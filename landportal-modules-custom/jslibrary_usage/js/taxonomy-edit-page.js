// alert('hi');

// enter value to desable name field when user enter to Layer name field
jQuery(document).ready(function() {

  var layerNameField = jQuery('#taxonomy-term-layer-form.taxonomy-term-layer-form .field--name-field-name input');
  var nameField = jQuery('#taxonomy-term-layer-form.taxonomy-term-layer-form .field--name-name input');

  layerNameField.on('input', function() {
    var layerName = layerNameField.val();
    
    nameField.val(layerName);
  });
});
