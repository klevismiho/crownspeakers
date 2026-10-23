# Changelog

## 2026-10-23

### Added

#### Blocks
- `page-header`: dark page header with eyebrow, title and paragraph, all edited directly in the editor (an empty title falls back to the page title), plus a decorative crown on the right.
- `topics-grid`: every speaker category with speakers, A–Z, as cards in a grid (1 → 5 columns), with an editable title above it.
- `favorites`: the visitor's saved speakers as cards, with a ✓ badge that turns into "Remove ×", a "N speakers added" bar and an empty state. "Inquire about these speakers" reveals the Event Inquiry form under the bar, prefilled with the speaker names. On a page with the Global Form section it scrolls there and prefills it instead.

#### Forms
- WPForms "Event Inquiry" form (ID 27446) shown in the `global-form` section, styled like the design (two-column fields, gold submit button). The static form remains as a fallback when WPForms is off.
- `setup/wpforms-event-inquiry.json`: import file for the form (name, email, phone, organization, event date, location, format, budget, previous speakers, event details), for setting it up on other environments.
- `inc/Inquiry_Form.php`: single place for the form ID, used by `global-form` and `favorites`.

#### Data and tooling
- `inc/Rest_Api.php`: read-only endpoint `GET /wp-json/crownspeakers/v1/speakers?ids=…` (name, link, photo, tagline; public speakers only) used by the Favorites block.
- `Speaker_Data`: search filters (`?topic=`, `?type=`), featured-speaker query and topic icons (`topic_icon()`).
- Shared SCSS mixins: `_topic-card.scss` (explore-by-industry + topics-grid cards) and `_inquiry-form.scss` (WPForms styling for global-form + favorites).

### Changed
- Homepage hero search now works: it submits keyword and topic to `/all-speakers/`, the Topic dropdown lists the speaker categories, and the speakers grid filters by both. The "Speaker type" dropdown appears once a speaker type taxonomy exists (`Speaker_Data::TYPE_TAXONOMY`, assumed `speaker_type`).
- `speakers-carousel`:
  - "Featured" tab shows speakers with "Feature speaker" checked.
  - Leadership, Inspiration, Business and Technology tabs show speakers from those `speaker_category` topics, switching without a page reload.
  - New "Show tabs" option in the block settings. With tabs off: Featured speakers only, arrows next to "View all speakers", no empty gap.
- `explore-by-industry`: cards come from the speaker categories (the 12 with the most speakers, or a hand-picked list in `$pinned_slugs`), each linking to its topic page, with an icon matched to the topic name.
- Topics megamenu: shows the 16 most popular categories, A–Z.
- Header "Favorites" is now a link to `/favorites/` with the count badge. The favorites dropdown was removed, and the mobile menu Favorites row links to `/favorites/` too.
- Editor: the global stylesheet is loaded inside the editor iframe (`add_editor_style`), so blocks show the theme colors in the editor instead of a white background.
- `speakers-grid`: removed the fixed 100% image width.

