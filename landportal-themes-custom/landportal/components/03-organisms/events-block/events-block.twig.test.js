import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('events-block', () => {
  it('can render an Events block', async () => {
    const { container } = await render(
      join(__dirname, 'events-block.twig'),
      loadYaml(join(__dirname, 'events-block.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });

});
