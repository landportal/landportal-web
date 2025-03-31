import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../../.storybook/setupTwig';

setupTwig(Twig);

describe('landportal-menu', () => {
  it('can render a tab menu', async () => {
    const { container } = await render(
      join(__dirname, 'landportal-menu.twig'),
      loadYaml(join(__dirname, 'landportal-menu.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
