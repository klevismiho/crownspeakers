/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./blocks/split-content-with-features/style.scss"
/*!*******************************************************!*\
  !*** ./blocks/split-content-with-features/style.scss ***!
  \*******************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ },

/***/ "react/jsx-runtime"
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
(module) {

module.exports = window["ReactJSXRuntime"];

/***/ },

/***/ "@wordpress/block-editor"
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
(module) {

module.exports = window["wp"]["blockEditor"];

/***/ },

/***/ "@wordpress/blocks"
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
(module) {

module.exports = window["wp"]["blocks"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["components"];

/***/ },

/***/ "./blocks/split-content-with-features/edit.js"
/*!****************************************************!*\
  !*** ./blocks/split-content-with-features/edit.js ***!
  \****************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _features_icons_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../features/icons.json */ "./blocks/features/icons.json");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");



// Shared with the Features block.


const ICON_OPTIONS = Object.entries(_features_icons_json__WEBPACK_IMPORTED_MODULE_2__).map(([value, icon]) => ({
  value,
  label: icon.label
}));

// Left column text: regular paragraphs (add, remove and reorder them like any paragraph).
const ALLOWED_BLOCKS = ['core/paragraph', 'core/list'];
const TEMPLATE = [['core/paragraph', {
  content: 'Crown has worked in the speaking business since 2004. Our agency was founded as a consultancy and is now a speakers bureau with a consultative approach.'
}]];
const NEW_ITEM = {
  icon: 'check',
  title: '',
  text: ''
};

// Icon markup comes from the theme's own icons.json, not from user input.
const Icon = ({
  name
}) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("svg", {
  width: "24",
  height: "24",
  viewBox: "0 0 24 24",
  fill: "none",
  stroke: "currentColor",
  strokeWidth: "1.75",
  strokeLinecap: "round",
  strokeLinejoin: "round",
  "aria-hidden": "true",
  dangerouslySetInnerHTML: {
    __html: (_features_icons_json__WEBPACK_IMPORTED_MODULE_2__[name] || _features_icons_json__WEBPACK_IMPORTED_MODULE_2__.check).svg
  }
});
function Edit({
  attributes,
  setAttributes
}) {
  const {
    eyebrow,
    title,
    items
  } = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useBlockProps)();
  const innerBlocksProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.useInnerBlocksProps)({
    className: 'split-content-with-features__text'
  }, {
    allowedBlocks: ALLOWED_BLOCKS,
    template: TEMPLATE
  });
  const updateItem = (index, field, value) => {
    setAttributes({
      items: items.map((item, i) => i === index ? {
        ...item,
        [field]: value
      } : item)
    });
  };
  const moveItem = (index, direction) => {
    const target = index + direction;
    if (target < 0 || target >= items.length) {
      return;
    }
    const updated = [...items];
    [updated[index], updated[target]] = [updated[target], updated[index]];
    setAttributes({
      items: updated
    });
  };
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("section", {
    ...blockProps,
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
      className: "section-inner",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        className: "split-content-with-features__content",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {
          tagName: "p",
          className: "split-content-with-features__eyebrow",
          value: eyebrow,
          allowedFormats: [],
          onChange: value => setAttributes({
            eyebrow: value
          }),
          placeholder: "Eyebrow (optional)"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {
          tagName: "h2",
          className: "split-content-with-features__title",
          value: title,
          allowedFormats: ['core/italic'],
          onChange: value => setAttributes({
            title: value
          }),
          placeholder: "Title"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("div", {
          ...innerBlocksProps
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("ul", {
          className: "split-content-with-features__list",
          children: items.map((item, index) => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("li", {
            className: "split-content-with-features__item",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("span", {
              className: "split-content-with-features__icon",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(Icon, {
                name: item.icon
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
              className: "split-content-with-features__item-body",
              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {
                tagName: "h3",
                className: "split-content-with-features__item-title",
                value: item.title,
                allowedFormats: ['core/italic'],
                onChange: value => updateItem(index, 'title', value),
                placeholder: "Feature title"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.RichText, {
                tagName: "p",
                className: "split-content-with-features__item-text",
                value: item.text,
                allowedFormats: ['core/bold', 'core/italic', 'core/link'],
                onChange: value => updateItem(index, 'text', value),
                placeholder: "Feature description"
              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("div", {
                className: "split-content-with-features__controls",
                children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.SelectControl, {
                  label: "Icon",
                  value: item.icon,
                  options: ICON_OPTIONS,
                  onChange: value => updateItem(index, 'icon', value),
                  __nextHasNoMarginBottom: true
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
                  icon: "arrow-up-alt2",
                  label: "Move up",
                  size: "small",
                  disabled: index === 0,
                  onClick: () => moveItem(index, -1)
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
                  icon: "arrow-down-alt2",
                  label: "Move down",
                  size: "small",
                  disabled: index === items.length - 1,
                  onClick: () => moveItem(index, 1)
                }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
                  icon: "trash",
                  label: "Remove feature",
                  size: "small",
                  isDestructive: true,
                  onClick: () => setAttributes({
                    items: items.filter((_, i) => i !== index)
                  })
                })]
              })]
            })]
          }, index))
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
          variant: "secondary",
          icon: "plus",
          onClick: () => setAttributes({
            items: [...items, {
              ...NEW_ITEM
            }]
          }),
          children: "Add feature"
        })]
      })]
    })
  });
}

