// core version + navigation, pagination modules:
import Swiper, { Navigation, Pagination } from 'swiper';

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);

  Drupal.behaviors.carousel = {
    attach(context) {
      var mySwiper = new Swiper('.swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: false,
        slidesPerView: 2,
        slidesPerGroup: 2,
        spaceBetween: 30,

        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
          type: 'bullets',
          clickable: true
        },

        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
          el: '.swiper-scrollbar',
        },
      })
    },
  };
