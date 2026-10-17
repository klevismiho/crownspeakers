<?php

// Core button styles only load when a core/button block is on the page.
wp_enqueue_style( 'wp-block-button' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="split-with-image__media">
			<img
				class="split-with-image__image"
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/src/images/featured-image.jpg' ); ?>"
				alt=""
				loading="lazy" />
		</div>

		<div class="split-with-image__content">
			<h2 class="section-title">You bring the vision.<br />We bring your people.</h2>

			<div class="split-with-image__body">
				<p>We take the time to understand your event, your audience and what you hope they’ll take away. Then we help you find a speaker who fits, with honest advice and practical support throughout the booking process.</p>
				<div class="wp-block-buttons">
					<div class="wp-block-button">
						<a class="wp-block-button__link wp-element-button" href="/contact/">Contact Us</a>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
