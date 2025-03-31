import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('news-card', () => {
  it('can render a News card', async () => {
    const { container } = await render(
      join(__dirname, 'news-card.twig'),
      loadYaml(join(__dirname, 'news-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
