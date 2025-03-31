import React from 'react';

import quoteBlock from './quote-block.twig';

import quoteBlockData from './quote-block.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Quote block' };

export const quoteBlockExample = () => (
  <div dangerouslySetInnerHTML={{ __html: quoteBlock(quoteBlockData) }} />
);
