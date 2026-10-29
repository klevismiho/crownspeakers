# Crownspeakers theme

WordPress **block theme** for Crownspeakers, a speaker booking agency. Templates are `.html` block templates (`templates/`, `parts/`); page sections are custom PHP-rendered blocks.

## Build

- `npm run dev` — watch mode (Vite for theme assets + `wp-scripts` for blocks, in parallel)
- `npm run build` — production build
- Blocks compile from `blocks/` to `assets/build/blocks/`, and WordPress loads them **from the build folder** — so every change, including `render.php` and `block.json`, needs `npm run dev` (watch, copies on save) or `npm run build`. Never edit files in `assets/build/`.
- `wp-scripts` only discovers blocks at startup: restart `npm run dev` after adding a new block.

## Custom blocks

Namespace `crownspeakers/`, category `crownspeakers`. `inc/Block_Registry.php` auto-registers every folder in the build directory that has a `block.json`, so no manual registration is needed.

Current blocks: `hero`, `logo-slider`, `navigation`, `speakers-carousel`, `split-with-image`, `explore-by-industry`, `statistics`, `talent-features`, `quotes-slider`, `global-form`, `speaker-search`, `speakers-grid`, `speaker-hero`, `similar-speakers-carousel`, `speaker-quotes-carousel`, `speaker-books`, `speaker-keynotes`, `speaker-videos`, `speaker-details`, `header-search`, `header-favorites`, `topics-grid`, `page-header`.

`navigation` renders menu 2272. A top-level item becomes a **megamenu** (promo card + two-column "Popular topics" list of the 16 `speaker_category` terms with the most speakers, shown A–Z — menu sub-items are ignored — full width under the header) when it has the CSS class `topics-menu` (set in Appearance → Menus); other parents keep the normal dropdown. The Topics link itself goes to `/topics/`; on desktop the panel opens on hover (`@media (hover: hover)`, with an invisible `::before` bridge over the header's 2rem bottom padding) and on keyboard focus (`:focus-within`); the chevron button next to it toggles the panel (that's how it opens on mobile). Menu item CSS classes are also output on the `<li>`.

`header-favorites` (in `parts/header.html`) stores favorites in the visitor's browser (`localStorage` key `cs_favorites`) and shows a "Favorites" link to `/favorites/` with the count (no dropdown; the list lives on the Favorites page, `favorites` block). It updates every `[data-favorites-count]` element. Its `view.js` loads on every page and handles **any** button with `data-favorite` + `data-speaker-id`, `data-speaker-name`, `data-speaker-url`, `data-speaker-image` (optional child `[data-favorite-label]` for visible text). Currently used by `speakers-grid` cards, `speakers-carousel` items and `speaker-hero`. To add a favorite button elsewhere, just output those attributes.

`header-search` lives in `parts/header.html`: an icon button that opens a full-width panel under the header (absolutely positioned against `.site-header`, which is `position: relative` in `_header.scss`). It submits `?keyword=` to `/all-speakers/`, which `speakers-grid` filters by.

Naming: blocks about the single current speaker use the `speaker-` prefix; blocks listing many speakers use `speakers-`.

Single speaker pages use `templates/single-speaker.html` (speaker-hero → speaker-details → speaker-quotes-carousel → speaker-books → speaker-keynotes → speaker-videos → similar-speakers-carousel → global-form). Blocks that show the current speaker declare `"usesContext": ["postId", "postType"]`, read `(int) ( $block->context['postId'] ?? get_the_ID() )`, and `return` early (render nothing) when their data is empty. In-page anchors: `#inquiry` (global-form), `#videos` (speaker-videos). `similar-speakers-carousel` is a copy of `speakers-carousel` without the tabs: it shows speakers sharing a `speaker_category` with the current one (excluding it), topped up with other speakers.

Topic pages (`speaker_category` archives, URL base `/category/…`) use `templates/taxonomy-speaker_category.html`: `page-header` (empty title → the topic's name, empty paragraph → the topic's description, plus a "← View all Topics" link to `/topics/` that `page-header` only outputs on `speaker_category` archives) + `speakers-carousel` ("Popular {topic} speakers") + `explore-by-industry` with `heading` "Other interesting topics" (on topic pages it leaves out the current topic). No speakers grid on topic pages. `speakers-grid` detects `is_tax( 'speaker_category' )` and limits itself to that topic.

