<?php

$statistics = array(
	array(
		'number' => '22',
		'suffix' => '+',
		'label'  => 'Years in business',
	),
	array(
		'number' => '30',
		'suffix' => 'K+',
		'label'  => 'Speakers within reach',
	),
	array(
		'number' => '100',
		'suffix' => '%',
		'label'  => 'Corporate vendor approval',
	),
	array(
		'number' => '1',
		'suffix' => 'st',
		'label'  => 'Client is still with us',
	),
);

$wrapper_attributes = get_block_wrapper_attributes();
?>

<section <?php echo $wrapper_attributes; ?>>
	<div class="section-inner">

		<?php foreach ( $statistics as $statistic ) : ?>
			<div class="statistics__item">
				<p class="statistics__number">
					<?php echo esc_html( $statistic['number'] ); ?><span class="statistics__suffix"><?php echo esc_html( $statistic['suffix'] ); ?></span>
				</p>
				<p class="statistics__label">
					<?php echo esc_html( $statistic['label'] ); ?>
				</p>
			</div>
		<?php endforeach; ?>

	</div>
</section>
