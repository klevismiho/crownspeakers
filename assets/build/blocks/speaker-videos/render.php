<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$videos     = $speaker_id ? Speaker_Data::videos( $speaker_id ) : array();

if ( ! $videos ) {
	return;
}

$name = get_the_title( $speaker_id );

// Used when a video has no oEmbed thumbnail.
$fallback_thumbnail = has_post_thumbnail( $speaker_id ) ? get_the_post_thumbnail_url( $speaker_id, 'large' ) : '';

// The `videos` id lets the speaker hero's "Watch … in action" button link here.
$wrapper_attributes = get_block_wrapper_attributes( array( 'id' => 'videos' ) );
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<p class="speaker-videos__eyebrow">Videos</p>
		<h2 class="section-title"><?php echo esc_html( $name . ' in action' ); ?></h2>

		<ul class="speaker-videos__list">
			<?php
			foreach ( $videos as $video ) :
				$thumbnail = $video['thumbnail'] ? $video['thumbnail'] : $fallback_thumbnail;
				$title     = $video['title'] ? $video['title'] : $name;
				?>
				<li>
					<a class="speaker-videos__item" href="<?php echo esc_url( $video['url'] ); ?>" target="_blank" rel="noopener">
						<?php if ( $thumbnail ) : ?>
							<img src="<?php echo esc_url( $thumbnail ); ?>" alt="" loading="lazy" />
						<?php else : ?>
							<span class="speaker-videos__placeholder"></span>
						<?php endif; ?>

						<span class="speaker-videos__play">
							<svg width="14" height="14" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true">
								<path d="M3 1.4v9.2a.5.5 0 0 0 .76.43l7.4-4.6a.5.5 0 0 0 0-.86L3.76.97A.5.5 0 0 0 3 1.4z" />
							</svg>
						</span>

						<span class="screen-reader-text">Play video: <?php echo esc_html( $title ); ?> (opens in a new tab)</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
