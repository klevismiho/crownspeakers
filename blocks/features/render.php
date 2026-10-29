<?php

// Title + features (icon, title, text), all edited in the block. Icons come from icons.json (shared with edit.js).
$title = trim( (string) ( $attributes['title'] ?? '' ) );
$items = array_filter(
	(array) ( $attributes['items'] ?? array() ),
	static fn( $item ) => is_array( $item ) && ( wp_strip_all_tags( $item['title'] ?? '' ) || wp_strip_all_tags( $item['text'] ?? '' ) )
);

// Read from the source folder: the block build only copies block.json, scripts and PHP files.
$icons_file = get_template_directory() . '/blocks/features/icons.json';
$icons      = file_exists( $icons_file ) ? json_decode( (string) file_get_contents( $icons_file ), true ) : array();

if ( ! $items && '' === wp_strip_all_tags( $title ) ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<?php if ( '' !== wp_strip_all_tags( $title ) ) : ?>
			<h2 class="features__title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<ul class="features__list">
				<?php
				foreach ( $items as $item ) :
					$icon = $icons[ $item['icon'] ?? '' ]['svg'] ?? ( $icons['heart']['svg'] ?? '' );
					?>
					<li class="features__item">
						<?php if ( $icon ) : ?>
							<span class="features__icon">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php echo $icon; // Theme file, not user input. ?>
								</svg>
							</span>
						<?php endif; ?>

						<?php if ( wp_strip_all_tags( $item['title'] ?? '' ) ) : ?>
							<h3 class="features__item-title"><?php echo wp_kses_post( $item['title'] ); ?></h3>
						<?php endif; ?>

						<?php if ( wp_strip_all_tags( $item['text'] ?? '' ) ) : ?>
							<p class="features__item-text"><?php echo wp_kses_post( $item['text'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

	</div>
</section>
