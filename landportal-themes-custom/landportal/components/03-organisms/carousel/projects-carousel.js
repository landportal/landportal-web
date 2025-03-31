// core version + navigation, pagination modules:
import Swiper, { Navigation, Pagination } from 'swiper';

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);

  Drupal.behaviors.projectsCarousel = {
    attach(context) {
      var mySwiper = new Swiper('.projects-swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: false,
        slidesPerView: 1,
        breakpoints: {
          // when window width is >= 480px
          480: {
            slidesPerView: 2,
            slidesPerGroup: 2,
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
