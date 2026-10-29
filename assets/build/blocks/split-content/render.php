<?php

// Eyebrow + title (attributes) on the left, the block's paragraphs (inner blocks, $content) on the right.
$eyebrow = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );
$title   = trim( (string) ( $attributes['title'] ?? '' ) );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="split-content__heading">
			<?php if ( '' !== $eyebrow ) : ?>
				<p class="split-content__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( '' !== wp_strip_all_tags( $title ) ) : ?>
				<h2 class="split-content__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( trim( $content ) ) : ?>
			<div class="split-content__text">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
