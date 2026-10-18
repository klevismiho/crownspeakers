<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );

if ( ! $speaker_id ) {
	return;
}

$name       = get_the_title( $speaker_id );
$highlights = Speaker_Data::highlights( $speaker_id );
$has_image  = has_post_thumbnail( $speaker_id );

// Facts strip; empty facts are skipped.
$facts = array_filter(
	array(
		'Topics'       => implode( ', ', Speaker_Data::categories( $speaker_id ) ),
		'Travels from' => Speaker_Data::travels_from( $speaker_id ),
		'Speaking fee' => Speaker_Data::fee( $speaker_id ),
	)
);

$bio = apply_filters( 'the_content', get_post_field( 'post_content', $speaker_id ) );

$bio_id = wp_unique_id( 'speaker-details-bio-' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>

	<?php if ( $facts ) : ?>
		<div class="speaker-details__facts">
			<dl class="section-inner">
				<?php foreach ( $facts as $label => $value ) : ?>
					<div class="speaker-details__fact<?php echo 'Topics' === $label ? ' speaker-details__fact--wide' : ''; ?>">
						<dt><?php echo esc_html( $label ); ?></dt>
						<dd><?php echo esc_html( $value ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php if ( $highlights || $has_image ) : ?>
		<div class="speaker-details__about">
			<div class="speaker-details__inner">
				<h2 class="speaker-details__heading">About <?php echo esc_html( $name ); ?></h2>

				<div class="speaker-details__summary">
					<?php
					if ( $has_image ) {
						echo get_the_post_thumbnail(
							$speaker_id,
							'medium',
							array(
								'class'   => 'speaker-details__image',
								'alt'     => '',
								'loading' => 'lazy',
							)
						);
					}
					?>

					<?php if ( $highlights ) : ?>
						<ul class="speaker-details__highlights">
							<?php foreach ( $highlights as $highlight ) : ?>
								<li><?php echo esc_html( $highlight ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( trim( wp_strip_all_tags( $bio ) ) ) : ?>
		<div class="speaker-details__bio-wrapper">
			<div class="speaker-details__inner">
				<div class="speaker-details__bio" id="<?php echo esc_attr( $bio_id ); ?>">
					<?php echo $bio; ?>
				</div>

				<button type="button" class="speaker-details__more" aria-expanded="false" aria-controls="<?php echo esc_attr( $bio_id ); ?>" hidden>
					Read more
				</button>
			</div>
		</div>
	<?php endif; ?>

</section>