/***/ },

/***/ "./blocks/split-content-with-features/index.js"
/*!*****************************************************!*\
  !*** ./blocks/split-content-with-features/index.js ***!
  \*****************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./blocks/split-content-with-features/style.scss");
/* harmony import */ var _edit_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit.js */ "./blocks/split-content-with-features/edit.js");
/* harmony import */ var _save_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./save.js */ "./blocks/split-content-with-features/save.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./block.json */ "./blocks/split-content-with-features/block.json");





(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_4__.name, {
  edit: _edit_js__WEBPACK_IMPORTED_MODULE_2__["default"],
  save: _save_js__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ },

/***/ "./blocks/split-content-with-features/save.js"
/*!****************************************************!*\
  !*** ./blocks/split-content-with-features/save.js ***!
  \****************************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ save)
/* harmony export */ });
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");


// The paragraphs are saved as inner blocks; render.php outputs them as $content.

function save() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_0__.InnerBlocks.Content, {});
}

/***/ },

/***/ "./blocks/features/icons.json"
/*!************************************!*\
  !*** ./blocks/features/icons.json ***!
  \************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"heart":{"label":"Heart","svg":"<path d=\\"M12 20s-7.5-4.5-7.5-10A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 7.5 3c0 5.5-7.5 10-7.5 10z\\" /><path d=\\"M12 10.5v4M10 12.5h4\\" />"},"chat":{"label":"Conversation","svg":"<path d=\\"M5 5h14a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1h-8l-4 3.5V16H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z\\" /><path d=\\"M8 9.5h8M8 12.5h5\\" />"},"check":{"label":"Check","svg":"<circle cx=\\"12\\" cy=\\"12\\" r=\\"8.5\\" /><path d=\\"m8.5 12.5 2.5 2.5 4.5-5\\" />"},"calendar":{"label":"Calendar","svg":"<rect x=\\"4\\" y=\\"5.5\\" width=\\"16\\" height=\\"14\\" rx=\\"1.5\\" /><path d=\\"M4 10h16M8.5 3.5v4M15.5 3.5v4\\" />"},"people":{"label":"People","svg":"<circle cx=\\"9\\" cy=\\"8\\" r=\\"3\\" /><circle cx=\\"16.5\\" cy=\\"9.5\\" r=\\"2.5\\" /><path d=\\"M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 7-3.7\\" />"},"star":{"label":"Star","svg":"<path d=\\"m12 3 2.6 5.6 6 .7-4.5 4.1 1.2 6L12 16.4 6.7 19.4l1.2-6L3.4 9.3l6-.7z\\" />"},"chart":{"label":"Growth","svg":"<path d=\\"M4 19h16M6 15l4-4 3 3 5-6\\" /><path d=\\"M15 8h3v3\\" />"},"flag":{"label":"Flag","svg":"<path d=\\"M6 21V4M6 4h11l-2 3.5L17 11H6\\" />"},"trophy":{"label":"Trophy","svg":"<path d=\\"M8 4h8v5a4 4 0 0 1-8 0V4zM8 6H5a3 3 0 0 0 3 3M16 6h3a3 3 0 0 1-3 3M12 13v4M8.5 20h7\\" />"},"globe":{"label":"Globe","svg":"<circle cx=\\"12\\" cy=\\"12\\" r=\\"8.5\\" /><path d=\\"M3.5 12h17M12 3.5c2.5 2.5 3.5 5.5 3.5 8.5s-1 6-3.5 8.5c-2.5-2.5-3.5-5.5-3.5-8.5s1-6 3.5-8.5z\\" />"},"shield":{"label":"Shield","svg":"<path d=\\"M12 3.5 19 6v5.5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z\\" /><path d=\\"m9 12 2 2 4-4\\" />"},"compass":{"label":"Compass","svg":"<circle cx=\\"12\\" cy=\\"12\\" r=\\"8.5\\" /><path d=\\"m15.5 8.5-2 5-5 2 2-5z\\" />"},"microphone":{"label":"Microphone","svg":"<rect x=\\"9\\" y=\\"3\\" width=\\"6\\" height=\\"11\\" rx=\\"3\\" /><path d=\\"M5.5 11a6.5 6.5 0 0 0 13 0M12 17.5V21M8.5 21h7\\" />"},"dollar":{"label":"Dollar","svg":"<circle cx=\\"12\\" cy=\\"12\\" r=\\"8.5\\" /><path d=\\"M14.5 9.25c-.4-.9-1.35-1.5-2.5-1.5-1.5 0-2.5.8-2.5 2s1 1.7 2.5 2 2.5.9 2.5 2.1-1 2-2.5 2c-1.2 0-2.2-.6-2.6-1.6M12 6.25v1.5M12 16.25v1.5\\" />"},"checklist":{"label":"Checklist","svg":"<rect x=\\"5\\" y=\\"4\\" width=\\"14\\" height=\\"17\\" rx=\\"1.5\\" /><path d=\\"M9 4V3h6v1M8.5 10l1.5 1.5 2.5-2.5M8.5 15.5l1.5 1.5 2.5-2.5M14.5 10.5h1.5M14.5 16h1.5\\" />"}}');

/***/ },

