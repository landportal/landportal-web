import React from 'react';

import projectsBlock from './projects-block.twig';

import projectsBlockData from './projects-block.yml';
import projectsCardData from "./projects-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Projects Block' };

export const projects = () => (
  <div
    dangerouslySetInnerHTML={{ __html: projectsBlock({ ...projectsBlockData, ...projectsCardData }) }}
  />
);
