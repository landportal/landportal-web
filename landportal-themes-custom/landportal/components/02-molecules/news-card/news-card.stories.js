import React from 'react';

import newsCard from './news-card.twig';

import newsData from './news-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/News Card' };

export const newsExample = () => (
  <div dangerouslySetInnerHTML={{ __html: newsCard(newsData) }} />
);
