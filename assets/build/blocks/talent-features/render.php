<?php

$features = array(
	array(
		'title' => 'Talent experts, not salespeople.',
		'text'  => 'Curated speaker recommendations that start with your goals, not a list to sell.',
	),
	array(
		'title' => 'An answer in 90 minutes.',
		'text'  => 'Typical response time during business hours, and never longer than 24 hours.',
	),
	array(
		'title' => 'Expert help at no extra cost.',
		'text'  => 'Research, booking and event-day support are free. You pay only the speaker’s fee.',
	),
);

$images_url = get_template_directory_uri() . '/assets/src/images/';

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="talent-features__content">
			<h2 class="section-title">A world of talent.<br />A personal connection.</h2>

			<ol class="talent-features__list">
				<?php foreach ( $features as $index => $feature ) : ?>
					<li class="talent-features__item">
						<span class="talent-features__number">
							<?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?>
						</span>
						<div>
							<h3 class="talent-features__title"><?php echo esc_html( $feature['title'] ); ?></h3>
							<p class="talent-features__text"><?php echo esc_html( $feature['text'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>

		<div class="talent-features__media" aria-hidden="true">
			<span class="talent-features__circle"></span>

			<svg class="talent-features__crown" viewBox="0 0 200 160" fill="none">
				<defs>
					<linearGradient id="talent-features-crown" x1="0" y1="0" x2="0" y2="1">
						<stop offset="0" stop-color="#e5e7eb" />
						<stop offset="1" stop-color="#f3f4f6" />
					</linearGradient>
				</defs>
				<path d="M15 60 50 115 55 30 80 105 100 15 120 105 145 30 150 115 185 60 160 150H40Z" fill="url(#talent-features-crown)" />
				<g fill="#d1d5db">
					<circle cx="15" cy="60" r="7" />
					<circle cx="55" cy="30" r="7" />
					<circle cx="100" cy="15" r="7" />
					<circle cx="145" cy="30" r="7" />
					<circle cx="185" cy="60" r="7" />
				</g>
			</svg>

			<img class="talent-features__image talent-features__image--1" src="<?php echo esc_url( $images_url . 'hero-image-1.jpg' ); ?>" alt="" loading="lazy" />
			<img class="talent-features__image talent-features__image--2" src="<?php echo esc_url( $images_url . 'hero-image-2.jpg' ); ?>" alt="" loading="lazy" />
			<img class="talent-features__image talent-features__image--3" src="<?php echo esc_url( $images_url . 'hero-image-3.jpg' ); ?>" alt="" loading="lazy" />
			<img class="talent-features__image talent-features__image--4" src="<?php echo esc_url( $images_url . 'hero-image-4.jpg' ); ?>" alt="" loading="lazy" />
		</div>

	</div>
</section>
