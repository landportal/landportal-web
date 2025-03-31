import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('project-card', () => {
  it('can render a Project card', async () => {
    const { container } = await render(
      join(__dirname, 'project-card.twig'),
      loadYaml(join(__dirname, 'project-card.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
