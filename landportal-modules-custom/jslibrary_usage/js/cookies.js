document.addEventListener('DOMContentLoaded', function() {
    var slideIndex = 1;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    var prevButton = document.querySelector('.prev');
    var nextButton = document.querySelector('.next');
    var dotButtons = document.querySelectorAll('.dot');

    showSlides(slideIndex); // Initial display of slides

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        if (n > slides.length) {
            slideIndex = 1;
        }
        if (n < 1) {
            slideIndex = slides.length;
        }

        // Hide all slides
        Array.from(slides).forEach(function(slide) {
            slide.style.display = "none";
        });

        // Deactivate all dots
        Array.from(dots).forEach(function(dot) {
            dot.classList.remove("active");
        });

        // Display the current slide and activate the corresponding dot
        if (slides.length > 0 && dots.length > 0) {
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].classList.add("active");
        }
    }

    // Event listeners for navigation arrows
    if (prevButton && nextButton) {
        prevButton.addEventListener('click', function() {
            plusSlides(-1);
        });

        nextButton.addEventListener('click', function() {
            plusSlides(1);
        });
    }

    // Event listener for navigation dots
    if (dotButtons) {
        dotButtons.forEach(function(dot, index) {
            dot.addEventListener('click', function() {
                currentSlide(index + 1);
            });
        });
    }
});


jQuery(document).ready(function() {
    jQuery('#block-useraccountmenu .inline-menu--account, #block-useraccountmenu .inline-menu--account *').mouseenter(function() {
        jQuery('#block-userlogin').css('display', 'block');
    });
    jQuery('#block-useraccountmenu .inline-menu--account').mouseleave(function() {
        jQuery('#block-userlogin').css('display', 'none');
    });

    jQuery('#block-userlogin').mouseenter(function() {
        jQuery(this).css('display', 'block');
    });
    jQuery('#block-userlogin').mouseleave(function() {
        jQuery(this).css('display', 'none');
    });
});


// remove org type filter from concepts view ///
jQuery(document).ready(function() {
    // Iterate over each .voc-name-img element
    jQuery('.voc-name-img').each(function() {
        // Check if the span inside this .voc-name-img is empty
        if (jQuery(this).find('span').is(':empty')) {
            // Hide the associated .js-form-item.form-item element
            jQuery('#views-exposed-form-thematic-content-page-1 .js-form-item:nth-child(2)').hide();
            jQuery('#views-exposed-form-thematic-content-block-1 .js-form-item').hide();
            // alert('hamza');
        }
    });
});



///  decline button click collaspe to bottom cookie popup ///
// jQuery(document).ready(function() {
//     jQuery('.eu-cookie-compliance-buttons .button--primary').click(function(){
//         jQuery('.sliding-popup-top').css('height', '100%');
//     });
// });


// function() {
//   Drupal.eu_cookie_compliance.setStatus(0);
//   $('#sliding-popup').animate({
//     bottom: $('#sliding-popup').outerHeight() * -1
//   }).animate({
//     height: 'toggle'
//   }).trigger('eu_cookie_compliance_popup_close');
// }


/// hide related content text if no content show ///////////////
jQuery(document).ready(function() {
    var relatedContent = jQuery('.open-up-guide .views-row .views-field-field-noderef').text();
    if(relatedContent == ''){
        jQuery('.open-up-guide').hide();
    }
});

/// hide partners text if no content show (blog post)  ///////////////
jQuery(document).ready(function() {
    var partnersField = jQuery('.organiers-field .views-row .views-field-field-orgref').text();
    if(partnersField == ''){
        jQuery('.organiers-field').hide();
    }
});


/// hide user block if no content show (blog post) ///////////////
jQuery(document).ready(function() {
    jQuery('.blog-post-user-image .views-row .user-first-last-name').each(function() {
        var userBlock = jQuery(this).text().trim();
        // alert(userBlock);
        if(userBlock == '') {
            jQuery(this).hide();
        }
    }); 
});




//alert('hmm'); 