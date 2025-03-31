import React from 'react';

import eventsBlock from './events-block.twig';

import eventsBlockData from './events-block.yml';
import eventsCardData from "./events-cards.yml";

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Events Block' };

export const events = () => (
  <div
    dangerouslySetInnerHTML={{ __html: eventsBlock({ ...eventsBlockData, ...eventsCardData }) }}
  />
);
