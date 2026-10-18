/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	// The require scope
/******/ 	const __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
let __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./blocks/navigation/view.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wp-block-crownspeakers-navigation').forEach(navigation => {
    const menuToggle = navigation.querySelector('.wp-block-crownspeakers-navigation__toggle');
    const panel = navigation.querySelector('.wp-block-crownspeakers-navigation__panel');
    const header = navigation.closest('.site-header');
    const desktop = window.matchMedia('(min-width: 1024px)');

    // Mobile panel: fills the screen below the header and locks page scroll.
    const setMenuOpen = open => {
      if (!menuToggle || !panel) {
        return;
      }
      if (open && header) {
        const top = Math.max(0, header.getBoundingClientRect().bottom);
        document.documentElement.style.setProperty('--cs-menu-top', `${top}px`);
      }
      menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      menuToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      panel.classList.toggle('is-open', open);
      header?.classList.toggle('is-menu-open', open);
      document.documentElement.classList.toggle('has-menu-open', open);
    };
    if (menuToggle && panel) {
      menuToggle.addEventListener('click', () => {
        setMenuOpen(menuToggle.getAttribute('aria-expanded') !== 'true');
      });
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && panel.classList.contains('is-open')) {
          setMenuOpen(false);
          menuToggle.focus();
        }
      });

      // Leaving the mobile layout closes the panel.
      desktop.addEventListener('change', event => {
        if (event.matches) {
          setMenuOpen(false);
        }
      });

      // "Favorites" row: close the panel and open the header favorites dropdown.
      // Opened after this tap finishes bubbling, so the dropdown's "click outside" handler doesn't close it.
      panel.querySelector('[data-open-favorites]')?.addEventListener('click', () => {
        setMenuOpen(false);
        window.setTimeout(() => header?.querySelector('.header-favorites__toggle')?.click(), 0);
      });
    }

    // Regular dropdowns (the megamenu has its own handling below).
    navigation.querySelectorAll('.wp-block-crownspeakers-navigation__submenu-toggle:not([data-mega-toggle])').forEach(button => {
      button.addEventListener('click', event => {
        event.preventDefault();
        const parent = button.closest('.menu-item-has-children');
        const isOpen = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !isOpen);
        parent.classList.toggle('is-open');
      });
    });

    // Megamenu: the parent link and its chevron both toggle the panel.
    const megaItems = navigation.querySelectorAll('.menu-item--mega');
    const setMegaOpen = (item, open) => {
      item.classList.toggle('is-open', open);
      item.querySelectorAll('[data-mega-toggle]').forEach(toggle => {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    };
    const closeAllMega = () => megaItems.forEach(item => setMegaOpen(item, false));
    megaItems.forEach(item => {
      item.querySelectorAll('[data-mega-toggle]').forEach(toggle => {
        toggle.addEventListener('click', event => {
          event.preventDefault();
          const open = !item.classList.contains('is-open');
          closeAllMega();
          setMegaOpen(item, open);
        });
      });
    });
    if (megaItems.length) {
      document.addEventListener('keydown', event => {
        const openItem = navigation.querySelector('.menu-item--mega.is-open');
        if (event.key === 'Escape' && openItem) {
          closeAllMega();
          openItem.querySelector('a[data-mega-toggle]')?.focus();
        }
      });
      document.addEventListener('click', event => {
        if (!event.target.closest('.menu-item--mega')) {
          closeAllMega();
        }
      });
    }
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map