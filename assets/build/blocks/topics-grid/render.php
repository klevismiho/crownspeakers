<?php

use CS\Theme\Speaker_Data;

// Every speaker category that has speakers, A–Z.
$topics = taxonomy_exists( 'speaker_category' )
	? get_terms(
		array(
			'taxonomy'   => 'speaker_category',
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	)
	: array();

if ( empty( $topics ) || is_wp_error( $topics ) ) {
	return;
}

$heading = trim( (string) ( $attributes['heading'] ?? '' ) );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<?php if ( '' !== wp_strip_all_tags( $heading ) ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $heading ); ?></h2>
		<?php endif; ?>

		<ul class="topics-grid__list">
			<?php foreach ( $topics as $topic ) : ?>
				<li>
					<a class="topics-grid__card" href="<?php echo esc_url( get_term_link( $topic ) ); ?>">

						<span class="topics-grid__icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<?php echo Speaker_Data::topic_icon( $topic->name ); ?>
							</svg>
						</span>

						<h3 class="topics-grid__title">
							<?php echo esc_html( $topic->name ); ?>
						</h3>

						<span class="topics-grid__link" aria-hidden="true">
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
								<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>

					</a>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
