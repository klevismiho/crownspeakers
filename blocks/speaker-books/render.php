<?php

use CS\Theme\Speaker_Data;

$speaker_id = (int) ( $block->context['postId'] ?? get_the_ID() );
$books      = $speaker_id ? Speaker_Data::books( $speaker_id ) : array();

if ( ! $books ) {
	return;
}

$name = get_the_title( $speaker_id );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="speaker-books__intro">
			<p class="speaker-books__eyebrow">Books</p>
			<h2 class="section-title">Ideas that<br />go home with you.</h2>
		</div>

		<ul class="speaker-books__list">
			<?php
			foreach ( $books as $book ) :
				// No title field yet: screen readers get a generic label instead.
				$label = $book['title'] ? $book['title'] : sprintf( 'Book by %s', $name );
				$tag   = $book['url'] ? 'a' : 'div';
				?>
				<li class="speaker-books__item">
					<<?php echo $tag; ?> class="speaker-books__link"
						<?php if ( $book['url'] ) : ?>
							href="<?php echo esc_url( $book['url'] ); ?>" target="_blank" rel="noopener"
						<?php endif; ?>>

						<span class="speaker-books__cover">
							<?php if ( $book['image'] ) : ?>
								<img src="<?php echo esc_url( $book['image'] ); ?>" alt="<?php echo esc_attr( $book['title'] ? '' : $label ); ?>" loading="lazy" />
							<?php endif; ?>
						</span>

						<?php if ( $book['title'] ) : ?>
							<span class="speaker-books__title">
								<?php echo esc_html( $book['title'] ); ?>
								<?php if ( $book['url'] ) : ?>
									<svg width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true">
										<path d="M2 8 8 2M3.5 2H8v4.5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								<?php endif; ?>
							</span>
						<?php endif; ?>

						<?php if ( $book['url'] ) : ?>
							<span class="screen-reader-text">(opens in a new tab)</span>
						<?php endif; ?>

					</<?php echo $tag; ?>>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
