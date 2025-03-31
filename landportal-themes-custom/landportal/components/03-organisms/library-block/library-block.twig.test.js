import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('library-block', () => {
  it('can render a Library block', async () => {
    const { container } = await render(
      join(__dirname, 'library-block.twig'),
      loadYaml(join(__dirname, 'library-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
