import React from 'react';

import libraryBlock from './library-block.twig';

import libraryBlockData from './library-block.yml';
import libraryCardData from "./library-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Library Block' };

export const library = () => (
  <div
    dangerouslySetInnerHTML={{ __html: libraryBlock({ ...libraryBlockData, ...libraryCardData }) }}
  />
);
