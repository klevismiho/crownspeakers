<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$quotes     = $speaker_id ? Speaker_Data::testimonials( $speaker_id ) : array();

if ( ! $quotes ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="swiper speaker-quotes-carousel__swiper">
			<div class="swiper-wrapper">

				<?php foreach ( $quotes as $quote ) : ?>
					<figure class="swiper-slide speaker-quotes-carousel__item">

						<svg class="speaker-quotes-carousel__icon" width="40" height="32" viewBox="0 0 40 32" fill="currentColor" aria-hidden="true">
							<path d="M9.2 32C4 32 0 28.1 0 22.4 0 13.6 6.3 4.6 16 0l1.8 3C11.6 6.5 8.4 11 8 14.6c.4-.1.9-.1 1.3-.1 4.8 0 8.3 3.6 8.3 8.4 0 5.2-3.7 9.1-8.4 9.1zm22 0C26 32 22 28.1 22 22.4 22 13.6 28.3 4.6 38 0l1.8 3C33.6 6.5 30.4 11 30 14.6c.4-.1.9-.1 1.3-.1 4.8 0 8.3 3.6 8.3 8.4 0 5.2-3.7 9.1-8.4 9.1z" />
						</svg>

						<blockquote class="speaker-quotes-carousel__quote">
							<p>“<?php echo esc_html( $quote['quote'] ); ?>”</p>
						</blockquote>

						<?php if ( $quote['name'] ) : ?>
							<figcaption class="speaker-quotes-carousel__author">
								<span class="speaker-quotes-carousel__name"><?php echo esc_html( $quote['name'] ); ?></span>
							</figcaption>
						<?php endif; ?>

					</figure>
				<?php endforeach; ?>

			</div>
		</div>

		<div class="speaker-quotes-carousel__pagination"></div>

	</div>
</section>
