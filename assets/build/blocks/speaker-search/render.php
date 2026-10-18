<?php

$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<h1 class="speaker-search__title">Find your next <span>great speaker.</span></h1>

		<form class="speaker-search__form" action="" method="get" role="search">
			<label for="speaker-search-keyword" class="screen-reader-text">Search by keyword, industry, or topic</label>

			<svg class="speaker-search__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<circle cx="13" cy="11" r="7" />
				<path d="m3 21 5-5" />
			</svg>

			<input
				type="search"
				id="speaker-search-keyword"
				name="keyword"
				value="<?php echo esc_attr( $keyword ); ?>"
				placeholder="Search by keyword, industry, or topic" />

			<button type="submit" class="speaker-search__submit">Find my speaker</button>
		</form>

	</div>
</section>
