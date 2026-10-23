<?php

// Cards come from the `speaker_category` taxonomy.
// By default: the categories with the most speakers. To hand-pick cards (in this order),
// list their slugs in $pinned_slugs; when it isn't empty it replaces the automatic choice.
$limit        = 12;
$pinned_slugs = array(
	// 'technology',
	// 'healthcare',
);

$terms = array();

if (taxonomy_exists('speaker_category')) {
	$terms = $pinned_slugs
		? get_terms(
			array(
				'taxonomy'   => 'speaker_category',
				'slug'       => $pinned_slugs,
				'orderby'    => 'slug__in',
				'hide_empty' => false,
			)
		)
		: get_terms(
			array(
				'taxonomy'   => 'speaker_category',
				'orderby'    => 'count',
				'order'      => 'DESC',
				'hide_empty' => true,
				'number'     => $limit,
			)
		);
}

$industries = array();

foreach (is_wp_error($terms) ? array() : $terms as $term) {
	$industries[] = array(
		'title' => $term->name,
		'url'   => get_term_link($term),
		'icon'  => \CS\Theme\Speaker_Data::topic_icon($term->name),
	);
}

if (! $industries) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="section-header">
			<h2 class="section-title">Explore by industry</h2>

			<div class="explore-by-industry__nav">
				<button type="button" class="explore-by-industry__arrow explore-by-industry__arrow--prev" aria-label="Previous industries">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
				<button type="button" class="explore-by-industry__arrow explore-by-industry__arrow--next" aria-label="Next industries">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
			</div>
		</div>

		<div class="swiper explore-by-industry__swiper">
			<div class="swiper-wrapper">

				<?php foreach ( $industries as $industry ) : ?>
					<div class="swiper-slide">
						<a class="explore-by-industry__card" href="<?php echo esc_url( $industry['url'] ); ?>">

							<span class="explore-by-industry__icon">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php echo $industry['icon']; ?>
								</svg>
							</span>

							<h3 class="explore-by-industry__title">
								<?php echo esc_html( $industry['title'] ); ?>
							</h3>

							<span class="explore-by-industry__link" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
									<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>

						</a>
					</div>
				<?php endforeach; ?>

			</div>
		</div>

	</div>
</section>
