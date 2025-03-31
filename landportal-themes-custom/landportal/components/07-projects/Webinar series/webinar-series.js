//alert('hello boss! welcome to the webinar page. hmm');

// var date = jQuery('.date-field div div:nth-child(2)').text();
// alert(date);

jQuery(document).ready(function() {
  jQuery('.date-field').each(function() {
    var dateText = jQuery(this).find('div:nth-child(2)').text().trim();
    var dates = dateText.split(' to ');

    var startDate = dates[0].trim();
    var endDate = dates[1].trim();

    if (startDate === endDate) {   
      jQuery(this).find('div:nth-child(2)').text(startDate);
    }
  });
});
