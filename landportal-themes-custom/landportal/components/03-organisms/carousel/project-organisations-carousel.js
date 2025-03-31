// core version + navigation, pagination modules:
import Swiper, { Navigation, Pagination } from 'swiper';

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);

  Drupal.behaviors.organisationsCarousel = {
    attach(context) {
      var mySwiper = new Swiper('.project-organisations-swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: false,
        slidesPerView: 1,
        spaceBetween: 1,
        breakpoints: {
          // when window width is >= 480px
          992: {
            slidesPerView: 1,
            spaceBetween: 1,
          },
        },
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },
      })
    },
  };