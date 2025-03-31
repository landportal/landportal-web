import React from 'react';

import tooltips from './tooltips.twig';

import tooltipsData from './tooltips.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Atoms/Tooltips' };

export const tooltip = () => (
  <div dangerouslySetInnerHTML={{ __html: tooltips(tooltipsData) }} />
);
