import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('news-block', () => {
  it('can render a News block', async () => {
    const { container } = await render(
      join(__dirname, 'news-block.twig'),
      loadYaml(join(__dirname, 'news-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
