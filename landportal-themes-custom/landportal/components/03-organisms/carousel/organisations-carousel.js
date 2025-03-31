// core version + navigation, pagination modules:
import Swiper, { Navigation, Pagination } from 'swiper';

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);

  Drupal.behaviors.organisationsCarousel = {
    attach(context) {
      var mySwiper = new Swiper('.organisations-swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: false,
        slidesPerView: 3,
        spaceBetween: 10,
        breakpoints: {
          // when window width is >= 480px
          992: {
            slidesPerView: 7,
            spaceBetween: 30,
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