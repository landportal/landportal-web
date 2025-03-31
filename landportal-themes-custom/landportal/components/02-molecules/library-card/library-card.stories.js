import React from 'react';

import libraryCard from './library-card.twig';

import libraryData from './library-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Library Card' };

export const libraryExample = () => (
  <div dangerouslySetInnerHTML={{ __html: libraryCard(libraryData) }} />
);
