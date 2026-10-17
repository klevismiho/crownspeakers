<?php

$attributes  = $attributes ?? array();
$heading     = $attributes['heading'] ?? 'Trusted By Industry Experts.';
$button_text = $attributes['buttonText'] ?? 'See the Difference';
$button_url  = $attributes['buttonUrl'] ?? '#';
$logos       = $attributes['logos'] ?? array();

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>
	data-autoplay="<?php echo $attributes['autoplay'] ? 'true' : 'false'; ?>">

	<div class="section-inner">
		<div class="section-title">
			In good company.<br />
			Trusted by teams at
		</div>

		<div class="swiper logo-grid__swiper">
			<div class="swiper-wrapper logo-grid__logos">
				<?php foreach ($logos as $logo) : ?>
					<div class="swiper-slide logo-grid__item">
						<?php if (! empty($logo['url'])) : ?>
							<a href="<?php echo esc_url($logo['url']); ?>">
								<img
									width="400"
									height="400"
									src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
							</a>
						<?php else : ?>
							<img
								width="400"
								height="400"
								src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
						<?php foreach ($logos as $logo) : ?>
					<div class="swiper-slide logo-grid__item">
						<?php if (! empty($logo['url'])) : ?>
							<a href="<?php echo esc_url($logo['url']); ?>">
								<img
									width="400"
									height="400"
									src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
							</a>
						<?php else : ?>
							<img
								width="400"
								height="400"
								src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
						<?php foreach ($logos as $logo) : ?>
					<div class="swiper-slide logo-grid__item">
						<?php if (! empty($logo['url'])) : ?>
							<a href="<?php echo esc_url($logo['url']); ?>">
								<img
									width="400"
									height="400"
									src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
							</a>
						<?php else : ?>
							<img
								width="400"
								height="400"
								src="<?php echo esc_url($logo['src']); ?>" alt="<?php echo esc_attr($logo['alt'] ?? ''); ?>" />
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

</section>