<?php

use CS\Theme\Inquiry_Form;

$phone_label = '1 (877) 882-5368';
$phone_href  = 'tel:+18778825368';
$email       = 'Info@CrownSpeakers.com';

$office = array(
	'address' => array( '320 S. Boston Ave, Suite 1026', 'Tulsa, Oklahoma 74103' ),
	'hours'   => array( 'Monday–Friday', '8:00 am–5:00 pm Central Time' ),
);

// The `inquiry` id lets buttons elsewhere link to the form (e.g. "Request more info"),
// like the Global Form section. Don't put both blocks on one page: the form can only appear once.
$wrapper_attributes = get_block_wrapper_attributes( array( 'id' => 'inquiry' ) );
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="contact__details">
			<h2 class="contact__title">What do you<br />have in mind?</h2>
			<p class="contact__intro">You don’t need every detail worked out. A little about your audience and what you hope to achieve is a useful place to start.</p>

			<ul class="contact__links">
				<li>
					<a class="contact__link" href="<?php echo esc_attr( $phone_href ); ?>">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1L6.6 10.8z" />
						</svg>
						<?php echo esc_html( $phone_label ); ?>
					</a>
				</li>
				<li>
					<a class="contact__link" href="<?php echo esc_attr( 'mailto:' . $email ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M3 6.5A1.5 1.5 0 0 1 4.5 5h15A1.5 1.5 0 0 1 21 6.5v.3l-9 5.6-9-5.6v-.3zm0 2.6v8.4A1.5 1.5 0 0 0 4.5 19h15a1.5 1.5 0 0 0 1.5-1.5V9.1l-9 5.6-9-5.6z" />
						</svg>
						<?php echo esc_html( $email ); ?>
					</a>
				</li>
			</ul>

			<div class="contact__office">
				<p class="contact__eyebrow">Our office</p>

				<address class="contact__address">
					<?php echo implode( '<br />', array_map( 'esc_html', $office['address'] ) ); ?>
				</address>

				<p class="contact__hours">
					<?php echo implode( '<br />', array_map( 'esc_html', $office['hours'] ) ); ?>
				</p>
			</div>
		</div>

		<div class="contact__form">
			<h2 class="contact__form-title">A little about your event</h2>

			<?php if ( Inquiry_Form::id() ) : ?>
				<?php Inquiry_Form::display(); ?>
			<?php else : ?>
				<p class="contact__form-fallback">
					Our inquiry form is unavailable right now. Call us at
					<a href="<?php echo esc_attr( $phone_href ); ?>"><?php echo esc_html( $phone_label ); ?></a>
					or email
					<a href="<?php echo esc_attr( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>.
				</p>
			<?php endif; ?>
		</div>

	</div>
</section>
