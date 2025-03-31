import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../util/loadYaml';
import { setupTwig } from '../../../.storybook/setupTwig';

setupTwig(Twig);

describe('tooltips', () => {
  it('can render a tooltip', async () => {
    const { container } = await render(
      join(__dirname, 'tooltips.twig'),
      loadYaml(join(__dirname, 'tooltips.yml')),
    );

    expect(container).toMatchInlineSnapshot(`
      <div>



        <div
          class="tooltips"
        >


          <iframe
            allowfullscreen="allowfullscreen"
            frameborder="0"
            height="480"
            src="https://www.youtube.com/embed/YRnVnlhjOBs?autoplay=0&start=0"
            title="landportal Video"
            width="854"
          />


        </div>


      </div>
    `);
  });

  it('can render a full tooltips', async () => {
    const { container } = await render(
      join(__dirname, 'tooltips.twig'),
      loadYaml(join(__dirname, 'tooltips-full.yml')),
    );

    expect(container).toMatchInlineSnapshot(`
      <div>



        <div
          class="tooltips tooltips--full"
        >


          <iframe
            allowfullscreen="allowfullscreen"
            frameborder="0"
            height="480"
            src="https://www.youtube.com/embed/YRnVnlhjOBs?autoplay=0&start=0"
            title="landportal Video"
            width="854"
          />


        </div>


      </div>
    `);
  });
});
