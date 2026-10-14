<?php
/**
 * Прайс-лист сторінки «Вартість послуг».
 *
 * @package Zadzerkalya
 */

$items = array(
	array( 'title' => 'Первинна консультація', 'price' => '850 грн' ),
	array( 'title' => 'Індивідуальне заняття', 'price' => '700 грн' ),
	array( 'title' => 'Групове заняття', 'price' => '600 грн' ),
	array( 'title' => 'Підготовка до школи', 'price' => '600 грн (груповий формат), 700 грн (індивідуальний формат)' ),
	array( 'title' => 'Консультація для батьків', 'price' => '850 грн' ),
	array( 'title' => 'Туалетний тренінг', 'price' => '850 грн' ),
	array( 'title' => 'Корекція харчової поведінки', 'price' => '750 грн' ),
	array( 'title' => '«Маленький садочок»', 'price' => '1050 грн (3 години)' ),
	array( 'title' => 'Масаж', 'price' => '750 грн' ),
	array( 'title' => 'Психотерапія', 'price' => '850 грн' ),
	array( 'title' => 'Психолог для підлітків', 'price' => '750 грн' ),
	array( 'title' => 'Діагностика ADOS-2', 'price' => '2500\\3000 грн' ),
	array( 'title' => 'Діагностика РДУГ CONNERS-3', 'price' => '2500\\3000 грн' ),
	array( 'title' => 'Нейродіагностика', 'price' => '2500 грн' ),
	array( 'title' => 'Діагностика VB-MAPP', 'price' => 'від 5000 грн' ),
	array( 'title' => 'Складання індивідуальної програми розвитку', 'price' => 'від 3000 грн' ),
	array( 'title' => 'Консультації для асистентів, вихователів та вчителів', 'price' => 'від 2000 грн' ),
	array( 'title' => 'Терапія TOMATIS', 'price' => '10 600 грн' ),
	array( 'title' => 'Консультація психіатра', 'price' => '600-800 грн' ),
	array( 'title' => 'Профорієнтаційне тестування', 'price' => '2500 грн' ),
	array( 'title' => 'Написання характеристики дитини', 'price' => 'від 500 грн' ),
	array( 'title' => 'Тренінг «Стоп-незнайомець»', 'price' => '400 грн (груповий формат), від 700 грн (індивідуальний формат)' ),
);

?>
<section class="price-list" aria-labelledby="price-list-title">
	<header class="price-list__heading">
		<img class="price-list__heading-line price-list__heading-line--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-heading-line-left.svg' ); ?>" alt="" aria-hidden="true">
		<h2 id="price-list-title"><?php esc_html_e( 'Вартість', 'zadzerkalya' ); ?></h2>
		<img class="price-list__heading-line price-list__heading-line--right" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-heading-line-right.svg' ); ?>" alt="" aria-hidden="true">
	</header>

	<div class="price-list__items">
		<?php foreach ( $items as $item ) : ?>
			<article class="price-list__item">
				<div class="price-list__row">
					<div class="price-list__details">
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['price'] ); ?></p>
					</div>

					<?php
					zadzerkalya_button(
						array(
							'label'   => __( 'записатися', 'zadzerkalya' ),
							'url'     => '#contact-form',
							'variant' => 'primary',
						)
					);
					?>
				</div>
				<img class="price-list__separator" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-row-line.svg' ); ?>" alt="" aria-hidden="true">
			</article>
		<?php endforeach; ?>
	</div>
</section>
