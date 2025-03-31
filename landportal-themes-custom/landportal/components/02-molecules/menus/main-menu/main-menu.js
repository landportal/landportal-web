Drupal.behaviors.mainMenu = {
  attach(context) {
    const toggleExpand = context.getElementById('toggle-expand');
    const menu = context.getElementById('main-nav');
    const langSwitcher = context.getElementById('block-languageswitcher');
    const accountMenu = context.getElementById('block-useraccountmenu').querySelector('.inline-menu--account');

    if (menu) {
      const expandMenu = menu.getElementsByClassName('expand-sub');

      // Mobile Menu Show/Hide.
      toggleExpand.addEventListener('click', (e) => {
        menu.classList.toggle('main-nav--open');
        langSwitcher.classList.toggle('main-nav--open');
        accountMenu.classList.toggle('main-nav--open');
        e.preventDefault();
      });

      // Expose mobile sub menu on click.
      Array.from(expandMenu).forEach((item) => {
        item.addEventListener('click', (e) => {
          const menuItem = e.currentTarget;
          const subMenu = menuItem.nextElementSibling;

          menuItem.classList.toggle('expand-sub--open');
          subMenu.classList.toggle('main-menu--sub-open');
        });
      });
    }

    // Open the language switch on click.
    langSwitcher.addEventListener('click', (e) => {
      const switcher = e.currentTarget;
      const links = switcher.querySelector('.links');
      links.classList.toggle('language-switcher--open');
    });
  },
};
