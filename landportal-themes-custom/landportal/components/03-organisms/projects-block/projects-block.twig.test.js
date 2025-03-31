import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('projects-block', () => {
  it('can render a Projects block', async () => {
    const { container } = await render(
      join(__dirname, 'projects-block.twig'),
      loadYaml(join(__dirname, 'projects-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
