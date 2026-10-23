<?php

$background_path = '/assets/src/images/global-form-bg.jpg';
$background_url  = file_exists( get_template_directory() . $background_path )
	? get_template_directory_uri() . $background_path
	: '';

// The `inquiry` id lets other blocks link to the form (e.g. "Request more info").
$extra_attributes = array( 'id' => 'inquiry' );

if ( $background_url ) {
	$extra_attributes['style'] = '--global-form-bg: url(' . esc_url( $background_url ) . ');';
}

$wrapper_attributes = get_block_wrapper_attributes( $extra_attributes );

// WPForms "Event Inquiry" form shown in the right column (ID set in inc/Inquiry_Form.php).
// If WPForms is off or the form doesn't exist, the static fallback form below is shown.
$wpforms_id = \CS\Theme\Inquiry_Form::id();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<header class="global-form__intro">
			<h2 class="global-form__heading">Let’s make your event<br /><span>one to remember.</span></h2>
			<p>We’ll help you find the vision to bring it to life.</p>
		</header>

		<div class="global-form__columns">

			<div class="global-form__content">
				<h3 class="global-form__subheading">Great events start with a conversation</h3>
				<p>Looking for the right speaker or exploring ideas for your next event? We’re here to help.</p>
				<p>Let us know what you have in mind. Whether you have a detailed summary or are just getting started, our team can help you explore speakers, check availability, and work through the details.</p>
				<p>Prefer to call us? Talk to one of our talent experts:</p>

				<a class="global-form__phone" href="tel:+18778825368">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1L6.6 10.8z" />
					</svg>
					1 (877) 882-5368
				</a>
			</div>

			<?php if ( $wpforms_id ) : ?>

				<div class="global-form__form global-form__form--wpforms">
					<h3 class="global-form__form-title">Let us know what you have in mind</h3>
					<?php \CS\Theme\Inquiry_Form::display(); ?>
				</div>

			<?php else : ?>

			<form class="global-form__form" action="#" method="post">
				<h3 class="global-form__form-title">Let us know what you have in mind</h3>

				<div class="global-form__fields">
					<div class="global-form__field">
						<label for="global-form-name">Your Name</label>
						<input type="text" id="global-form-name" name="name" placeholder="Full name" autocomplete="name" />
					</div>

					<div class="global-form__field">
						<label for="global-form-organization">Organization</label>
						<input type="text" id="global-form-organization" name="organization" placeholder="Organization name" autocomplete="organization" />
					</div>

					<div class="global-form__field">
						<label for="global-form-date">Event Date</label>
						<input type="date" id="global-form-date" name="event_date" />
					</div>

					<div class="global-form__field">
						<label for="global-form-location">Location</label>
						<input type="text" id="global-form-location" name="location" placeholder="City or virtual" />
					</div>

					<div class="global-form__field">
						<label for="global-form-format">Event Format</label>
						<input type="text" id="global-form-format" name="event_format" placeholder="Keynote, Q&amp;A, fireside chat, meet &amp; greet, etc." />
					</div>

					<div class="global-form__field">
						<label for="global-form-budget">Budget Range</label>
						<input type="text" id="global-form-budget" name="budget" placeholder="Preferred budget range" />
					</div>

					<div class="global-form__field global-form__field--full">
						<label for="global-form-previous">Previous Speakers</label>
						<input type="text" id="global-form-previous" name="previous_speakers" placeholder="Please share with us any speakers you’ve booked for your previous events." />
					</div>

					<div class="global-form__field global-form__field--full">
						<label for="global-form-details">Event Details</label>
						<textarea id="global-form-details" name="event_details" rows="4" placeholder="Please share with us details on your event, your vision, and the event theme."></textarea>
					</div>
				</div>

				<div class="global-form__actions">
					<button type="submit" class="global-form__submit">Submit Inquiry</button>
				</div>
			</form>

			<?php endif; ?>

		</div>

	</div>
</section>
