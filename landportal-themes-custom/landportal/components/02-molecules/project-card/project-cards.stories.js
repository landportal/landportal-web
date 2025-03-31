import React from 'react';

import projectCard from './project-card.twig';

import projectCardData from './project-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Project Card' };

export const projectCardExample = () => (
  <div dangerouslySetInnerHTML={{ __html: projectCard(projectCardData) }} />
);
