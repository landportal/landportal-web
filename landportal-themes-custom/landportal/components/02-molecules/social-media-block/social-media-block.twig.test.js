import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('social-media-block', () => {
  it('can render a Social media block', async () => {
    const { container } = await render(
      join(__dirname, 'social-media-block.twig'),
      loadYaml(join(__dirname, 'social-media-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
