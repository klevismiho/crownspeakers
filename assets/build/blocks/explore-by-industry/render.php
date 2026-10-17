<?php

$industries = array(
	array(
		'title' => 'Technology',
		'url'   => '#',
		'icon'  => '<rect x="7" y="7" width="10" height="10" rx="1.5" /><rect x="10" y="10" width="4" height="4" /><path d="M10 4v3M14 4v3M10 17v3M14 17v3M4 10h3M4 14h3M17 10h3M17 14h3" />',
	),
	array(
		'title' => 'Healthcare & life sciences',
		'url'   => '#',
		'icon'  => '<path d="M12 20s-7.5-4.5-7.5-10A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 7.5 3c0 5.5-7.5 10-7.5 10z" /><path d="M12 10.5v4M10 12.5h4" />',
	),
	array(
		'title' => 'Financial services & insurance',
		'url'   => '#',
		'icon'  => '<path d="M4 9.5 12 5l8 4.5M5 9.5h14M6.5 9.5v7M10 9.5v7M14 9.5v7M17.5 9.5v7M4 19h16" />',
	),
	array(
		'title' => 'Education',
		'url'   => '#',
		'icon'  => '<path d="m3 9.5 9-4.5 9 4.5-9 4.5-9-4.5z" /><path d="M7 11.5V16c0 1.2 2.2 2.5 5 2.5s5-1.3 5-2.5v-4.5M21 9.5V14" />',
	),
	array(
		'title' => 'Retail & consumer goods',
		'url'   => '#',
		'icon'  => '<path d="M6 8h12l-1 12H7L6 8z" /><path d="M9 8V7a3 3 0 0 1 6 0v1" />',
	),
);

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<div class="section-header">
			<h2 class="section-title">Explore by industry</h2>

			<div class="explore-by-industry__nav">
				<button type="button" class="explore-by-industry__arrow explore-by-industry__arrow--prev" aria-label="Previous industries">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M13 8H3M7 4L3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
				<button type="button" class="explore-by-industry__arrow explore-by-industry__arrow--next" aria-label="Next industries">
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
			</div>
		</div>

		<div class="swiper explore-by-industry__swiper">
			<div class="swiper-wrapper">

				<?php foreach ( $industries as $industry ) : ?>
					<div class="swiper-slide">
						<a class="explore-by-industry__card" href="<?php echo esc_url( $industry['url'] ); ?>">

							<span class="explore-by-industry__icon">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<?php echo $industry['icon']; ?>
								</svg>
							</span>

							<h3 class="explore-by-industry__title">
								<?php echo esc_html( $industry['title'] ); ?>
							</h3>

							<span class="explore-by-industry__link" aria-hidden="true">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
									<path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>

						</a>
					</div>
				<?php endforeach; ?>

			</div>
		</div>

	</div>
</section>
