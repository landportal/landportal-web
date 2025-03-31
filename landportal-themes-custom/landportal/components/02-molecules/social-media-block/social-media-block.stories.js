import React from 'react';

import socialBlock from './social-media-block.twig';

import socialBlockData from './social-media-block.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Social media block' };

export const socialBlockExample = () => (
  <div dangerouslySetInnerHTML={{ __html: socialBlock(socialBlockData) }} />
);
