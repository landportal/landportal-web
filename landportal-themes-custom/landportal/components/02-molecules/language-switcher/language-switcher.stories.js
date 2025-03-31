import React from 'react';

import languageSwitcher from './language-switcher.twig';

import languageSwitcherData from './language-switcher.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Language Switcher' };

export const languageSwitcherExample = () => (
  <div dangerouslySetInnerHTML={{ __html: languageSwitcher(languageSwitcherData) }} />
);
