<?php

$current_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$limit      = 10;

$base_args = array(
	'post_type'           => 'speaker',
	'post_status'         => 'publish',
	'orderby'             => 'menu_order',
	'order'               => 'ASC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'fields'              => 'ids',
	// Only visible speakers with a photo.
	'meta_query'          => array(
		'relation' => 'AND',
		array(
			'key'     => '_thumbnail_id',
			'compare' => 'EXISTS',
		),
		\CS\Theme\Speaker_Data::visible_meta_query(),
	),
);

// Only speakers whose photo really exists, minus the given IDs. (WordPress ignores post__not_in
// when post__in is set, so excluded speakers are removed from the list here.)
$photo_ids        = \CS\Theme\Speaker_Data::ids_with_photo();
$photo_ids_except = static function ( array $exclude ) use ( $photo_ids ) {
	$ids = array_values( array_diff( $photo_ids, array_map( 'intval', $exclude ) ) );

	return $ids ? $ids : array( 0 );
};

// First: speakers sharing a category with the current speaker.
$category_ids = $current_id ? wp_get_post_terms( $current_id, 'speaker_category', array( 'fields' => 'ids' ) ) : array();
$speaker_ids  = array();

if ( ! empty( $category_ids ) && ! is_wp_error( $category_ids ) ) {
	$speaker_ids = get_posts(
		$base_args + array(
			'posts_per_page' => $limit,
			'post__in'       => $photo_ids_except( array( $current_id ) ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'speaker_category',
					'terms'    => $category_ids,
				),
			),
		)
	);
}

// Then: top up with other speakers if there aren't enough similar ones.
if ( count( $speaker_ids ) < $limit ) {
	$speaker_ids = array_merge(
		$speaker_ids,
		get_posts(
			$base_args + array(
				'posts_per_page' => $limit - count( $speaker_ids ),
				'post__in'       => $photo_ids_except( array_merge( array( $current_id ), $speaker_ids ) ),
			)
		)
	);
}

if ( empty( $speaker_ids ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="section-header">
			<h2 class="section-title">Similar speakers</h2>

			<div class="similar-speakers-carousel__actions">
				<a class="similar-speakers-carousel__view-all" href="/all-speakers/">View all speakers</a>

				<div class="similar-speakers-carousel__nav">
					<button type="button" class="similar-speakers-carousel__arrow similar-speakers-carousel__arrow--prev" aria-label="Previous speakers">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</button>
					<button type="button" class="similar-speakers-carousel__arrow similar-speakers-carousel__arrow--next" aria-label="Next speakers">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
							<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</button>
				</div>
			</div>
		</div>

		<div class="swiper similar-speakers-carousel__swiper">
			<div class="swiper-wrapper similar-speakers-carousel__slides">

				<?php
				foreach ( $speaker_ids as $speaker_id ) :
					$speaker_categories = get_the_terms( $speaker_id, 'speaker_category' );
					?>

					<div class="swiper-slide similar-speakers-carousel__item">

						<a href="<?php echo esc_url( get_permalink( $speaker_id ) ); ?>">

							<?php
							echo get_the_post_thumbnail(
								$speaker_id,
								'medium_large',
								array(
									'alt'     => get_post_meta(
										get_post_thumbnail_id( $speaker_id ),
										'_wp_attachment_image_alt',
										true
									),
									'loading' => 'lazy',
								)
							);
							?>

							<h3 class="similar-speakers-carousel__name">
								<?php echo esc_html( get_the_title( $speaker_id ) ); ?>
							</h3>

							<?php if ( ! empty( $speaker_categories ) && ! is_wp_error( $speaker_categories ) ) : ?>
								<div class="similar-speakers-carousel__categories">
									<?php foreach ( $speaker_categories as $speaker_category ) : ?>
										<span class="similar-speakers-carousel__category">
											<?php echo esc_html( $speaker_category->name ); ?>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						</a>

					</div>

				<?php endforeach; ?>

			</div>
		</div>

	</div>
</section>
