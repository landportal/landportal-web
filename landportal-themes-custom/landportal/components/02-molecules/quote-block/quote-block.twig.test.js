import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('quote-block', () => {
  it('can render a Quote block', async () => {
    const { container } = await render(
      join(__dirname, 'quote-block.twig'),
      loadYaml(join(__dirname, 'quote-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
