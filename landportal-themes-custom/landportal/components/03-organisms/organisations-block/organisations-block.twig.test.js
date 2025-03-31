import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('organisations-block', () => {
  it('can render an Organisations block', async () => {
    const { container } = await render(
      join(__dirname, 'organisations-block.twig'),
      loadYaml(join(__dirname, 'organisations-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
