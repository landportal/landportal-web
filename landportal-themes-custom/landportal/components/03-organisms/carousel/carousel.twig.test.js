import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('carousel', () => {
  it('can render a carousel', async () => {
    const { container } = await render(
      join(__dirname, 'carousel.twig'),
      loadYaml(join(__dirname, 'carousel.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

  it('can render a carousel', async () => {
    const { container } = await render(
      join(__dirname, 'carousel.twig'),
      loadYaml(join(__dirname, 'carousel-projects.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
