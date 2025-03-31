import React from 'react';

import blogBlock from './blog-block.twig';

import blogBlockData from './blog-block.yml';
import blogCardData from "./blog-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Blog Block' };

export const blog = () => (
  <div
    dangerouslySetInnerHTML={{ __html: blogBlock({ ...blogBlockData, ...blogCardData }) }}
  />
);
