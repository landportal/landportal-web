

var arr = [ "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49", "50" ];
jQuery.each( arr, function( i, val ) {

// field_related_resources code Start here...       
    jQuery("div[id^='edit-field-related-resources-"+val+"-data-container2'] h1").click(function(){ 
        jQuery("input[id^='edit-field-related-resources-"+val+"-fullname']").removeAttr('value');
        jQuery("input[id^='edit-field-related-resources-"+val+"-swid']").removeAttr('value');
        jQuery(this).parent().parent().parent().css('display','none');

    });
    jQuery(document).on('click', "div[id^='edit-field-related-resources-"+val+"-data-container'] h2", function(){
        jQuery("input[id^='edit-field-related-resources-"+val+"-fullname']").val('');
        jQuery("input[id^='edit-field-related-resources-"+val+"-swid']").val('');
        jQuery(this).parent().parent().parent().css('display','none');
    });
// field_related_resources code End....

// field_related_donor start here...
    jQuery("div[id^='edit-field-related-donor-"+val+"-data-container2'] h1").click(function(){ 
        jQuery("input[id^='edit-field-related-donor-"+val+"-fullname']").removeAttr('value');
        jQuery("input[id^='edit-field-related-donor-"+val+"-swid']").removeAttr('value');
        jQuery(this).parent().parent().parent().css('display','none');

    });
    jQuery(document).on('click', "div[id^='edit-field-related-donor-"+val+"-data-container'] h2", function(){
        jQuery("input[id^='edit-field-related-donor-"+val+"-fullname']").val('');
        jQuery("input[id^='edit-field-related-donor-"+val+"-swid']").val('');
        jQuery(this).parent().parent().parent().css('display','none');
    });
// field_related_donor end here....

// field_related_provider start here...
    jQuery("div[id^='edit-field-related-provider-"+val+"-data-container2'] h1").click(function(){ 
        jQuery("input[id^='edit-field-related-provider-"+val+"-fullname']").removeAttr('value');
        jQuery("input[id^='edit-field-related-provider-"+val+"-swid']").removeAttr('value');
        jQuery(this).parent().parent().parent().css('display','none');

    });
    jQuery(document).on('click', "div[id^='edit-field-related-provider-"+val+"-data-container'] h2", function(){
        jQuery("input[id^='edit-field-related-provider-"+val+"-fullname']").val('');
        jQuery("input[id^='edit-field-related-provider-"+val+"-swid']").val('');
        jQuery(this).parent().parent().parent().css('display','none');
    });
// field_related_provider end here....

// field_related_impact code start here...

    jQuery("div[id^='edit-field-related-impact-"+val+"-data-container2'] h1").click(function(){ 
        jQuery("input[id^='edit-field-related-impact-"+val+"-fullname']").removeAttr('value');
        jQuery("input[id^='edit-field-related-impact-"+val+"-swid']").removeAttr('value');
        jQuery(this).parent().parent().parent().css('display','none');

    });
    jQuery(document).on('click', "div[id^='edit-field-related-impact-"+val+"-data-container'] h2", function(){
        jQuery("input[id^='edit-field-related-impact-"+val+"-fullname']").val('');
        jQuery("input[id^='edit-field-related-impact-"+val+"-swid']").val('');
        jQuery(this).parent().parent().parent().css('display','none');
    });

// field_related_impact code End here.....

// field_orgnization code start here...

    jQuery("div[id^='edit-field-related-organization-"+val+"-data-container2'] h1").click(function(){ 
        jQuery("input[id^='edit-field-related-organization-"+val+"-fullname']").removeAttr('value');
        jQuery("input[id^='edit-field-related-organization-"+val+"-swid']").removeAttr('value');
        jQuery(this).parent().parent().parent().css('display','none');
    });
    jQuery(document).on('click', "div[id^='edit-field-related-organization-"+val+"-data-container'] h2", function(){
        jQuery("input[id^='edit-field-related-organization-"+val+"-fullname']").val('');
        jQuery("input[id^='edit-field-related-organization-"+val+"-swid']").val('');
        jQuery(this).parent().parent().parent().css('display','none');
    });

// field_organization code end here....
    
});

var item_field_related_impact_val = "";
var item_field = 'item--field';
jQuery("table#field-related-impact-values tbody tr").each(function( i, val ) {
    item_field_related_impact_val = jQuery(this).children("td").children(".form-"+item_field+"-related-impact-" + i + "-swid").children(".form-text").val();
    if(item_field_related_impact_val > 1){
        jQuery(this).children("td").children(".form-"+item_field+"-related-impact-" + i + "-data").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-impact-" + i + "-regional").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-impact-" + i + "-data-type").css('display','none');
    }
});

var item_field_related_organization_val = "";
jQuery("table#field-related-organization-values tbody tr").each(function( i, val ) {
    item_field_related_organization_val = jQuery(this).children("td").children(".form-"+item_field+"-related-organization-" + i + "-swid").children(".form-text").val();
    if(item_field_related_organization_val > 1){
        jQuery(this).children("td").children(".form-"+item_field+"-related-organization-" + i + "-data").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-organization-" + i + "-regional").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-organization-" + i + "-data-type").css('display','none');
    }
});

var item_field_related_resources_val = "";
jQuery("table#field-related-resources-values tbody tr").each(function( i, val ) {
    item_field_related_resources_val = jQuery(this).children("td").children(".form-"+item_field+"-related-resources-" + i + "-swid").children(".form-text").val();
    if(item_field_related_resources_val > 1){
        jQuery(this).children("td").children(".form-"+item_field+"-related-resources-" + i + "-data").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-resources-" + i + "-regional").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-resources-" + i + "-data-type").css('display','none');
    }
});

var item_field_related_donor_val = "";
jQuery("table#field-related-donor-values tbody tr").each(function( i, val ) {
    item_field_related_donor_val = jQuery(this).children("td").children(".form-"+item_field+"-related-donor-" + i + "-swid").children(".form-text").val();
    if(item_field_related_donor_val > 1){
        jQuery(this).children("td").children(".form-"+item_field+"-related-donor-" + i + "-data").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-donor-" + i + "-regional").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-donor-" + i + "-data-type").css('display','none');
    }
});

var item_field_related_provider_val = "";
jQuery("table#field-related-provider-values tbody tr").each(function( i, val ) {
    item_field_related_provider_val = jQuery(this).children("td").children(".form-"+item_field+"-related-provider-" + i + "-swid").children(".form-text").val();
    if(item_field_related_provider_val > 1){
        jQuery(this).children("td").children(".form-"+item_field+"-related-provider-" + i + "-data").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-provider-" + i + "-regional").css('display','none');
        jQuery(this).children("td").children(".form-"+item_field+"-related-provider-" + i + "-data-type").css('display','none');
    }
});



////////////// #edit-field-value-decimal-0-value code start here///////////////
var addDot = '.00';
var projectValue = '';
jQuery('#edit-field-value-decimal-0-value').keyup(function(){
     projectValue = jQuery(this).val();
});

jQuery('#edit-submit').click(function(){
    if(projectValue == ''){

    }
    else{
        if(projectValue.indexOf(".") != -1){
        }
        else{
            jQuery('#edit-field-value-decimal-0-value').val(projectValue+addDot);
        }
    }
});