<?php
/**
 * Блок статистики центру.
 *
 * @package Zadzerkalya
 */

$top_stats = array(
	array( 'number' => '10 000+', 'label' => __( 'клієнтів', 'zadzerkalya' ) ),
	array( 'number' => '30+', 'label' => __( 'спеціалістів', 'zadzerkalya' ) ),
	array( 'number' => '25+', 'label' => __( 'послуг всебічної корекції', 'zadzerkalya' ) ),
);
$bottom_stats = array(
	array( 'number' => '7+', 'label' => __( 'років існування центру', 'zadzerkalya' ) ),
	array( 'number' => '5', 'label' => __( 'поверхів', 'zadzerkalya' ) ),
	array( 'number' => '800', 'label' => __( 'м²', 'zadzerkalya' ) ),
	array( 'number' => '2', 'label' => __( 'міста', 'zadzerkalya' ) ),
);

$render_stat_card = static function ( $stat ) {
	?>
	<div class="numbers__stat">
		<div class="numbers__stat-frame">
			<strong><?php echo esc_html( $stat['number'] ); ?></strong>
		</div>
		<span><?php echo esc_html( $stat['label'] ); ?></span>
	</div>
	<?php
};
?>
<section class="numbers" aria-labelledby="numbers-title">
	<div class="numbers__content">
		<div class="numbers__main">
			<div class="numbers__top">
				<div class="numbers__heading">
					<h2 id="numbers-title"><?php esc_html_e( '«Задзеркалля» у цифрах', 'zadzerkalya' ); ?></h2>
					<p><?php esc_html_e( 'Роки досвіду, сотні щасливих історій та команда фахівців, які допомагають дітям розкривати свій потенціал!', 'zadzerkalya' ); ?></p>
				</div>
				<div class="numbers__stats numbers__stats--top">
					<?php foreach ( $top_stats as $stat ) : ?>
						<?php $render_stat_card( $stat ); ?>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="numbers__stats numbers__stats--bottom">
				<?php foreach ( $bottom_stats as $stat ) : ?>
					<?php $render_stat_card( $stat ); ?>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="numbers__love" role="img" aria-label="<?php esc_attr_e( 'І безкінечна кількість любові до своєї справи', 'zadzerkalya' ); ?>">
			<svg viewBox="0 -80 1696 234" aria-hidden="true">
				<defs>
					<path id="numbers-love-path" d="M0.5 134.198C0.912385 134.003 27.2233 116.123 115.051 77.9394C174.466 52.1082 273.35 35.0461 382.609 20.8298C491.868 6.61347 610.891 1.35171 686.115 0.593521C761.338 -0.164669 789.154 3.74016 824.335 10.3826C859.516 17.025 901.217 26.2867 935.249 32.1868C994.025 42.3769 1029.04 44.8475 1065.97 57.1952C1094.35 66.6866 1141.25 85.4667 1173.84 95.8919C1222.11 111.333 1247.19 111.898 1295.69 113.931C1337.08 115.666 1412.63 115.555 1458.28 114.343C1517.6 112.767 1544.83 99.8607 1569.27 86.7266C1620.12 59.4809 1673.01 37.2528 1682.55 33.746C1686.99 32.2198 1690.63 31.2081 1695.47 30.1658"/>
				</defs>
				<path class="numbers__love-line" d="M0.5 134.198C0.912385 134.003 27.2233 116.123 115.051 77.9394C174.466 52.1082 273.35 35.0461 382.609 20.8298C491.868 6.61347 610.891 1.35171 686.115 0.593521C761.338 -0.164669 789.154 3.74016 824.335 10.3826C859.516 17.025 901.217 26.2867 935.249 32.1868C994.025 42.3769 1029.04 44.8475 1065.97 57.1952C1094.35 66.6866 1141.25 85.4667 1173.84 95.8919C1222.11 111.333 1247.19 111.898 1295.69 113.931C1337.08 115.666 1412.63 115.555 1458.28 114.343C1517.6 112.767 1544.83 99.8607 1569.27 86.7266C1620.12 59.4809 1673.01 37.2528 1682.55 33.746C1686.99 32.2198 1690.63 31.2081 1695.47 30.1658"/>
				<text dy="-16">
					<textPath href="#numbers-love-path" startOffset="0"><?php esc_html_e( 'і безкінечна кількість любові до своєї справи', 'zadzerkalya' ); ?></textPath>
				</text>
			</svg>
			<span><?php esc_html_e( 'І безкінечна кількість любові до своєї справи', 'zadzerkalya' ); ?></span>
		</div>
	</div>

	<div class="numbers__photo">
		<img
			src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/team.jpg' ); ?>"
			width="1824"
			height="674"
			alt="<?php esc_attr_e( 'Команда центру «Задзеркалля»', 'zadzerkalya' ); ?>"
		>
	</div>
</section>
