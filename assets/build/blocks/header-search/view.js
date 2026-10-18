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
/*!**************************************!*\
  !*** ./blocks/header-search/view.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wp-block-crownspeakers-header-search').forEach(block => {
    const toggle = block.querySelector('.header-search__toggle');
    const panel = block.querySelector('.header-search__panel');
    const input = block.querySelector('input[type="search"]');
    if (!toggle || !panel) {
      return;
    }
    const setOpen = open => {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.hidden = !open;
      block.classList.toggle('is-open', open);
      if (open) {
        input?.focus();
      }
    };
    toggle.addEventListener('click', () => {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // Close with Escape and return focus to the icon.
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && !panel.hidden) {
        setOpen(false);
        toggle.focus();
      }
    });

    // Close when clicking outside the icon and the panel.
    document.addEventListener('click', event => {
      if (!panel.hidden && !block.contains(event.target)) {
        setOpen(false);
      }
    });
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map