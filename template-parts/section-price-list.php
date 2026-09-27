<?php
/**
 * Прайс-лист сторінки «Вартість послуг».
 *
 * @package Zadzerkalya
 */

$fallback_items = array(
	array( 'title' => 'Первинна консультація', 'price' => '850 грн' ),
	array( 'title' => 'Індивідуальне заняття', 'price' => '650 грн' ),
	array( 'title' => 'Групові заняття', 'price' => '550 грн' ),
	array( 'title' => 'Підготовка до школи (груповий формат)', 'price' => '550 грн' ),
	array( 'title' => 'Підготовка до школи (індивідуальний формат)', 'price' => '650 грн' ),
	array( 'title' => 'Консультація для батьків', 'price' => '850 грн' ),
	array( 'title' => 'Туалетний тренінг', 'price' => '850 грн' ),
	array( 'title' => '«Маленький садочок»', 'price' => '1050 грн (3 години)' ),
	array( 'title' => 'Масаж', 'price' => '700 грн' ),
	array( 'title' => 'Терапія TOMATIS', 'price' => '10 600 грн' ),
	array( 'title' => 'Діагностика ADOS-2', 'price' => '2000/2500 грн' ),
	array( 'title' => 'Діагностика РДУГ CONNERS-3', 'price' => '2500/3000 грн' ),
	array( 'title' => 'Нейродіагностика', 'price' => '2500 грн' ),
	array( 'title' => 'Діагностика VB-MAPP', 'price' => 'від 5000 грн' ),
	array( 'title' => 'Профорієнтаційне тестування', 'price' => '2000 грн' ),
	array( 'title' => 'Написання характеристики дитини', 'price' => 'від 500 грн' ),
	array( 'title' => 'Складання індивідуальної програми розвитку', 'price' => 'від 3000 грн' ),
	array( 'title' => 'Психотерапія', 'price' => '850 грн' ),
	array( 'title' => 'Консультація психіатра', 'price' => '600/800 грн' ),
	array( 'title' => 'Тренінг «Стоп-незнайомець» (груповий формат)', 'price' => '350 грн' ),
	array( 'title' => 'Тренінг «Стоп-незнайомець» (індивідуальний формат)', 'price' => 'від 650 грн' ),
);
$items          = array();
$services       = new WP_Query(
	array(
		'post_type'      => 'service',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'order'          => 'ASC',
	)
);

while ( $services->have_posts() ) {
	$services->the_post();
	$price = zadzerkalya_service_price_html();

	if ( $price ) {
		$items[] = array(
			'title' => get_the_title(),
			'price' => $price,
		);
	}
}
wp_reset_postdata();

if ( ! $items ) {
	$items = $fallback_items;
}

$booking_url = zadzerkalya_get_page_url( 'contacts' );
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
							'url'     => $booking_url,
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
