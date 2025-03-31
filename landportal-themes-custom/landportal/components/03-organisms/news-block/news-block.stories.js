import React from 'react';

import newsBlock from './news-block.twig';

import newsBlockData from './news-block.yml';
import newsCardData from "./news-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/News Block' };

export const news = () => (
  <div
    dangerouslySetInnerHTML={{ __html: newsBlock({ ...newsBlockData, ...newsCardData }) }}
  />
);
alert('Hello');