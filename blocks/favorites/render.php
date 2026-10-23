<?php

// The cards are rendered by view.js from the visitor's saved favorites (localStorage),
// with photo and tagline fetched from the theme's REST endpoint (inc/Rest_Api.php).
$endpoint     = rest_url( 'crownspeakers/v1/speakers' );
$speakers_url = home_url( '/all-speakers/' );
$contact_url  = home_url( '/contact/' );

// "Inquire about these speakers" reveals the inquiry form right under the bar, unless the page already
// has a Global Form section (the same WPForms form can't be on a page twice) — then it scrolls there.
$show_inquiry = \CS\Theme\Inquiry_Form::id() && ! has_block( 'crownspeakers/global-form', get_the_ID() );
$inquiry_id   = wp_unique_id( 'favorites-inquiry-' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'data-endpoint'    => $endpoint,
		'data-contact-url' => $contact_url,
	)
);
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<ul class="favorites__list" aria-live="polite"></ul>

		<div class="favorites__empty" hidden>
			<p>You haven’t added any speakers yet.</p>
			<a class="favorites__browse" href="<?php echo esc_url( $speakers_url ); ?>">Browse speakers</a>
		</div>

		<div class="favorites__bar" hidden>
			<p class="favorites__count"></p>
			<a
				class="favorites__inquire"
				href="<?php echo esc_url( $contact_url ); ?>"
				<?php if ( $show_inquiry ) : ?>
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $inquiry_id ); ?>"
				<?php endif; ?>>Inquire about these speakers</a>
		</div>

		<?php if ( $show_inquiry ) : ?>
			<div class="favorites__inquiry" id="<?php echo esc_attr( $inquiry_id ); ?>" hidden>
				<h2 class="favorites__inquiry-title">Inquire about these speakers</h2>
				<p class="favorites__inquiry-speakers"></p>

				<div class="favorites__inquiry-form">
					<?php \CS\Theme\Inquiry_Form::display(); ?>
				</div>
			</div>
		<?php endif; ?>

	</div>

</section>
