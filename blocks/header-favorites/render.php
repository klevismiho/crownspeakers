<?php

// The list is filled in by view.js from the visitor's saved favorites (localStorage).
$panel_id = wp_unique_id( 'header-favorites-panel-' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?>>

	<button type="button" class="header-favorites__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
		<span class="header-favorites__label">Favorites</span>
		<span class="header-favorites__count" aria-live="polite">
			<span class="header-favorites__count-number">0</span>
			<span class="screen-reader-text">saved speakers</span>
		</span>
	</button>

	<div class="header-favorites__panel" id="<?php echo esc_attr( $panel_id ); ?>" hidden>
		<p class="header-favorites__title">Your favorite speakers</p>

		<ul class="header-favorites__list"></ul>

		<p class="header-favorites__empty">
			No favorites yet. Tap <strong>+</strong> on a speaker to save them here.
		</p>
	</div>

</div>
