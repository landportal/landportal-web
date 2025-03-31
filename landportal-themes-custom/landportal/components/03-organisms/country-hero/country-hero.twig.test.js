import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('country-hero', () => {
  it('can render a Country hero', async () => {
    const { container } = await render(
      join(__dirname, 'country-hero.twig'),
      loadYaml(join(__dirname, 'country-hero.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
