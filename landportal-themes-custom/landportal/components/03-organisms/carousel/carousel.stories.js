import React from 'react';

import carousel from './carousel.twig';

import carouselData from './carousel.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Carousels' };

export const defaultCarousel = () => (
  <div dangerouslySetInnerHTML={{ __html: carousel(carouselData) }} />
);
alert('Hello');