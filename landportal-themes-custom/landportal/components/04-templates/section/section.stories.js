import React from 'react';

import onecolTwig from './onecol.twig';
import threecolTwig from './threecol.twig';
import sidebarTwig from './sidebar.twig';

import sectionData from './section.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Templates/Section' };

export const onecol = () => (
  <div
    dangerouslySetInnerHTML={{
      __html: onecolTwig(sectionData),
    }}
  />
);

export const threecol = () => (
  <div
    dangerouslySetInnerHTML={{
      __html: threecolTwig(sectionData),
    }}
  />
);

export const sidebar = () => (
  <div
    dangerouslySetInnerHTML={{
      __html: sidebarTwig(sectionData),
    }}
  />
);
