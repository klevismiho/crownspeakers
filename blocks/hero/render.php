<?php

use CS\Theme\Speaker_Data;

// Searches go to the Speakers page, where speakers-grid reads ?keyword=, ?topic= and ?type=.
$search_url = home_url('/all-speakers/');
$selected   = Speaker_Data::selected_filters();
$keyword    = isset($_GET['keyword']) ? sanitize_text_field(wp_unslash($_GET['keyword'])) : '';
// Every term with speakers, subcategories indented under their parent.
$topics     = Speaker_Data::filter_options('speaker_category');
$types      = Speaker_Data::filter_options(Speaker_Data::TYPE_TAXONOMY);

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
  <h1>Great events start<br />with <span>great people</span>.</h1>
  <p>Most of our clients found us once.<br />They’ve stayed ever since.</p>
  <form class="hero-form" action="<?php echo esc_url($search_url); ?>" method="get" role="search">

    <div class="field-group field-group--search">
      <label for="speaker-keyword" class="screen-reader-text">
        Search by keyword
      </label>
      <svg
        class="search-icon"
        xmlns="http://www.w3.org/2000/svg"
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.35-4.35" />
      </svg>
      <input
        type="search"
        id="speaker-keyword"
        name="keyword"
        value="<?php echo esc_attr($keyword); ?>"
        placeholder="Search by keyword" />
    </div>

    <div class="field-group">
      <label for="speaker-topic" class="screen-reader-text">Topic</label>
      <select id="speaker-topic" name="topic">
        <option value="">All topics</option>
        <?php foreach ($topics as $slug => $label) : ?>
          <option value="<?php echo esc_attr($slug); ?>" <?php selected($selected['topic'] ?? '', $slug); ?>>
            <?php echo esc_html($label); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <?php // Shown only once the speaker type taxonomy exists (see Speaker_Data::TYPE_TAXONOMY). ?>
    <?php if ($types) : ?>
      <div class="field-group">
        <label for="speaker-type" class="screen-reader-text">Speaker type</label>
        <select id="speaker-type" name="type">
          <option value="">All speaker types</option>
          <?php foreach ($types as $slug => $label) : ?>
            <option value="<?php echo esc_attr($slug); ?>" <?php selected($selected['type'] ?? '', $slug); ?>>
              <?php echo esc_html($label); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="field-group">
      <button type="submit" class="wp-block-button__link wp-element-button">Find my speaker</button>
    </div>
  </form>

  <img class="hero-image hero-image-1" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-1.jpg" alt="" />
  <img class="hero-image hero-image-2" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-2.jpg" alt="" />
  <img class="hero-image hero-image-3" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-3.jpg" alt="" />
  <img class="hero-image hero-image-4" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-4.jpg" alt="" />
  <img class="hero-image hero-image-5" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-5.jpg" alt="" />
  <img class="hero-image hero-image-6" src="<?php echo get_template_directory_uri(); ?>/assets/src/images/hero-image-6.jpg" alt="" />
</section>