import React from 'react';

import organisationsBlock from './organisations-block.twig';

import organisationsBlockData from './organisations-block.yml';
import organisationsCardData from "./organisations-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Organisations Block' };

export const organisations = () => (
  <div
    dangerouslySetInnerHTML={{ __html: organisationsBlock({ ...organisationsBlockData, ...organisationsCardData }) }}
  />
);
