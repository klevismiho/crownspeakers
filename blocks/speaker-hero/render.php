<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );

if ( ! $speaker_id ) {
	return;
}

// First name in white, everything after it highlighted.
[ $first_name, $last_name ] = Speaker_Data::name_parts( $speaker_id );

$name         = get_the_title( $speaker_id );
$tagline      = Speaker_Data::tagline( $speaker_id );
$has_videos   = ! empty( Speaker_Data::videos( $speaker_id ) );
$is_exclusive = Speaker_Data::is_exclusive( $speaker_id );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="speaker-hero__content">
			<p class="speaker-hero__eyebrow">Meet</p>

			<h1 class="speaker-hero__name">
				<?php echo esc_html( $first_name ); ?>
				<?php if ( $last_name ) : ?>
					<span><?php echo esc_html( $last_name ); ?></span>
				<?php endif; ?>
			</h1>

			<?php if ( $tagline ) : ?>
				<p class="speaker-hero__tagline"><?php echo esc_html( $tagline ); ?></p>
			<?php endif; ?>

			<div class="speaker-hero__actions">
				<a class="speaker-hero__button speaker-hero__button--primary" href="#inquiry">Request more info</a>

				<?php if ( $has_videos ) : ?>
					<a class="speaker-hero__button speaker-hero__button--outline" href="#videos">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true">
							<path d="M2 1.2v9.6a.5.5 0 0 0 .76.43l7.8-4.8a.5.5 0 0 0 0-.86l-7.8-4.8A.5.5 0 0 0 2 1.2z" />
						</svg>
						Watch <?php echo esc_html( $name ); ?> in action
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="speaker-hero__media">
			<?php
			if ( has_post_thumbnail( $speaker_id ) ) {
				echo get_the_post_thumbnail(
					$speaker_id,
					'large',
					array(
						'class' => 'speaker-hero__image',
						'alt'   => '',
					)
				);
			} else {
				echo '<span class="speaker-hero__image speaker-hero__image--empty"></span>';
			}
			?>

			<?php if ( $is_exclusive ) : ?>
				<span class="speaker-hero__badge">
					<svg width="18" height="14" viewBox="0 0 18 14" fill="none" aria-hidden="true">
						<path d="M1.5 4 5 9l4-7 4 7 3.5-5-1.5 9h-12z" fill="var(--color-secondary)" />
					</svg>
					Exclusive speaker
				</span>
			<?php endif; ?>

			<button
				type="button"
				class="speaker-hero__favorite"
				aria-pressed="false"
				data-favorite
				data-speaker-id="<?php echo esc_attr( $speaker_id ); ?>"
				data-speaker-name="<?php echo esc_attr( $name ); ?>"
				data-speaker-url="<?php echo esc_url( get_permalink( $speaker_id ) ); ?>"
				data-speaker-image="<?php echo esc_url( (string) get_the_post_thumbnail_url( $speaker_id, 'thumbnail' ) ); ?>">
				<span data-favorite-label>Add to Favorites</span>
				<svg width="12" height="12" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
				</svg>
			</button>
		</div>

	</div>
</section>
