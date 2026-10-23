<?php

$page_id = (int) ( $block->context['postId'] ?? get_the_ID() );

$eyebrow = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );

// Title from the block, or the page's own title when left empty.
$title    = trim( (string) ( $attributes['title'] ?? '' ) );
$title    = '' !== wp_strip_all_tags( $title ) ? $title : esc_html( get_the_title( $page_id ) );
$subtitle = trim( (string) ( $attributes['subtitle'] ?? '' ) );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>

	<svg class="page-header__crown" viewBox="0 0 320 320" fill="none" aria-hidden="true">
		<defs>
			<linearGradient id="page-header-crown-fill" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0" stop-color="#fff" stop-opacity="0.16" />
				<stop offset="1" stop-color="#fff" stop-opacity="0" />
			</linearGradient>
		</defs>
		<path d="M30 110 85 190 110 50 160 140 200 30 245 150 290 55 300 320H60Z" fill="url(#page-header-crown-fill)" />
		<g fill="#142333" fill-opacity="0.45">
			<circle cx="30" cy="104" r="17" />
			<circle cx="110" cy="44" r="17" />
			<circle cx="200" cy="24" r="17" />
			<circle cx="290" cy="49" r="17" />
		</g>
	</svg>

	<div class="section-inner">
		<?php if ( '' !== $eyebrow ) : ?>
			<p class="page-header__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>

		<h1 class="page-header__title"><?php echo wp_kses_post( $title ); ?></h1>

		<?php if ( '' !== wp_strip_all_tags( $subtitle ) ) : ?>
			<p class="page-header__subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
		<?php endif; ?>
	</div>

</section>
