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
			<svg viewBox="0 0 1743 234" aria-hidden="true">
				<defs>
					<path id="numbers-love-path" d="M25 194C330 25 650 18 925 82C1190 144 1375 214 1718 88"/>
				</defs>
				<path class="numbers__love-line" d="M25 194C330 25 650 18 925 82C1190 144 1375 214 1718 88"/>
				<text>
					<textPath href="#numbers-love-path" startOffset="50%" text-anchor="middle"><?php esc_html_e( 'і безкінечна кількість любові до своєї справи', 'zadzerkalya' ); ?></textPath>
				</text>
			</svg>
			<span><?php esc_html_e( 'І безкінечна кількість любові до своєї справи', 'zadzerkalya' ); ?></span>
		</div>
	</div>

	<div class="numbers__photo-placeholder" role="img" aria-label="<?php esc_attr_e( 'Місце для фото команди', 'zadzerkalya' ); ?>">
		<span><?php esc_html_e( 'Фото команди', 'zadzerkalya' ); ?></span>
	</div>
</section>
