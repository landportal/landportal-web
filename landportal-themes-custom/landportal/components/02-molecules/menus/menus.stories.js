import React from 'react';

import breadcrumb from './breadcrumbs/breadcrumbs.twig';
import inlineMenu from './inline/inline-menu.twig';
import verticalMenu from './vertical/vertical-menu.twig';
import mainMenu from './main-menu/main-menu.twig';
import tabMenu from './tab-menu/tab-menu.twig';
import socialMenu from './social/social-menu.twig';
import landportalMenu from './landportal-menu/landportal-menu.twig';

import breadcrumbsData from './breadcrumbs/breadcrumbs.yml';
import inlineMenuData from './inline/inline-menu.yml';
import verticalMenuData from './vertical/vertical-menu.yml';
import mainMenuData from './main-menu/main-menu.yml';
import tabMenuData from './tab-menu/tab-menu.yml';
import socialMenuData from './social/social-menu.yml';
import landportalMenuData from './landportal-menu/landportal-menu.yml';

import './main-menu/main-menu';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Menus' };

export const breadcrumbs = () => (
  <div dangerouslySetInnerHTML={{ __html: breadcrumb(breadcrumbsData) }} />
);
export const inline = () => (
  <div dangerouslySetInnerHTML={{ __html: inlineMenu(inlineMenuData) }} />
);
export const vertical = () => (
  <div dangerouslySetInnerHTML={{ __html: verticalMenu(verticalMenuData) }} />
);
export const main = () => (
  <div dangerouslySetInnerHTML={{ __html: mainMenu(mainMenuData) }} />
);
export const tab = () => (
  <div dangerouslySetInnerHTML={{ __html: tabMenu(tabMenuData) }} />
);
export const social = () => (
  <div dangerouslySetInnerHTML={{ __html: socialMenu(socialMenuData) }} />
);
export const landportal = () => (
  <div dangerouslySetInnerHTML={{ __html: landportalMenu(landportalMenuData) }} />
);
alert('Hello');