### Fixed
- `speakers-carousel` tabs did nothing when clicked (they used a Swiper method from a module that isn't loaded).

### Removed
- `templates/page-topics.html` and its `theme.json` entry. The Topics page is built from blocks (Page Header + Topics Grid) on the default page template.

### Known gaps
- "Speaker type" filter: no taxonomy found yet.
- On another environment, the imported Event Inquiry form gets a new ID: update `Inquiry_Form::FORM_ID`.
- The Favorites page must have the slug `favorites` for the header link.

## 2026-10-18

### Added

#### Homepage blocks
- `split-with-image`: image beside heading, text and a Contact Us button.
- `explore-by-industry`: Swiper carousel of industry cards with icons and prev/next arrows.
- `statistics`: four key numbers with suffixes and dividers.
- `talent-features`: numbered feature list beside a collage of speaker photos, circle and crown.
- `quotes-slider`: single-quote testimonial slider with decorative quote marks and bar pagination.
- `global-form`: event inquiry section with intro, phone link and a two-column form (`id="inquiry"`; not connected to a form handler yet).

#### Speakers page (`/all-speakers/`)
- `speaker-search`: "Find your next great speaker." heading with a search bar (`?keyword=`).
- `speakers-grid`: toolbar (Refine / Sort by, static), 4-column grid of real `speaker` posts with photo, favorite button, name, tagline, categories, fee and travel location, plus pagination. Filters by `?keyword=` and, on topic pages, by the current topic.

#### Single speaker template (`templates/single-speaker.html`)
- `speaker-hero`: "Meet [Name]" with tagline, Request more info (`#inquiry`), Watch in action (`#videos`) and Add to Favorites.
- `speaker-details`: Topics / Travels from / Speaking fee strip, highlights with photo, bio with Read more.
- `speaker-quotes-carousel`: testimonial cards, three per view, with bar pagination.
- `speaker-books`: book covers linking to the store.
- `speaker-keynotes`: accordion of keynote programs with a two-line preview.
- `speaker-videos`: video thumbnails (Vimeo, via oEmbed) linking to the videos.
- `similar-speakers-carousel`: speakers from the same category, excluding the current one.

#### Topic pages
- `templates/taxonomy-speaker_category.html`: topic title + speakers grid limited to that topic (`/category/{topic}/`).

#### Header
- `header-search`: search icon opening a full-width panel under the header; searches `/all-speakers/`.
- `header-favorites`: "Favorites" with count badge and a dropdown of saved speakers. Favorites are stored in the browser (`localStorage`) and toggled by any `data-favorite` button (speakers grid, speakers carousel, speaker hero).
- Topics megamenu: the menu item with the CSS class `topics-menu` opens a full-width panel with a promo card and all speaker categories in two columns.
- Mobile menu: full-screen panel with menu rows, Favorites row with count, Contact Us, a search field and a call-to-action card.

#### Data and tooling
- `inc/Speaker_Data.php`: single place that reads speaker fields (name, tagline, categories, travel location, fee, highlights, testimonials, books, keynotes, videos, visibility) from post meta.
- `CLAUDE.md`: theme overview, block conventions, styling rules, speaker field map and working preferences.

### Changed
- Whole theme made responsive, mobile first: base styles are the phone layout, enhanced with the `breakpoint()` mixin. Section titles scale from 2rem to 2.5rem, grids go from 1 to 2 to 3–4 columns, search bars stack on phones, and rows that overflowed now wrap or scroll.
- Navigation rewritten mobile first; the inline desktop menu starts at 1024px (`medium`).
- Header: logo | search, favorites | hamburger on phones; "Favorites" label and Contact Us from 800px; side padding added.
- Homepage hero: smaller heading and stacked search form on phones; decorative photos only from 1320px.
- Footer and button groups converted to mobile first.
- `speakers-carousel`: shows categories under names, "View all speakers" link, static category tabs, prev/next arrows, square photos with 16px radius, Instrument Sans names, favorite buttons on the photos.
- `speakers-carousel`, `speakers-grid` and `similar-speakers-carousel` hide private and archived speakers.
- `speakers-books` renamed to `speaker-books` and `speakers-quotes-carousel` to `speaker-quotes-carousel` (blocks about one speaker use `speaker-`, lists of speakers use `speakers-`).
- "View all speakers" links and searches point to `/all-speakers/`.

### Fixed
- Mobile menu was invisible: white links on a white panel and black hamburger bars on the dark header.
- Desktop menu links disappeared on hover (blue on the blue header) and dropdown links were white on white.
- Header had no side padding on small screens.
- Removed leftover debug output from `speakers-carousel`.

### Known gaps
- Fee field not found (assumed `speaker_fee_ranges` taxonomy), no book title field, no "exclusive speaker" field.
- `global-form` does not send submissions yet.
- Refine results, Sort by and the carousel category tabs are static.
- Contact links point to `/contact/` (address to confirm); the header Contact Us button still links to `#`.
