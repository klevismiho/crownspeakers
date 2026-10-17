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

?>

<nav class="wp-block-crownspeakers-navigation">

	<button
		class="wp-block-crownspeakers-navigation__toggle"
		aria-expanded="false"
		aria-label="Open menu">

		<span></span>
		<span></span>
		<span></span>

	</button>


	<ul class="wp-block-crownspeakers-navigation__menu">

		<?php foreach ($menu_items[0] ?? [] as $item): ?>

			<?php $has_children = ! empty($menu_items[$item->ID]); ?>

			<li class="menu-item <?php echo $has_children ? 'menu-item-has-children' : ''; ?>">

				<a href="<?php echo esc_url($item->url); ?>">
					<?php echo esc_html($item->title); ?>
				</a>


				<?php if ($has_children): ?>

					<button
						class="wp-block-crownspeakers-navigation__submenu-toggle"
						aria-expanded="false"
						aria-label="Toggle submenu">

						<svg width="12" height="8" viewBox="0 0 12 8" fill="none">
							<path
								d="M1 1.5L6 6.5L11 1.5"
								stroke="currentColor"
								stroke-width="1.5" />
						</svg>

					</button>


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

</nav>