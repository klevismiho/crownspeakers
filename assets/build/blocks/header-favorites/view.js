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

// Only allow same-site links and http(s) images from saved data.
const safeUrl = value => {
  try {
    const url = new URL(value, window.location.origin);
    return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
  } catch (error) {
    return '';
  }
};
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
const renderList = block => {
  const list = block.querySelector('.header-favorites__list');
  const empty = block.querySelector('.header-favorites__empty');
  const count = block.querySelector('.header-favorites__count-number');
  count.textContent = String(favorites.length);
  empty.hidden = favorites.length > 0;
  list.replaceChildren();
  favorites.forEach(speaker => {
    const item = document.createElement('li');
    item.className = 'header-favorites__item';
    const link = document.createElement('a');
    link.className = 'header-favorites__link';
    link.href = safeUrl(speaker.url) || '#';
    const image = safeUrl(speaker.image);
    if (image) {
      const img = document.createElement('img');
      img.src = image;
      img.alt = '';
      img.loading = 'lazy';
      link.append(img);
    }
    const name = document.createElement('span');
    name.textContent = speaker.name;
    link.append(name);
    const remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'header-favorites__remove';
    remove.dataset.removeId = speaker.id;
    remove.setAttribute('aria-label', `Remove ${speaker.name} from favorites`);
    remove.textContent = '×';
    item.append(link, remove);
    list.append(item);
  });
};
const blocks = () => document.querySelectorAll('.wp-block-crownspeakers-header-favorites');
const refresh = () => {
  blocks().forEach(renderList);
  updateButtons();

  // Other places that show the count (e.g. the "Favorites" row in the mobile menu).
  document.querySelectorAll('[data-favorites-count]').forEach(element => {
    element.textContent = String(favorites.length);
  });
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

  // Favorite buttons anywhere on the page, and remove buttons in the dropdown.
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

  // Keep other open tabs in sync.
  window.addEventListener('storage', event => {
    if (event.key === STORAGE_KEY) {
      favorites = readFavorites();
      refresh();
    }
  });

  // Dropdown open/close.
  blocks().forEach(block => {
    const toggle = block.querySelector('.header-favorites__toggle');
    const panel = block.querySelector('.header-favorites__panel');
    const setOpen = open => {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.hidden = !open;
    };
    toggle.addEventListener('click', () => {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && !panel.hidden) {
        setOpen(false);
        toggle.focus();
      }
    });
    document.addEventListener('click', event => {
      // Removing an item re-renders the list, so check the click target is still attached.
      if (!panel.hidden && event.target.isConnected && !block.contains(event.target)) {
        setOpen(false);
      }
    });
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map