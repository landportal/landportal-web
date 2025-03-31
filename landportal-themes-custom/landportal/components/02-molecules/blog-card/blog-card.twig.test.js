import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('blog-card', () => {
  it('can render a Blog card', async () => {
    const { container } = await render(
      join(__dirname, 'blog-card.twig'),
      loadYaml(join(__dirname, 'blog-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
