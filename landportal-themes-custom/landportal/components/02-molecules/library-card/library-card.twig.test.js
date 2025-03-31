import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('library-card', () => {
  it('can render a Library card', async () => {
    const { container } = await render(
      join(__dirname, 'library-card.twig'),
      loadYaml(join(__dirname, 'library-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
