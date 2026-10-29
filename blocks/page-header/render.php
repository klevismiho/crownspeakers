<?php

$page_id = (int) ( $block->context['postId'] ?? get_the_ID() );

$eyebrow  = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );
$title    = trim( (string) ( $attributes['title'] ?? '' ) );
$subtitle = trim( (string) ( $attributes['subtitle'] ?? '' ) );

// Empty title / paragraph fall back to the current page — or, on a category/topic archive
// (e.g. templates/taxonomy-speaker_category.html), to the term's name and description.
// Checked first: on archives the "current post" can be the first item in the list.
$term = ( is_category() || is_tag() || is_tax() ) ? get_queried_object() : null;

if ( '' === wp_strip_all_tags( $title ) ) {
	$title = $term instanceof WP_Term ? esc_html( $term->name ) : esc_html( get_the_title( $page_id ) );
}

if ( '' === wp_strip_all_tags( $subtitle ) && $term instanceof WP_Term && '' !== trim( $term->description ) ) {
	$subtitle = esc_html( wp_strip_all_tags( $term->description ) );
}

// Topic pages only: a "View all Topics" link back to the topics overview.
$back_link = is_tax( 'speaker_category' ) ? home_url( '/topics/' ) : '';

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

	<?php if ( $back_link ) : ?>
		<div class="page-header__back-wrap">
			<a class="page-header__back" href="<?php echo esc_url( $back_link ); ?>">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
				<span>View all Topics</span>
			</a>
		</div>
	<?php endif; ?>

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
