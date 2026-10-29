<?php

use CS\Theme\Speaker_Data;

$attributes = $attributes ?? array();

$heading  = $attributes['heading'] ?? 'Discover the thinkers, leaders & storytellers who move a room.';
$autoplay = ! empty($attributes['autoplay']);

// "Show tabs" (block settings). Off: Featured speakers only, no tab row.
$show_tabs = $attributes['showTabs'] ?? true;

// On a topic page (taxonomy-speaker_category template): "Popular {topic} speakers",
// that topic's speakers with featured ones first, and no tabs.
$topic_term = is_tax('speaker_category') ? get_queried_object() : null;
$topic_term = $topic_term instanceof WP_Term ? $topic_term : null;

// "View all speakers": the full list — on topic pages "View all {topic} speakers", filtered (?topic=slug).
$view_all_url   = home_url('/all-speakers/');
$view_all_label = 'View all speakers';

if ($topic_term) {
	$heading        = sprintf('Popular %s speakers', $topic_term->name);
	$show_tabs      = false;
	$view_all_url   = add_query_arg('topic', $topic_term->slug, $view_all_url);
	$view_all_label = sprintf('View all %s speakers', $topic_term->name);
}

// Speakers per tab.
$per_tab = 10;

// Topics shown as tabs after "Featured", looked up by name (then slug) in the `speaker_category` taxonomy.
$topic_names = array('Leadership', 'Inspiration', 'Business', 'Technology');

// Visible speakers with a photo, in the admin order.
$base_args = array(
	'post_type'           => 'speaker',
	'post_status'         => 'publish',
	'posts_per_page'      => $per_tab,
	'orderby'             => 'menu_order',
	'order'               => 'ASC',
	'fields'              => 'ids',
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
	// Only speakers whose photo really exists (set, in the Media Library, file on the server).
	'post__in'            => Speaker_Data::ids_with_photo(),
);

$visible_with_photo = array(
	'relation' => 'AND',
	array(
		'key'     => '_thumbnail_id',
		'compare' => 'EXISTS',
	),
	Speaker_Data::visible_meta_query(),
);

// Tab key => [ label, query args ].
$tab_queries = array(
	'featured' => array(
		'label' => 'Featured',
		'args'  => array(
			'meta_query' => array(
				'relation' => 'AND',
				Speaker_Data::featured_meta_query(),
				$visible_with_photo,
			),
		),
	),
);

if ($topic_term) {
	$tab_queries = array(
		'featured' => array(
			'label' => $topic_term->name,
			'args'  => array(
				'orderby'    => array(
					Speaker_Data::FEATURED_ORDER_CLAUSE => 'DESC',
					'menu_order'                        => 'ASC',
				),
				'meta_query' => array(
					'relation' => 'AND',
					$visible_with_photo,
					Speaker_Data::featured_first_meta_query(),
				),
				'tax_query'  => array(
					array(
						'taxonomy' => 'speaker_category',
						'terms'    => $topic_term->term_id,
					),
				),
			),
		),
	);
}

if ($show_tabs && taxonomy_exists('speaker_category')) {
	foreach ($topic_names as $topic_name) {
		$term = get_term_by('name', $topic_name, 'speaker_category');

		if (! $term) {
			$term = get_term_by('slug', sanitize_title($topic_name), 'speaker_category');
		}

		if (! $term) {
			continue;
		}

		$tab_queries['topic-' . $term->slug] = array(
			'label' => $term->name,
			'args'  => array(
				'meta_query' => $visible_with_photo,
				'tax_query'  => array(
					array(
						'taxonomy' => 'speaker_category',
						'terms'    => $term->term_id,
					),
				),
			),
		);
	}
}

// Run each tab's query; remember which tabs every speaker belongs to.
$tabs        = array();
$speaker_ids = array();
$slide_tabs  = array();

foreach ($tab_queries as $key => $tab) {
	$ids = get_posts(array_merge($base_args, $tab['args']));

	if (! $ids) {
		continue;
	}

	$tabs[$key] = $tab['label'];

	foreach ($ids as $id) {
		$speaker_ids[$id]  = (int) get_post_field('menu_order', $id);
		$slide_tabs[$id][] = $key;
	}
}

// Each speaker is output once, in admin order; the tabs filter them in view.js.
if (! $topic_term) {
	asort($speaker_ids);
}
$speaker_ids = array_keys($speaker_ids);
$active_tab  = array_key_first($tabs);