/***/ "./blocks/split-content-with-features/block.json"
/*!*******************************************************!*\
  !*** ./blocks/split-content-with-features/block.json ***!
  \*******************************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"crownspeakers/split-content-with-features","version":"1.0.0","title":"Split Content With Features","category":"crownspeakers","icon":"columns","description":"Eyebrow, heading and text beside a list of features with icons","example":{"viewportWidth":1200},"supports":{"html":false,"align":["full"]},"attributes":{"eyebrow":{"type":"string","default":"Why book with Crown?"},"title":{"type":"string","default":"Someone in your corner.<br>From the start."},"items":{"type":"array","default":[{"icon":"microphone","title":"Understanding your vision.","text":"We start with your audience, your aims and the goals of your event."},{"icon":"dollar","title":"Consider the options.","text":"We help you weigh up relevant experience, speaking style and budget."},{"icon":"checklist","title":"Work through the details.","text":"We confirm fee, availability, and any other requirements before you book."}],"items":{"type":"object"}}},"textdomain":"crownspeakers","editorScript":"file:./index.js","style":"file:./style-index.css","render":"file:./render.php"}');

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	const __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		const cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		const module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			const e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		const deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			let notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				let [chunkIds, fn, priority] = deferred[i];
/******/ 				let fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					const r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter/value functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			if(Array.isArray(definition)) {
/******/ 				var i = 0;
/******/ 				while(i < definition.length) {
/******/ 					var key = definition[i++];
/******/ 					var binding = definition[i++];
/******/ 					if(!__webpack_require__.o(exports, key)) {
/******/ 						if(binding === 0) {
/******/ 							Object.defineProperty(exports, key, { enumerable: true, value: definition[i++] });
/******/ 						} else {
/******/ 							Object.defineProperty(exports, key, { enumerable: true, get: binding });
/******/ 						}
/******/ 					} else if(binding === 0) { i++; }
/******/ 				}
/******/ 			} else {
/******/ 				for(var key in definition) {
/******/ 					if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 						Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 					}
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.hasOwn(obj, prop))
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		const installedChunks = {
/******/ 			"split-content-with-features/index": 0,
/******/ 			"split-content-with-features/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		const webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			let [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		const chunkLoadingGlobal = globalThis["webpackChunkcrownspeakers"] ||= [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	let __webpack_exports__ = __webpack_require__.O(undefined, ["split-content-with-features/style-index"], () => (__webpack_require__("./blocks/split-content-with-features/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map