import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('events-card', () => {
  it('can render an events card', async () => {
    const { container } = await render(
      join(__dirname, 'events-card.twig'),
      loadYaml(join(__dirname, 'events-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
