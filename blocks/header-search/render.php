<?php

// Searches go to the Speakers page, where the speakers-grid block reads ?keyword=.
$search_url = home_url( '/all-speakers/' );
$keyword    = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';

$panel_id = wp_unique_id( 'header-search-panel-' );
$input_id = wp_unique_id( 'header-search-input-' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<div <?php echo $wrapper_attributes; ?>>

	<button type="button" class="header-search__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
		<svg class="header-search__icon-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="11" cy="11" r="8" />
			<path d="m21 21-4.35-4.35" />
		</svg>
		<svg class="header-search__icon-close" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
			<path d="M6 6l12 12M18 6 6 18" />
		</svg>
		<span class="screen-reader-text">Search</span>
	</button>

	<div class="header-search__panel" id="<?php echo esc_attr( $panel_id ); ?>" hidden>
		<div class="header-search__inner">

			<p class="header-search__title">What are you looking for?</p>

			<form class="header-search__form" action="<?php echo esc_url( $search_url ); ?>" method="get" role="search">
				<label for="<?php echo esc_attr( $input_id ); ?>" class="screen-reader-text">Search by keyword, industry, or topic</label>

				<svg class="header-search__form-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="13" cy="11" r="7" />
					<path d="m3 21 5-5" />
				</svg>

				<input
					type="search"
					id="<?php echo esc_attr( $input_id ); ?>"
					name="keyword"
					value="<?php echo esc_attr( $keyword ); ?>"
					placeholder="Search by keyword, industry, or topic" />

				<button type="submit" class="header-search__submit">Search</button>
			</form>

		</div>
	</div>

</div>
