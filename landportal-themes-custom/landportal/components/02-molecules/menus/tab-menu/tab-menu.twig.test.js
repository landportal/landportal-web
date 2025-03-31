import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../../.storybook/setupTwig';

setupTwig(Twig);

describe('tab-menu', () => {
  it('can render a tab menu', async () => {
    const { container } = await render(
      join(__dirname, 'tab-menu.twig'),
      loadYaml(join(__dirname, 'tab-menu.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
