import React from 'react';

import indicatorsBlock from './indicators.twig';
import indicatorBlock from './indicator.twig';

import indicatorsData from './indicators.yml';
import indicatorData from './indicator.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Indicators Block' };

export const indicatorExample = () => (
  <div
    dangerouslySetInnerHTML={{ __html: indicatorBlock({ indicatorData }) }}
  />
);

export const indicatorsExample = () => (
  <div
    dangerouslySetInnerHTML={{ __html: indicatorsBlock({ ...indicatorsData, ...indicatorData }) }}
  />
);
