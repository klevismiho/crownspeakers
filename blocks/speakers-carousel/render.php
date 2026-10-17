<?php

$attributes = $attributes ?? array();

$heading  = $attributes['heading'] ?? 'Discover the thinkers, leaders & storytellers who move a room.';
$autoplay = ! empty($attributes['autoplay']);

$speakers = new WP_Query(
	array(
		'post_type'      => 'speaker',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section
	<?php echo $wrapper_attributes; ?>
	data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>">
	<div class="section-inner">

		<div class="section-header">
			<h2 class="section-title">
				<?php echo esc_html($heading); ?>
			</h2>

			<a class="speakers-carousel__view-all" href="/speakers/">View all speakers</a>
		</div>

		<div class="speakers-carousel__toolbar">
			<div class="speakers-carousel__tabs">
				<button type="button" class="speakers-carousel__tab is-active">Featured</button>
				<button type="button" class="speakers-carousel__tab">Leadership</button>
				<button type="button" class="speakers-carousel__tab">Inspiration</button>
				<button type="button" class="speakers-carousel__tab">Business</button>
				<button type="button" class="speakers-carousel__tab">Technology</button>
			</div>

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
		</div>

		<?php if ($speakers->have_posts()) : ?>
			<div class="swiper speakers-carousel__swiper">
				<div class="swiper-wrapper speakers-carousel__slides">

					<?php
					$displayed_speakers = 0;

					while ($speakers->have_posts()) :
						$speakers->the_post();

						$speaker_id = get_the_ID();

						if (! has_post_thumbnail($speaker_id)) {
							continue;
						}

						if ($displayed_speakers >= 10) {
							break;
						}

						$displayed_speakers++;

						$speaker_categories = get_the_terms(
							$speaker_id,
							'speaker_category'
						);
					?>

						<div class="swiper-slide speakers-carousel__item">

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

						</div>

					<?php endwhile; ?>

				</div>
			</div>
		<?php endif; ?>

	</div>
</section>

<?php
wp_reset_postdata();
?>