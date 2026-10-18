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
/*!****************************************!*\
  !*** ./blocks/speaker-details/view.js ***!
  \****************************************/
__webpack_require__.r(__webpack_exports__);
// Collapse long bios behind "Read more"; short bios show in full with no button.
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wp-block-crownspeakers-speaker-details').forEach(block => {
    const bio = block.querySelector('.speaker-details__bio');
    const button = block.querySelector('.speaker-details__more');
    if (!bio || !button) {
      return;
    }
    bio.classList.add('is-collapsed');
    if (bio.scrollHeight <= bio.clientHeight) {
      bio.classList.remove('is-collapsed');
      return;
    }
    button.hidden = false;
    button.addEventListener('click', () => {
      const isOpen = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      button.textContent = isOpen ? 'Read more' : 'Read less';
      bio.classList.toggle('is-collapsed', isOpen);
    });
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map