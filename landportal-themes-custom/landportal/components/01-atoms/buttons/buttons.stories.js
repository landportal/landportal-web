import React from 'react';

import Button from './react/Button.component';

import button from './twig/button.twig';

import buttonData from './twig/button.yml';
import buttonCountriesData from './twig/button-countries.yml';
import buttonIssuesData from './twig/button-issues.yml';

/**
 * Storybook Definition.
 */
export default {
  component: Button,
  title: 'Atoms/Button',
};

export const react = () => <Button>React Button</Button>;

export const buttonExample = () => (
  <>
    <h3>Default Button:</h3>
    <div dangerouslySetInnerHTML={{ __html: button(buttonData) }} />
    <h3>Countries Button:</h3>
    <div dangerouslySetInnerHTML={{ __html: button(buttonCountriesData) }} />
    <h3>Issues Button:</h3>
    <div dangerouslySetInnerHTML={{ __html: button(buttonIssuesData) }} />
  </>
);
