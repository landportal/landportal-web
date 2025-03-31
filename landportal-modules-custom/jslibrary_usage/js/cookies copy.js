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
    // Reset slide index if out of bounds
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
