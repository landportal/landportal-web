import React from 'react';

import blogCard from './blog-card.twig';

import blogData from './blog-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Blog Card' };

export const blogExample = () => (
  <div dangerouslySetInnerHTML={{ __html: blogCard(blogData) }} />
);
