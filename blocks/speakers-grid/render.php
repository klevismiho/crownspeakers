<?php

use CS\Theme\Speaker_Data;

$keyword  = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';
$paged    = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$selected = Speaker_Data::selected_filters();

// Sort options (?sort=). "Relevance": featured speakers first, then best search matches, then admin order.
$sort_options = array(
	'relevance' => 'Relevance',
	'name'      => 'Name A–Z',
	'newest'    => 'Newest',
);
$sort         = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'relevance';
$sort         = isset( $sort_options[ $sort ] ) ? $sort : 'relevance';

switch ( $sort ) {
	case 'name':
		$orderby = array( 'title' => 'ASC' );
		break;

	case 'newest':
		$orderby = array( 'date' => 'DESC' );
		break;

	default:
		$orderby = array( Speaker_Data::FEATURED_ORDER_CLAUSE => 'DESC' );

		if ( $keyword ) {
			$orderby['relevance'] = 'DESC';
		}

		$orderby['menu_order'] = 'ASC';
}

$query_args = array(
	'post_type'      => 'speaker',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $paged,
	// Only speakers whose photo really exists (set, in the Media Library, file on the server).
	'post__in'       => Speaker_Data::ids_with_photo(),
	'orderby'        => $orderby,
	'meta_query'     => array(
		'relation' => 'AND',
		Speaker_Data::visible_meta_query(),
		Speaker_Data::featured_first_meta_query(),
	),
);

if ( $keyword ) {
	$query_args['s'] = $keyword;
}

// Taxonomy filters (?topic=, ?type=, ?fee=, ?location=), plus the current topic on topic pages.
$on_topic_page = is_tax( 'speaker_category' );
$tax_query     = Speaker_Data::filters_tax_query();

if ( $on_topic_page ) {
	$tax_query[] = array(
		'taxonomy' => 'speaker_category',
		'terms'    => get_queried_object_id(),
	);
}

if ( $tax_query ) {
	$query_args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
}

$speakers = new WP_Query( $query_args );

// "Refine results" panel: one dropdown per filter whose taxonomy exists and has terms.
// The topic dropdown is left out on topic pages (the page already is a topic).
$filter_labels = array(
	'topic'    => 'Topic',
	'type'     => 'Speaker type',
	'fee'      => 'Fee range',
	'location' => 'Location',
);
$filter_fields = array();

foreach ( Speaker_Data::filters() as $param => $taxonomy ) {
	if ( 'topic' === $param && $on_topic_page ) {
		continue;
	}

	// Every term with speakers, subcategories indented under their parent.
	$options = Speaker_Data::filter_options( $taxonomy );

	if ( $options ) {
		$filter_fields[ $param ] = array(
			'label'   => $filter_labels[ $param ] ?? ucfirst( $param ),
			'options' => $options,
		);
	}
}

// The form submits to this page (page 1): the listing page, or the topic page.
$base_url = $on_topic_page ? get_term_link( get_queried_object() ) : get_permalink();
$base_url = is_wp_error( $base_url ) || ! $base_url ? home_url( '/all-speakers/' ) : $base_url;

$active_filters = count( array_intersect_key( $selected, $filter_fields ) ) + ( $keyword ? 1 : 0 );
$total          = (int) $speakers->found_posts;
$panel_id       = wp_unique_id( 'speakers-grid-filters-' );
$field_id       = wp_unique_id( 'speakers-grid-' );

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>

	<form class="speakers-grid__toolbar" action="<?php echo esc_url( $base_url ); ?>" method="get" role="search" aria-label="Filter and sort speakers">
		<div class="section-inner">
			<div class="speakers-grid__toolbar-start">
				<button type="button" class="speakers-grid__refine" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
					<span>
						Refine results
						<?php if ( $active_filters ) : ?>
							<span class="speakers-grid__refine-count">(<?php echo esc_html( $active_filters ); ?>)</span>
						<?php endif; ?>
					</span>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
					</svg>
				</button>

				<p class="speakers-grid__total" aria-live="polite">
					<?php echo esc_html( sprintf( _n( '%s speaker', '%s speakers', $total, 'crownspeakers' ), number_format_i18n( $total ) ) ); ?>
				</p>
			</div>

			<div class="speakers-grid__sort">
				<label for="<?php echo esc_attr( $field_id ); ?>-sort">Sort by</label>
				<select id="<?php echo esc_attr( $field_id ); ?>-sort" name="sort" data-auto-submit>
					<?php foreach ( $sort_options as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $sort, $value ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<noscript><button type="submit" class="speakers-grid__sort-submit">Sort</button></noscript>
			</div>
		</div>

		<div class="speakers-grid__panel" id="<?php echo esc_attr( $panel_id ); ?>" hidden>
			<div class="section-inner">
				<div class="speakers-grid__fields">
					<div class="speakers-grid__field">
						<label for="<?php echo esc_attr( $field_id ); ?>-keyword">Keyword</label>
						<input type="search" id="<?php echo esc_attr( $field_id ); ?>-keyword" name="keyword" value="<?php echo esc_attr( $keyword ); ?>" placeholder="Name, keyword or topic" />
					</div>

					<?php foreach ( $filter_fields as $param => $field ) : ?>
						<div class="speakers-grid__field">
							<label for="<?php echo esc_attr( $field_id . '-' . $param ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
							<select id="<?php echo esc_attr( $field_id . '-' . $param ); ?>" name="<?php echo esc_attr( $param ); ?>">
								<option value="">All</option>
								<?php foreach ( $field['options'] as $slug => $label ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $selected[ $param ] ?? '', $slug ); ?>>
										<?php echo esc_html( $label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="speakers-grid__panel-actions">
					<button type="submit" class="speakers-grid__apply">Apply filters</button>
					<?php if ( $active_filters ) : ?>
						<a class="speakers-grid__clear" href="<?php echo esc_url( $base_url ); ?>">Clear all</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>

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
			<div class="speakers-grid__empty">
				<p>No speakers found<?php echo $keyword ? ' for “' . esc_html( $keyword ) . '”' : ''; ?><?php echo $active_filters ? ' with these filters' : ''; ?>.</p>
				<?php if ( $active_filters ) : ?>
					<a class="speakers-grid__clear" href="<?php echo esc_url( $base_url ); ?>">Clear all filters</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>

</section>

<?php
wp_reset_postdata();
?>
