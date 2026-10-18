<?php

if (! defined('ABSPATH')) {
	exit;
}

$menu_id = $attributes['menuId'] ?? 0;
$menu_id = 2272;

if (! $menu_id) {
	return;
}

$items = wp_get_nav_menu_items($menu_id);

if (! $items) {
	return;
}

$menu_items = [];

foreach ($items as $item) {
	$menu_items[$item->menu_item_parent][] = $item;
}

// Mobile panel extras (rows under the menu + call-to-action card).
$contact_url  = home_url('/contact/');
$speakers_url = home_url('/all-speakers/');
$phone_label  = '1 (877) 882-5368';
$phone_href   = 'tel:+18778825368';
$nav_panel_id = wp_unique_id('navigation-panel-');
$search_id    = wp_unique_id('navigation-search-');

$chevron = '<svg class="wp-block-crownspeakers-navigation__chevron" width="8" height="14" viewBox="0 0 8 14" fill="none" aria-hidden="true"><path d="m1 1 6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" /></svg>';

?>

<nav class="wp-block-crownspeakers-navigation">

	<button
		class="wp-block-crownspeakers-navigation__toggle"
		aria-expanded="false"
		aria-controls="<?php echo esc_attr($nav_panel_id); ?>"
		aria-label="Open menu">

		<span></span>
		<span></span>
		<span></span>

	</button>


	<div class="wp-block-crownspeakers-navigation__panel" id="<?php echo esc_attr($nav_panel_id); ?>">

	<ul class="wp-block-crownspeakers-navigation__menu">

		<?php foreach ($menu_items[0] ?? [] as $item): ?>

			<?php
			$has_children = ! empty($menu_items[$item->ID]);

			// Megamenu for the item with the "topics-menu" CSS class (Appearance → Menus).
			$item_classes = array_filter((array) $item->classes);
			$is_mega      = in_array('topics-menu', $item_classes, true);
			$panel_id     = 'mega-menu-' . $item->ID;

			// Topic links: always the speaker categories (menu sub-items are ignored for the megamenu).
			$mega_links = array();

			if ($is_mega && taxonomy_exists('speaker_category')) {
				$topics = get_terms(
					array(
						'taxonomy'   => 'speaker_category',
						'hide_empty' => true,
						'orderby'    => 'name',
						'parent'     => 0,
					)
				);

				if (! is_wp_error($topics)) {
					foreach ($topics as $topic) {
						$mega_links[] = array('title' => $topic->name, 'url' => get_term_link($topic));
					}
				}
			}
			?>

			<li class="menu-item <?php echo ($has_children || $is_mega) ? 'menu-item-has-children' : ''; ?> <?php echo $is_mega ? 'menu-item--mega' : ''; ?> <?php echo esc_attr(implode(' ', $item_classes)); ?>">

				<a href="<?php echo esc_url($item->url); ?>" <?php echo $is_mega ? 'data-mega-toggle aria-expanded="false" aria-controls="' . esc_attr($panel_id) . '"' : ''; ?>>
					<?php echo esc_html($item->title); ?>
					<?php echo ($has_children || $is_mega) ? '' : $chevron; ?>
				</a>


				<?php if ($has_children || $is_mega): ?>

					<button
						class="wp-block-crownspeakers-navigation__submenu-toggle"
						aria-expanded="false"
						<?php echo $is_mega ? 'data-mega-toggle aria-controls="' . esc_attr($panel_id) . '" tabindex="-1"' : ''; ?>
						aria-label="Toggle submenu">

						<svg width="12" height="8" viewBox="0 0 12 8" fill="none">
							<path
								d="M1 1.5L6 6.5L11 1.5"
								stroke="currentColor"
								stroke-width="1.5" />
						</svg>

					</button>

				<?php endif; ?>


				<?php if ($is_mega): ?>

					<div class="mega-menu" id="<?php echo esc_attr($panel_id); ?>">
						<div class="mega-menu__inner">

							<div class="mega-menu__card">
								<p class="mega-menu__eyebrow">Find your next big moment</p>
								<p class="mega-menu__title">Every event.<br />The right voice.</p>
								<p class="mega-menu__text">Explore by industry or a topic that matters to your audience.</p>
								<a class="mega-menu__link" href="<?php echo esc_url(home_url('/all-speakers/')); ?>">Explore all speakers</a>
							</div>

							<div class="mega-menu__topics">
								<p class="mega-menu__heading">Popular topics</p>

								<ul class="mega-menu__list">
									<?php foreach ($mega_links as $mega_link): ?>
										<li>
											<a href="<?php echo esc_url($mega_link['url']); ?>">
												<?php echo esc_html($mega_link['title']); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>

						</div>
					</div>

				<?php elseif ($has_children): ?>

					<ul class="sub-menu">

						<?php foreach ($menu_items[$item->ID] as $child): ?>

							<li class="menu-item">

								<a href="<?php echo esc_url($child->url); ?>">
									<?php echo esc_html($child->title); ?>
								</a>

							</li>

						<?php endforeach; ?>

					</ul>

				<?php endif; ?>


			</li>

		<?php endforeach; ?>

	</ul>

	<?php // Mobile only (hidden from `medium`): extra rows, search and call to action. ?>
	<div class="wp-block-crownspeakers-navigation__extras">

		<ul class="wp-block-crownspeakers-navigation__rows">
			<li>
				<button type="button" class="wp-block-crownspeakers-navigation__row" data-open-favorites>
					<span>
						Favorites
						<span class="wp-block-crownspeakers-navigation__badge" data-favorites-count>0</span>
					</span>
					<?php echo $chevron; ?>
				</button>
			</li>
			<li>
				<a class="wp-block-crownspeakers-navigation__row" href="<?php echo esc_url($contact_url); ?>">
					<span>Contact Us</span>
					<?php echo $chevron; ?>
				</a>
			</li>
		</ul>

		<form class="wp-block-crownspeakers-navigation__search" action="<?php echo esc_url($speakers_url); ?>" method="get" role="search">
			<label for="<?php echo esc_attr($search_id); ?>" class="screen-reader-text">Search by keyword, industry, or topic</label>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<circle cx="13" cy="11" r="7" />
				<path d="m3 21 5-5" />
			</svg>
			<input type="search" id="<?php echo esc_attr($search_id); ?>" name="keyword" placeholder="Search by keyword, industry, or topic" enterkeyhint="search" />
		</form>

		<div class="wp-block-crownspeakers-navigation__cta">
			<img class="wp-block-crownspeakers-navigation__cta-avatar" src="<?php echo esc_url(get_template_directory_uri() . '/assets/src/images/member-1.svg'); ?>" alt="" />

			<p class="wp-block-crownspeakers-navigation__cta-title">Let’s make your event <span>unforgettable.</span></p>

			<a class="wp-block-crownspeakers-navigation__cta-button wp-block-crownspeakers-navigation__cta-button--primary" href="<?php echo esc_url($contact_url); ?>">Tell us about your event</a>

			<a class="wp-block-crownspeakers-navigation__cta-button wp-block-crownspeakers-navigation__cta-button--outline" href="<?php echo esc_attr($phone_href); ?>">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1L6.6 10.8z" />
				</svg>
				<?php echo esc_html($phone_label); ?>
			</a>
		</div>

	</div>

	</div>

</nav>