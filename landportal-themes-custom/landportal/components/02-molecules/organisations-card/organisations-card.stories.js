import React from 'react';

import organisationsCard from './organisations-card.twig';

import organisationsData from './organisations-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Organisations Card' };

export const organisationsExample = () => (
  <div dangerouslySetInnerHTML={{ __html: organisationsCard(organisationsData) }} />
);
