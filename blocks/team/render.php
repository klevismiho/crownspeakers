<?php

// Team members from the `team` post type (inc/Post_Type_Team.php), in their Order, then A–Z.
// Job title and LinkedIn are ACF fields (team_role, team_linkedin), read as post meta.
$heading = trim( (string) ( $attributes['heading'] ?? '' ) );

$members = post_type_exists( 'team' )
	? get_posts(
		array(
			'post_type'      => 'team',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	)
	: array();

if ( ! $members ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<?php if ( '' !== wp_strip_all_tags( $heading ) ) : ?>
			<h2 class="team__title"><?php echo wp_kses_post( $heading ); ?></h2>
		<?php endif; ?>

		<ul class="team__list">
			<?php
			foreach ( $members as $member ) :
				$role     = trim( (string) get_post_meta( $member->ID, 'team_role', true ) );
				$linkedin = trim( (string) get_post_meta( $member->ID, 'team_linkedin', true ) );
				$bio      = trim( (string) $member->post_content );
				$name     = get_the_title( $member );
				?>
				<li class="team__member">

					<div class="team__photo">
						<?php
						if ( has_post_thumbnail( $member ) ) {
							echo get_the_post_thumbnail(
								$member,
								'medium_large',
								array(
									'class'   => 'team__image',
									'alt'     => $name,
									'loading' => 'lazy',
									'sizes'   => '(min-width: 800px) 13rem, 40vw',
								)
							);
						}
						?>
					</div>

					<div class="team__details">
						<h3 class="team__name"><?php echo esc_html( $name ); ?></h3>

						<?php if ( $role ) : ?>
							<p class="team__role"><?php echo esc_html( $role ); ?></p>
						<?php endif; ?>

						<?php if ( $bio ) : ?>
							<div class="team__bio">
								<?php echo wp_kses_post( wpautop( $bio ) ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $linkedin ) : ?>
							<a class="team__linkedin" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9.75h4v11H3v-11zm6.5 0h3.8v1.5h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1v5.45h-4v-4.83c0-1.15-.02-2.63-1.6-2.63-1.6 0-1.85 1.25-1.85 2.55v4.91h-3.95v-11z" />
								</svg>
								<span>LinkedIn</span>
								<span class="screen-reader-text">: <?php echo esc_html( $name ); ?> (opens in a new tab)</span>
							</a>
						<?php endif; ?>
					</div>

				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