// Prev/next arrows: in the tab row when tabs are on, in the header when they're off.
ob_start();
?>
			<div class="speakers-carousel__nav">
				<button type="button" class="speakers-carousel__arrow speakers-carousel__arrow--prev" aria-label="Previous speakers">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
				<button type="button" class="speakers-carousel__arrow speakers-carousel__arrow--next" aria-label="Next speakers">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
			</div>
<?php
$nav_markup = ob_get_clean();

// "Background" (block settings): none (white) or light gray.
$background = in_array($attributes['background'] ?? 'none', array('none', 'light'), true) ? ($attributes['background'] ?? 'none') : 'none';

$wrapper_attributes = get_block_wrapper_attributes(
	array('class' => 'light' === $background ? 'has-light-background' : '')
);
?>

<section
	<?php echo $wrapper_attributes; ?>
	data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>">
	<div class="section-inner">

		<div class="section-header<?php echo $show_tabs ? '' : ' section-header--no-tabs'; ?>">
			<h2 class="section-title">
				<?php echo esc_html($heading); ?>
			</h2>

			<?php if ($show_tabs) : ?>
				<a class="speakers-carousel__view-all" href="<?php echo esc_url($view_all_url); ?>"><?php echo esc_html($view_all_label); ?></a>
			<?php else : ?>
				<div class="speakers-carousel__actions">
					<a class="speakers-carousel__view-all" href="<?php echo esc_url($view_all_url); ?>"><?php echo esc_html($view_all_label); ?></a>
					<?php echo $nav_markup; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($show_tabs) : ?>
			<div class="speakers-carousel__toolbar">
				<div class="speakers-carousel__tabs" role="group" aria-label="Filter speakers">
					<?php foreach ($tabs as $key => $label) : ?>
						<button
							type="button"
							class="speakers-carousel__tab<?php echo $key === $active_tab ? ' is-active' : ''; ?>"
							aria-pressed="<?php echo $key === $active_tab ? 'true' : 'false'; ?>"
							data-tab="<?php echo esc_attr($key); ?>">
							<?php echo esc_html($label); ?>
						</button>
					<?php endforeach; ?>
				</div>

				<?php echo $nav_markup; ?>
			</div>
		<?php endif; ?>

		<?php if ($speaker_ids) : ?>
			<div class="swiper speakers-carousel__swiper" data-active-tab="<?php echo esc_attr($active_tab); ?>">
				<div class="swiper-wrapper speakers-carousel__slides">

					<?php
					foreach ($speaker_ids as $speaker_id) :
						$speaker_categories = get_the_terms(
							$speaker_id,
							'speaker_category'
						);
					?>

						<div class="swiper-slide speakers-carousel__item" data-tabs="<?php echo esc_attr(implode(' ', $slide_tabs[$speaker_id])); ?>">

							<a href="<?php echo esc_url(get_permalink($speaker_id)); ?>">

								<?php
								echo get_the_post_thumbnail(
									$speaker_id,
									'medium_large',
									array(
										'alt'     => get_post_meta(
											get_post_thumbnail_id($speaker_id),
											'_wp_attachment_image_alt',
											true
										),
										'loading' => 'lazy',
									)
								);
								?>

								<h3 class="speakers-carousel__name">
									<?php echo esc_html(get_the_title($speaker_id)); ?>
								</h3>

								<?php
								if (
									! empty($speaker_categories) &&
									! is_wp_error($speaker_categories)
								) :
								?>
									<div class="speakers-carousel__categories">
										<?php foreach ($speaker_categories as $speaker_category) : ?>
											<span class="speakers-carousel__category">
												<?php echo esc_html($speaker_category->name); ?>
											</span>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

							</a>

							<button
								type="button"
								class="speakers-carousel__favorite"
								aria-pressed="false"
								data-favorite
								data-speaker-id="<?php echo esc_attr($speaker_id); ?>"
								data-speaker-name="<?php echo esc_attr(get_the_title($speaker_id)); ?>"
								data-speaker-url="<?php echo esc_url(get_permalink($speaker_id)); ?>"
								data-speaker-image="<?php echo esc_url((string) get_the_post_thumbnail_url($speaker_id, 'thumbnail')); ?>">
								<span class="speakers-carousel__favorite-label" data-favorite-label>Add to Favorites</span>
								<svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true">
									<path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
								</svg>
							</button>

						</div>

					<?php endforeach; ?>

				</div>
			</div>
		<?php endif; ?>

	</div>
</section>
