<?php

// Left: eyebrow + title (attributes) and paragraphs (inner blocks, $content).
// Right: features (icon, title, text). Icons come from blocks/features/icons.json (shared with edit.js).
$eyebrow = trim( wp_strip_all_tags( (string) ( $attributes['eyebrow'] ?? '' ) ) );
$title   = trim( (string) ( $attributes['title'] ?? '' ) );
$items   = array_filter(
	(array) ( $attributes['items'] ?? array() ),
	static fn( $item ) => is_array( $item ) && ( wp_strip_all_tags( $item['title'] ?? '' ) || wp_strip_all_tags( $item['text'] ?? '' ) )
);

// Read from the source folder: the block build only copies block.json, scripts and PHP files.
$icons_file = get_template_directory() . '/blocks/features/icons.json';
$icons      = file_exists( $icons_file ) ? json_decode( (string) file_get_contents( $icons_file ), true ) : array();

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="split-content-with-features__content">
			<?php if ( '' !== $eyebrow ) : ?>
				<p class="split-content-with-features__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<?php if ( '' !== wp_strip_all_tags( $title ) ) : ?>
				<h2 class="split-content-with-features__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( trim( $content ) ) : ?>
				<div class="split-content-with-features__text">
					<?php echo $content; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $items ) : ?>
			<ul class="split-content-with-features__list">
				<?php
				foreach ( $items as $item ) :
					$icon = $icons[ $item['icon'] ?? '' ]['svg'] ?? ( $icons['check']['svg'] ?? '' );
					?>
					<li class="split-content-with-features__item">
						<span class="split-content-with-features__icon">
							<?php if ( $icon ) : ?>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php echo $icon; // Theme file, not user input. ?>
								</svg>
							<?php endif; ?>
						</span>

						<div class="split-content-with-features__item-body">
							<?php if ( wp_strip_all_tags( $item['title'] ?? '' ) ) : ?>
								<h3 class="split-content-with-features__item-title"><?php echo wp_kses_post( $item['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( wp_strip_all_tags( $item['text'] ?? '' ) ) : ?>
								<p class="split-content-with-features__item-text"><?php echo wp_kses_post( $item['text'] ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

	</div>
</section>
