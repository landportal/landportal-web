import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('blog-block', () => {
  it('can render a Blog block', async () => {
    const { container } = await render(
      join(__dirname, 'blog-block.twig'),
      loadYaml(join(__dirname, 'blog-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