`global-form` shows the WPForms "Event Inquiry" form, **ID 27446** (`CS\Theme\Inquiry_Form::FORM_ID` in `inc/Inquiry_Form.php`, used via `Inquiry_Form::id()` / `display()`; falls back to the static form if WPForms is off or that form doesn't exist). The form's import file is `setup/wpforms-event-inquiry.json` (re-import on other environments, then update the ID). The WPForms styling is the `inquiry-form($theme)` mixin in `assets/src/scss/_inquiry-form.scss` — `'dark'` (default; `global-form`, `favorites`) or `'light'` (`contact`): fields with the CSS class `cs-half` take one column from `small`, all others are full width.

`explore-by-industry` has an editable `heading` (default "Explore by industry"); its cards are `speaker_category` terms: the 12 with the most speakers by default, or the slugs in `$pinned_slugs` (in that order) when filled. Each card links to the topic page; its icon comes from `Speaker_Data::topic_icon()` (matches the term name, first match wins, compass as fallback). The card styles are the `topic-card()` mixin in `assets/src/scss/_topic-card.scss`, shared with `topics-grid`.

`topics-grid` has an editable title (`heading` attribute, default "Start with what matters to your audience.") and lists **every** `speaker_category` term with speakers, A–Z, as the same cards in a grid (1 → 2 → 3 → 4 → 5 columns). Add it to a page in the editor (e.g. the Topics page: Page Header + Topics Grid) — there is no dedicated page template.

`page-header` title supports a **"Highlight (gold)"** toolbar button: the `crownspeakers/highlight` RichText format, registered once in `blocks/page-header/highlight-format.js`, output as `<span class="is-highlight">` (gold). Other blocks can offer it by adding `'crownspeakers/highlight'` to a RichText's `allowedFormats` (and styling `.is-highlight`). The eyebrow has a short centered line under it (`::after`).

`page-header` is the dark header for regular pages: eyebrow, title and paragraph edited inline in the editor (`eyebrow` / `title` / `subtitle` attributes — `subtitle` is the paragraph; an empty title falls back to the page's own title, or on a category/topic archive to the term name — and an empty paragraph to the term description) and a decorative crown on the right from `small`. Pages use the default `templates/page.html`, which only outputs the page's blocks, so content pages are built from blocks, not templates.

`favorites` is the Favorites page block: `view.js` reads the saved IDs from `localStorage` (`cs_favorites`), fetches card data from the theme endpoint `GET /wp-json/crownspeakers/v1/speakers?ids=…` (`inc/Rest_Api.php`: name, link, `medium_large` photo, tagline; public speakers only, max 50 IDs) and renders the cards. Remove buttons use `data-remove-id` (handled by `header-favorites`), which fires `cs:favorites-changed` after every change so the block re-renders. "Inquire about these speakers": if the page has a Global Form section (`#inquiry`) it scrolls there and prefills its Event Details textarea with the names; otherwise it reveals the inquiry form in a dark panel right under the bar (`.favorites__inquiry`, prefilled, scrolled into view). The same WPForms form must never be on a page twice, so that panel is only output when `has_block( 'crownspeakers/global-form' )` is false.

`contact` is the Contact page section: static details on the left (title, intro, phone and email pills, office address and hours, as PHP variables at the top of `render.php`) and the Event Inquiry form on a light gray panel on the right, styled with `inquiry-form('light')`. It has `id="inquiry"` like `global-form`, so never put both on one page (the WPForms form can only appear once); `favorites` checks for both before adding its own form.

`split-content` is a generic text section: eyebrow + title on the left (attributes, edited inline), paragraphs on the right as **inner blocks** (`core/paragraph`, `core/list`). `centered-content` is its stacked, centered sibling (optional eyebrow, title, then paragraphs). `split-content-with-features` is the third variant: eyebrow, title and paragraphs on the left, a list of features on the right (dark circle with a gold icon, title, text; `items` attribute, edited inline, with an **icon dropdown** per feature, using `blocks/features/icons.json`). `our-story` adds an image: eyebrow + image (Media Library, `imageId`/`imageUrl`/`imageAlt` attributes, chosen in the sidebar "Image" panel or the on-canvas placeholder) on the left, title + paragraphs on the right. These four are the only custom blocks whose `save.js` returns `<InnerBlocks.Content />` instead of `null`; `render.php` outputs the paragraphs via `$content`.

`features` is a heading + row of features (icon, title, text). The title and each feature's text are edited inline, and features can be added, removed and reordered (`items` array attribute). There's no icon picker: every feature uses the heart icon (each item still stores `icon`, default `heart`). Icons live in `blocks/features/icons.json`, shared by `edit.js` (imported) and `render.php` (read from the **source** folder, because the block build doesn't copy extra JSON files).

`speakers-grid` toolbar is a GET `<form>` (to the current page or topic page): "Refine results" toggles a filter panel (keyword + one dropdown per `Speaker_Data::filters()` taxonomy that exists and has terms: topic, type, fee, location; topic is hidden on topic pages; options come from `Speaker_Data::filter_options()` — every term with speakers, subcategories indented under their parent, also used by the hero search), the button shows the active-filter count, and a speaker total sits next to it. `?sort=` is `relevance` (default: featured first, then search relevance when there's a keyword, then `menu_order`), `name` (title A–Z) or `newest` (date). `view.js` toggles the panel, submits on sort change and drops empty fields from the URL. Pagination keeps the query string.

## Speaker data

All speaker blocks read data through `inc/Speaker_Data.php` (`CS\Theme\Speaker_Data`, static methods) — never call `get_post_meta()` for speaker fields directly in a block. Fields are plain post meta saved by a speaker plugin (repeaters are serialized arrays, **not** ACF — `get_fields()` returns nothing useful).

| Method | Source |
|---|---|
| `name_parts()` | `speaker_fname`, `speaker_lname` (fallback: split title) |
| `tagline()` | excerpt → `speaker_brief_bio` → first highlight |
| `categories()` | `speaker_category` terms |
| `travels_from()` | `speaker_traveling_from` |
| `fee()` | `speaker_fee_ranges` taxonomy terms (assumed; empty if the taxonomy doesn't exist) |
| `highlights()` | `speaker_descriptions[][speaker_description]` |
| `testimonials()` | `speaker_testimonies[][speaker_testimony, speaker_testimony_name]` (name only, no separate title) |
| `books()` | `speaker_books[][speaker_book (image URL), speaker_book_link]` (no title field) |
| `keynotes()` | `speaker_programs[][speaker_program_title, speaker_program_description]` |
| `videos()` | `speaker_videos[][speaker_video_title, speaker_video (Vimeo URL)]`; thumbnail via oEmbed, cached in a transient |
| `is_exclusive()` | no field yet (always false) |
| `featured_meta_query()` | `feature_speaker = 1`; used by the `speakers-carousel` "Featured" tab |
| `ids_with_photo()` | IDs of published speakers whose featured image really exists (set, media item exists, original file on disk, and the `PHOTO_SIZE` = `medium_large` resized file the blocks load — skipped when the image is smaller and has no resized file); cached 6 h in the `PHOTO_IDS_CACHE` transient (bump its suffix when the check changes), cleared by `register_hooks()` on speaker save / media changes. Used as `post__in` by `speakers-grid`, `speakers-carousel` and `similar-speakers-carousel` (WordPress ignores `post__not_in` when `post__in` is set — remove excluded IDs from the list instead). If media is ever offloaded to a CDN, the `file_exists()` check would hide everyone. |
| `featured_first_meta_query()` | keeps all speakers but lets a query sort by `feature_speaker` (`orderby` the `FEATURED_ORDER_CLAUSE` key `DESC`); used by `speakers-grid` so featured speakers always come first |
| `visible_meta_query()` | excludes `private_speaker = 1` and `archived_speaker = 1`; used by every speaker listing query |

Other known meta: `feature_speaker` (1/0), `featured_speaker_quote`, `speaker_admin_details` (private — never display).

`speakers-carousel`, `speakers-grid` and `similar-speakers-carousel` query real `speaker` posts with `WP_Query`. `speakers-carousel` tabs: "Featured" (`feature_speaker`) plus the `speaker_category` terms named in `$topic_names` in its `render.php` (looked up by name, then slug; a tab is hidden if the term doesn't exist or has no speakers). The `showTabs` attribute ("Show tabs" toggle in the block settings, default on) turns the tab row off — then only Featured speakers are shown. On a topic page (`is_tax( 'speaker_category' )`) it switches itself to "Popular {topic} speakers": no tabs, that topic's speakers, featured first (keeps the query order instead of re-sorting by `menu_order`). Up to 10 speakers per tab; every speaker is rendered once with `data-tabs`, and `view.js` swaps the Swiper slides on tab click, then fires `cs:favorites-refresh` so `header-favorites` re-syncs the buttons. `speakers-grid` reads `?keyword=` (title/content search) plus `?topic=` (speaker_category slug) and `?type=` (`Speaker_Data::TYPE_TAXONOMY`, assumed `speaker_type`; ignored if that taxonomy doesn't exist), via `Speaker_Data::filters_tax_query()`. The homepage `hero` form submits all three to `/all-speakers/`; its Topic dropdown lists `speaker_category` terms, and the Speaker type dropdown only renders when the type taxonomy exists.

Each block folder follows the same pattern:

- `block.json` — `apiVersion: 3`, `supports: { html: false, align: ["full"] }`, `render: file:./render.php`
- `index.js` — `registerBlockType`, imports `style.scss`
- `edit.js` — placeholder only (`<section {...useBlockProps()}>Block name edits here</section>`)
- `save.js` — returns `null`
- `render.php` — the real markup; wrapper is `<section <?php echo get_block_wrapper_attributes(); ?>>`
- `style.scss` — front-end + editor styles
- `view.js` — front-end JS (only when needed, e.g. sliders)

Conventions:

- Content is **static for now**: hard-coded in `render.php`, with repeated items (cards, stats, quotes) as a PHP array at the top of the file.
- BEM class names prefixed with the block name (`.speakers-carousel__name`), plus shared `.section-inner`, `.section-header`, `.section-title`.
- Escape output (`esc_html`, `esc_url`).
- Buttons use core classes `wp-block-button__link wp-element-button`.

## Styles

- Every block SCSS starts with `@use '../../assets/src/scss/mixins' as *;` and nests everything under `.wp-block-crownspeakers-<block>`.
- **Mobile first**: base styles are the phone layout; enhance with `@include breakpoint(small|medium|large|xlarge)` = 800 / 1024 / 1320 / 1600px. Don't use `breakpoint-down` for layout (only for phone-only overrides that can't be undone upward, e.g. the `!important` button width in `_buttons.scss`).
- Put `@include breakpoint()` blocks **after** a rule's plain declarations (Sass 1.101 outputs declarations in source order).
- Responsive patterns used everywhere: section titles `2rem` → `2.5rem` from `small`; grids 1 col → 2 (480px) → 3–4 (`medium`); header rows wrap or stack on phones; search bars put the button on its own full-width row below `small`; horizontal tab rows scroll on phones.
- Header: below `medium` it shows logo | Contact Us | hamburger (a light round button with two lines); search and favorites are hidden there because the mobile menu has its own search field and Favorites row. From `medium`: logo | inline menu | search, favorites, Contact Us.
- Mobile menu (below `medium`): the hamburger opens `.wp-block-crownspeakers-navigation__panel`, a fixed full-screen panel under the header (`--cs-menu-top` set by `navigation/view.js`; page scroll locked via `html.has-menu-open`; header icons hidden via `.site-header.is-menu-open`). It holds the menu rows plus mobile-only `__extras`: Favorites row (link to `/favorites/`, count via `[data-favorites-count]`), Contact Us (`/contact/`), a search field (→ `/all-speakers/?keyword=`) and a CTA card. From `medium` the panel is `display: contents` and the extras are hidden.
- Use rem units (16px base).
- Colors (from `theme.json`, exposed in `assets/src/scss/_variables.scss`):
  - `--color-primary` #34485C (slate blue, main text/backgrounds)
  - `--color-secondary` #DDB56F (gold accent)
  - `--color-dark-gray` #142333, `--color-white`, `--color-black`
  - Recurring non-palette colors: light section background `#e5e8ee`, muted text `#5b6675`, borders `#e5e7eb` / `#d6dae1`
- Fonts (presets): headings `var(--wp--preset--font-family--crownspeakers-heading-font)` (Playfair Display), body `var(--wp--preset--font-family--crownspeakers-body-font)` (Instrument Sans, 400 and 500 loaded).
- Content width: `var(--wp--style--global--content-size)` (1280px).
- The editor renders blocks in an iframe: the global stylesheet (`assets/build/css/global.css`, which defines the `--color-*` variables) is loaded there via `add_editor_style()` in `inc/Theme_Setup.php`. Without it, blocks using `var(--color-…)` show transparent/white in the editor.
- Full-bleed colored sections use `margin-left: -1rem; margin-right: -1rem;` with side padding.

## Sliders

Swiper (`swiper` package), initialized in each block's `view.js` on `DOMContentLoaded`. Scope navigation/pagination elements with `block.querySelector(...)` so multiple instances don't conflict, and import only the modules used (`Navigation`, `Pagination`, `Autoplay`).

## Content model

- `speaker` post type and `speaker_category` taxonomy — registered **outside the theme** (plugin), not in theme code.
- No post type archive: `/all-speakers/` is a regular WordPress page (titled "Speakers"), built from blocks (e.g. `speaker-search`). The "View all speakers" link points there.

## Team

`team` post type (`inc/Post_Type_Team.php`): name = title, photo = featured image, bio = content, order = page attributes (`menu_order`). Not public (no single pages, archive or search); shown by the `team` block (all published members ordered by `menu_order`, then title; photo, name, `team_role`, bio, optional LinkedIn link; editable `heading`, default "Our team"). ACF fields `team_role` (job title) and `team_linkedin` live in `acf-json/group_cs_team_member.json`.

## Images

Theme images live in `assets/src/images/` (e.g. `hero-image-1.jpg` … `hero-image-6.jpg`, `logo.svg`, `member-*.svg`). Reference them with `get_template_directory_uri() . '/assets/src/images/...'`.

## Working preferences

- Work only inside this theme folder. Don't read or search plugins, mu-plugins, the database or the live site; solve things from the theme (filters, templates, blocks) or ask.
- Make only the change asked for; keep edits scoped to the files mentioned.
- Match designs from screenshots closely, using existing tokens before adding new colors.
