import React from 'react';

import eventsCard from './events-card.twig';

import eventsData from './events-card.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Events Card' };

export const eventsExample = () => (
  <div dangerouslySetInnerHTML={{ __html: eventsCard(eventsData) }} />
);
