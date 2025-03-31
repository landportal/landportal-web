import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('portfolio-card', () => {
  it('can render a Portfolio card', async () => {
    const { container } = await render(
      join(__dirname, 'portfolio-card.twig'),
      loadYaml(join(__dirname, 'portfolio-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
