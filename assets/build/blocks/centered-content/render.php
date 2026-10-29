<?php

// Optional eyebrow + title (attributes), then the block's paragraphs (inner blocks, $content), all centered.
$eyebrow = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );
$title   = trim( (string) ( $attributes['title'] ?? '' ) );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<?php if ( '' !== $eyebrow ) : ?>
			<p class="centered-content__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== wp_strip_all_tags( $title ) ) : ?>
			<h2 class="centered-content__title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( trim( $content ) ) : ?>
			<div class="centered-content__text">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
