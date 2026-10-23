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
/*!*****************************************!*\
  !*** ./blocks/header-favorites/view.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/**
 * Speaker favorites, saved in the visitor's browser (localStorage).
 *
 * Any button on the page with `data-favorite` toggles a speaker:
 *
 * <button data-favorite data-speaker-id="12" data-speaker-name="Mel Robbins"
 *         data-speaker-url="…" data-speaker-image="…" aria-pressed="false">
 *   <span data-favorite-label>Add to Favorites</span>
 * </button>
 *
 * This script loads on every page because the header-favorites block is in the header.
 * It keeps every `[data-favorites-count]` element up to date (header link, mobile menu row)
 * and handles `data-remove-id` buttons (Favorites page block).
 */

const STORAGE_KEY = 'cs_favorites';
const readFavorites = () => {
  try {
    const saved = JSON.parse(window.localStorage.getItem(STORAGE_KEY));
    return Array.isArray(saved) ? saved : [];
  } catch (error) {
    return [];
  }
};
const writeFavorites = favorites => {
  try {
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(favorites));
  } catch (error) {
    // Storage unavailable (private mode, blocked): favorites last for this page only.
  }
};
let favorites = readFavorites();
const isFavorite = id => favorites.some(speaker => speaker.id === id);
const updateButtons = () => {
  document.querySelectorAll('[data-favorite]').forEach(button => {
    const pressed = isFavorite(button.dataset.speakerId);
    const name = button.dataset.speakerName || 'speaker';
    const label = button.querySelector('[data-favorite-label]');
    button.setAttribute('aria-pressed', pressed ? 'true' : 'false');
    if (label) {
      label.textContent = pressed ? 'Remove from Favorites' : 'Add to Favorites';
    } else {
      button.setAttribute('aria-label', pressed ? `Remove ${name} from favorites` : `Add ${name} to favorites`);
    }
  });
};
const refresh = () => {
  updateButtons();

  // Every place that shows the count: header link, mobile menu row.
  document.querySelectorAll('[data-favorites-count]').forEach(element => {
    element.textContent = String(favorites.length);
  });

  // Let other blocks (e.g. the Favorites page block) react to changes.
  document.dispatchEvent(new CustomEvent('cs:favorites-changed', {
    detail: {
      favorites: [...favorites]
    }
  }));
};
const toggleFavorite = button => {
  const {
    speakerId: id,
    speakerName: name,
    speakerUrl: url,
    speakerImage: image
  } = button.dataset;
  if (!id) {
    return;
  }
  favorites = isFavorite(id) ? favorites.filter(speaker => speaker.id !== id) : [...favorites, {
    id,
    name,
    url,
    image
  }];
  writeFavorites(favorites);
  refresh();
};
document.addEventListener('DOMContentLoaded', () => {
  refresh();

  // Favorite buttons anywhere on the page, and remove buttons (Favorites page block).
  document.addEventListener('click', event => {
    const favoriteButton = event.target.closest('[data-favorite]');
    const removeButton = event.target.closest('[data-remove-id]');
    if (favoriteButton) {
      event.preventDefault();
      toggleFavorite(favoriteButton);
    }
    if (removeButton) {
      favorites = favorites.filter(speaker => speaker.id !== removeButton.dataset.removeId);
      writeFavorites(favorites);
      refresh();
    }
  });

  // Other scripts can ask for a re-sync after adding favorite buttons (e.g. carousel tabs).
  document.addEventListener('cs:favorites-refresh', refresh);

  // Keep other open tabs in sync.
  window.addEventListener('storage', event => {
    if (event.key === STORAGE_KEY) {
      favorites = readFavorites();
      refresh();
    }
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map