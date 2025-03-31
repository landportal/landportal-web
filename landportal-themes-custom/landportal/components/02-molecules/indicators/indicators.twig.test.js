import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('indicators-block', () => {
  it('can render an Indicators block', async () => {
    const { container } = await render(
      join(__dirname, 'indicators.twig'),
      loadYaml(join(__dirname, 'indicators.yml')),
      namespaces,
    );

    expect(container).toMatchSnapshot();
  });
});
