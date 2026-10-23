<?php

// "Favorites" link with the saved-speaker count (kept up to date by view.js from localStorage).
$favorites_url = home_url( '/favorites/' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?>>

	<a class="header-favorites__link" href="<?php echo esc_url( $favorites_url ); ?>">
		<span class="header-favorites__label">Favorites</span>
		<span class="header-favorites__count">
			<span class="header-favorites__count-number" data-favorites-count>0</span>
			<span class="screen-reader-text">saved speakers</span>
		</span>
	</a>

</div>
