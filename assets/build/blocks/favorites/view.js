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
/*!**********************************!*\
  !*** ./blocks/favorites/view.js ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/**
 * Favorites page: renders the visitor's saved speakers.
 *
 * Saved favorites live in localStorage (`cs_favorites`, managed by the header-favorites block).
 * They only hold IDs + a thumbnail, so names, photos and taglines are fetched from
 * /wp-json/crownspeakers/v1/speakers. Removing a speaker uses `data-remove-id`, which the
 * header-favorites script handles; it then fires `cs:favorites-changed` and this block re-renders.
 */

const STORAGE_KEY = 'cs_favorites';
const readFavoriteIds = () => {
  try {
    const saved = JSON.parse(window.localStorage.getItem(STORAGE_KEY));
    return Array.isArray(saved) ? saved.map(speaker => String(speaker.id)) : [];
  } catch (error) {
    return [];
  }
};
const safeUrl = value => {
  try {
    const url = new URL(value, window.location.origin);
    return ['http:', 'https:'].includes(url.protocol) ? url.href : '';
  } catch (error) {
    return '';
  }
};
const checkIcon = () => {
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('width', '12');
  svg.setAttribute('height', '12');
  svg.setAttribute('viewBox', '0 0 12 12');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('aria-hidden', 'true');
  svg.classList.add('favorites__check');
  svg.innerHTML = '<path d="m2.5 6.5 2.5 2.5 4.5-5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />';
  return svg;
};
const closeIcon = () => {
  const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
  svg.setAttribute('width', '10');
  svg.setAttribute('height', '10');
  svg.setAttribute('viewBox', '0 0 10 10');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('aria-hidden', 'true');
  svg.classList.add('favorites__close');
  svg.innerHTML = '<path d="m2 2 6 6M8 2 2 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />';
  return svg;
};
const renderCard = speaker => {
  const item = document.createElement('li');
  item.className = 'favorites__item';
  const media = document.createElement('div');
  media.className = 'favorites__media';
  const imageLink = document.createElement('a');
  imageLink.href = safeUrl(speaker.url) || '#';
  imageLink.tabIndex = -1;
  imageLink.setAttribute('aria-hidden', 'true');
  const image = safeUrl(speaker.image);
  if (image) {
    const img = document.createElement('img');
    img.className = 'favorites__image';
    img.src = image;
    img.alt = '';
    img.loading = 'lazy';
    imageLink.append(img);
  } else {
    const placeholder = document.createElement('span');
    placeholder.className = 'favorites__image favorites__image--empty';
    imageLink.append(placeholder);
  }

  // ✓ badge that turns into "Remove ×" on hover/focus; handled by the header-favorites script.
  const remove = document.createElement('button');
  remove.type = 'button';
  remove.className = 'favorites__remove';
  remove.dataset.removeId = speaker.id;
  remove.setAttribute('aria-label', `Remove ${speaker.name} from favorites`);
  const label = document.createElement('span');
  label.className = 'favorites__remove-label';
  label.textContent = 'Remove';
  remove.append(checkIcon(), label, closeIcon());
  media.append(imageLink, remove);
  const name = document.createElement('h3');
  name.className = 'favorites__name';
  const nameLink = document.createElement('a');
  nameLink.href = safeUrl(speaker.url) || '#';
  nameLink.textContent = speaker.name;
  name.append(nameLink);
  item.append(media, name);
  if (speaker.tagline) {
    const tagline = document.createElement('p');
    tagline.className = 'favorites__tagline';
    tagline.textContent = speaker.tagline;
    item.append(tagline);
  }
  return item;
};
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.wp-block-crownspeakers-favorites').forEach(block => {
    const list = block.querySelector('.favorites__list');
    const empty = block.querySelector('.favorites__empty');
    const bar = block.querySelector('.favorites__bar');
    const count = block.querySelector('.favorites__count');
    const inquire = block.querySelector('.favorites__inquire');
    const inquiry = block.querySelector('.favorites__inquiry');
    const endpoint = block.dataset.endpoint;

    // Speaker details fetched so far, by ID.
    const details = new Map();
    const render = ids => {
      const speakers = ids.map(id => details.get(id)).filter(Boolean);
      list.replaceChildren(...speakers.map(renderCard));
      empty.hidden = speakers.length > 0;
      bar.hidden = speakers.length === 0;
      if (!speakers.length && inquiry) {
        inquiry.hidden = true;
        inquire?.setAttribute('aria-expanded', 'false');
      }
      count.textContent = `${speakers.length} ${speakers.length === 1 ? 'speaker' : 'speakers'} added`;
      block.classList.remove('is-loading');
    };
    const load = async ids => {
      const missing = ids.filter(id => !details.has(id));
      if (missing.length && endpoint) {
        block.classList.add('is-loading');
        try {
          const response = await fetch(`${endpoint}?ids=${encodeURIComponent(missing.join(','))}`, {
            headers: {
              Accept: 'application/json'
            }
          });
          const speakers = response.ok ? await response.json() : [];
          speakers.forEach(speaker => details.set(String(speaker.id), speaker));
        } catch (error) {
          // Network error: show what we have.
        }
      }
      render(ids);
    };
    load(readFavoriteIds());

    // Removed here, in the header dropdown, or in another tab.
    document.addEventListener('cs:favorites-changed', event => {
      load(event.detail.favorites.map(speaker => String(speaker.id)));
    });

    // Adds "Speakers I'm interested in: …" to the top of the form's Event Details field.
    const prefill = (container, names) => {
      const field = container.querySelector('textarea');
      if (!field || !names.length) {
        return field;
      }
      const intro = `Speakers I'm interested in: ${names.join(', ')}.`;
      const rest = field.value.replace(/^Speakers I'm interested in: .*\.(\n\n)?/, '');
      field.value = rest ? `${intro}\n\n${rest}` : intro;
      return field;
    };

    // "Inquire about these speakers":
    // - Global Form section on the page → scroll to it and prefill it;
    // - otherwise reveal the inquiry form right under the bar (prefilled);
    // - without either (WPForms off) → follow the link to the contact page.
    inquire?.addEventListener('click', event => {
      const names = Array.from(list.querySelectorAll('.favorites__name')).map(name => name.textContent);
      const section = document.getElementById('inquiry');
      if (section) {
        event.preventDefault();
        const field = prefill(section, names);
        section.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
        field?.focus({
          preventScroll: true
        });
        return;
      }
      if (inquiry) {
        event.preventDefault();
        inquiry.querySelector('.favorites__inquiry-speakers').textContent = names.join(', ');
        prefill(inquiry, names);
        inquiry.hidden = false;
        inquire.setAttribute('aria-expanded', 'true');
        inquiry.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
        inquiry.querySelector('input:not([type="hidden"])')?.focus({
          preventScroll: true
        });
      }
    });
  });
});
/******/ })()
;
//# sourceMappingURL=view.js.map