// core version + navigation, pagination modules:
import Swiper, { Navigation, Pagination } from 'swiper';

// configure Swiper to use modules
Swiper.use([Navigation, Pagination]);

  Drupal.behaviors.projectsCarousel = {
    attach(context) {
      var issueSwiper = new Swiper('.issue-projects-swiper-container', {
        // Optional parameters
        direction: 'horizontal',
        loop: false,
        slidesPerView: 1,
        breakpoints: {
          // when window width is >= 480px
          480: {
            slidesPerView: 3,
            slidesPerGroup: 3,
            spaceBetween: 30,
          },
        },
        // If we need pagination
        pagination: {
          el: '.issue-swiper-pagination',
          clickable: true
        },
      })
    },
  };