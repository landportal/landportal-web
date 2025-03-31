import React from 'react';

import portfolioCard from './portfolio-card.twig';

import portfolioData from './portfolio-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Portfolio Card' };

export const portfolioExample = () => (
  <div dangerouslySetInnerHTML={{ __html: portfolioCard(portfolioData) }} />
);
