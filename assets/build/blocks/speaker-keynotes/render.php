<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$keynotes   = $speaker_id ? Speaker_Data::keynotes( $speaker_id ) : array();

if ( ! $keynotes ) {
	return;
}

$block_id = wp_unique_id( 'speaker-keynotes-' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="speaker-keynotes__intro">
			<p class="speaker-keynotes__eyebrow">Keynotes</p>
			<h2 class="section-title">Bring the right conversation into the room.</h2>
		</div>

		<ul class="speaker-keynotes__list">
			<?php
			foreach ( $keynotes as $index => $keynote ) :
				$description_id = $block_id . '-' . $index;
				?>
				<li class="speaker-keynotes__item">
					<h3 class="speaker-keynotes__title">
						<button type="button" class="speaker-keynotes__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $description_id ); ?>">
							<?php echo esc_html( $keynote['title'] ); ?>
						</button>
					</h3>

					<?php if ( $keynote['description'] ) : ?>
						<div class="speaker-keynotes__description" id="<?php echo esc_attr( $description_id ); ?>">
							<?php echo wp_kses_post( wpautop( $keynote['description'] ) ); ?>
						</div>
					<?php endif; ?>

					<span class="speaker-keynotes__chevron" aria-hidden="true">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none">
							<path d="m3 4.5 3 3 3-3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
