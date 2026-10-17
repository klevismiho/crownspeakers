<?php

$quotes = array(
	array(
		'quote'  => 'The keynote speakers and trainers you recommended were, as you promised, fantastic!',
		'name'   => 'Deena Palmer',
		'detail' => 'Department of Transportation',
	),
	array(
		'quote'  => 'Quote text goes here. Replace this with a real client testimonial.',
		'name'   => 'Client Name',
		'detail' => 'Company',
	),
	array(
		'quote'  => 'Quote text goes here. Replace this with a real client testimonial.',
		'name'   => 'Client Name',
		'detail' => 'Company',
	),
);

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="quotes-slider__inner">

		<div class="swiper quotes-slider__swiper">
			<div class="swiper-wrapper">

				<?php foreach ( $quotes as $quote ) : ?>
					<figure class="swiper-slide quotes-slider__slide">
						<blockquote class="quotes-slider__quote">
							<p>“<?php echo esc_html( $quote['quote'] ); ?>”</p>
						</blockquote>
						<figcaption class="quotes-slider__author">
							<span class="quotes-slider__name"><?php echo esc_html( $quote['name'] ); ?></span>
							<span class="quotes-slider__detail"><?php echo esc_html( $quote['detail'] ); ?></span>
						</figcaption>
					</figure>
				<?php endforeach; ?>

			</div>
		</div>

		<div class="quotes-slider__pagination"></div>

	</div>
</section>
