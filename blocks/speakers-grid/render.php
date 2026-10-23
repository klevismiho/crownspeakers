<?php

use CS\Theme\Speaker_Data;

$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
$paged   = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

$query_args = array(
	'post_type'      => 'speaker',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => Speaker_Data::visible_meta_query(),
);

if ( $keyword ) {
	$query_args['s'] = $keyword;
}

// Taxonomy filters: ?topic= / ?type= from the hero search, and the current topic on topic pages.
$tax_query = Speaker_Data::filters_tax_query();

if ( is_tax( 'speaker_category' ) ) {
	$tax_query[] = array(
		'taxonomy' => 'speaker_category',
		'terms'    => get_queried_object_id(),
	);
}

if ( $tax_query ) {
	$query_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
}

$speakers = new WP_Query( $query_args );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>

	<div class="speakers-grid__toolbar">
		<div class="section-inner">
			<button type="button" class="speakers-grid__refine">
				Refine results
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
					<path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
				</svg>
			</button>

			<div class="speakers-grid__sort">
				<label for="speakers-grid-sort">Sort by</label>
				<select id="speakers-grid-sort" name="sort">
					<option value="relevance">Relevance</option>
					<option value="name">Name A–Z</option>
					<option value="newest">Newest</option>
				</select>
			</div>
		</div>
	</div>

	<div class="section-inner">

		<?php if ( $speakers->have_posts() ) : ?>
			<ul class="speakers-grid__list">

				<?php
				while ( $speakers->have_posts() ) :
					$speakers->the_post();

					$speaker_id         = get_the_ID();
					$speaker_categories = Speaker_Data::categories( $speaker_id );
					$tagline            = Speaker_Data::tagline( $speaker_id );
					$fee                = Speaker_Data::fee( $speaker_id );
					$travels_from       = Speaker_Data::travels_from( $speaker_id );
					?>

					<li class="speakers-grid__item">

						<div class="speakers-grid__media">
							<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(
										'medium_large',
										array(
											'class'   => 'speakers-grid__image',
											'loading' => 'lazy',
											'alt'     => '',
										)
									);
								} else {
									echo '<span class="speakers-grid__image speakers-grid__image--empty"></span>';
								}
								?>
							</a>

							<button
								type="button"
								class="speakers-grid__favorite"
								aria-label="<?php echo esc_attr( sprintf( 'Add %s to favorites', get_the_title() ) ); ?>"
								aria-pressed="false"
								data-favorite
								data-speaker-id="<?php echo esc_attr( $speaker_id ); ?>"
								data-speaker-name="<?php echo esc_attr( get_the_title() ); ?>"
								data-speaker-url="<?php echo esc_url( get_permalink() ); ?>"
								data-speaker-image="<?php echo esc_url( (string) get_the_post_thumbnail_url( $speaker_id, 'thumbnail' ) ); ?>">
								<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
									<path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
								</svg>
							</button>
						</div>

						<h3 class="speakers-grid__name">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<?php if ( $tagline ) : ?>
							<p class="speakers-grid__tagline"><?php echo esc_html( $tagline ); ?></p>
						<?php endif; ?>

						<?php if ( $speaker_categories ) : ?>
							<ul class="speakers-grid__categories">
								<?php foreach ( $speaker_categories as $speaker_category ) : ?>
									<li class="speakers-grid__category"><?php echo esc_html( $speaker_category ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( $fee || $travels_from ) : ?>
							<div class="speakers-grid__meta">
								<?php if ( $fee ) : ?>
									<span class="speakers-grid__fee"><?php echo esc_html( $fee ); ?></span>
								<?php endif; ?>
								<?php if ( $travels_from ) : ?>
									<span class="speakers-grid__travels">Travels from <?php echo esc_html( $travels_from ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</li>

				<?php endwhile; ?>

			</ul>

			<?php
			$pagination = paginate_links(
				array(
					'total'     => $speakers->max_num_pages,
					'current'   => $paged,
					'prev_text' => '←',
					'next_text' => '→',
				)
			);

			if ( $pagination ) :
				?>
				<nav class="speakers-grid__pagination" aria-label="Speakers pagination">
					<?php echo $pagination; ?>
				</nav>
			<?php endif; ?>

		<?php else : ?>
			<p class="speakers-grid__empty">No speakers found<?php echo $keyword ? ' for “' . esc_html( $keyword ) . '”' : ''; ?>.</p>
		<?php endif; ?>

	</div>

</section>

<?php
wp_reset_postdata();
?>
