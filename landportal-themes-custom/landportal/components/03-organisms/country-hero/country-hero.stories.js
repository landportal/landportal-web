import React from 'react';

import countryHero from './country-hero.twig';

import countryHeroData from './country-hero.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Country Hero' };

export const hero = () => (
  <div
    dangerouslySetInnerHTML={{ __html: countryHero({ ...countryHeroData }) }}
  />
);
