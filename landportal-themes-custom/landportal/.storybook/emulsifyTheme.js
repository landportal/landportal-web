// Documentation on theming Storybook: https://storybook.js.org/docs/configurations/theming/

import { create } from '@storybook/theming';

export default create({
  base: 'light',
  // Branding
  brandTitle: 'Land Portal Foundation',
  brandUrl: 'https://landportal.org/',
  brandImage:
    'https://landportal.org/sites/landportal.org/files/landportal-logo_-color_0_0.png',
});
