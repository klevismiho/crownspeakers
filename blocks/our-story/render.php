<?php

// Left: eyebrow + image. Right: title (attribute) and paragraphs (inner blocks, $content).
$eyebrow   = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );
$title     = trim( (string) ( $attributes['title'] ?? '' ) );
$image_id  = (int) ( $attributes['imageId'] ?? 0 );
$image_url = (string) ( $attributes['imageUrl'] ?? '' );
$image_alt = (string) ( $attributes['imageAlt'] ?? '' );

// Prefer the Media Library image (responsive srcset); fall back to the saved URL.
$image = $image_id
	? wp_get_attachment_image(
		$image_id,
		'large',
		false,
		array(
			'class'   => 'our-story__image',
			'alt'     => $image_alt,
			'loading' => 'lazy',
			'sizes'   => '(min-width: 1024px) 50vw, 100vw',
		)
	)
	: '';

if ( ! $image && $image_url ) {
	$image = sprintf(
		'<img class="our-story__image" src="%s" alt="%s" loading="lazy" />',
		esc_url( $image_url ),
		esc_attr( $image_alt )
	);
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="our-story__media">
			<?php if ( '' !== $eyebrow ) : ?>
				<p class="our-story__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<?php echo $image; ?>
		</div>

		<div class="our-story__content">
			<?php if ( '' !== wp_strip_all_tags( $title ) ) : ?>
				<h2 class="our-story__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( trim( $content ) ) : ?>
				<div class="our-story__text">
					<?php echo $content; ?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
