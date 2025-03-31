import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('organisations-card', () => {
  it('can render a Organisations card', async () => {
    const { container } = await render(
      join(__dirname, 'organisations-card.twig'),
      loadYaml(join(__dirname, 'organisations-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
