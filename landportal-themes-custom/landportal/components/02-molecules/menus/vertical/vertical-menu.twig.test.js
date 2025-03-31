import { join } from 'path';
import { render, Twig } from 'twig-testing-library';

import loadYaml from '../../../../util/loadYaml';
import { setupTwig, namespaces } from '../../../../.storybook/setupTwig';

setupTwig(Twig);

describe('vertical-menu', () => {
  it('can render a vertical menu', async () => {
    const { container } = await render(
      join(__dirname, 'vertical-menu.twig'),
      loadYaml(join(__dirname, 'vertical-menu.yml')),
      namespaces,
    );

    expect(container).toMatchVerticalSnapshot(`
      <div>











        <ul
          class="vertical-menu"
        >




          <li
            class="vertical-menu__item"
          >





            <a
              class="vertical-menu__link"
              href="#"
            >

            Test

            </a>


          </li>




          <li
            class="vertical-menu__item"
          >





            <a
              class="vertical-menu__link"
              href="#"
            >

            Number 2

            </a>


          </li>




          <li
            class="vertical-menu__item"
          >





            <a
              class="vertical-menu__link"
              href="#"
            >

            Item Number 3

            </a>


          </li>


        </ul>



      </div>
    `);
  });
});
