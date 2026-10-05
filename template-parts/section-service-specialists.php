<?php
/**
 * Картки «що роблять спеціалісти» на сторінці послуги.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title Заголовок секції.
 *     @type array  $items Картки з title і description.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title' => '',
		'items' => array(),
	)
);

$default_items = array(
	array(
		'title'       => __( 'Проводять діагностику сенсорних порушень', 'zadzerkalya' ),
		'description' => __( 'Фахівці оцінюють рівень функціонування сенсорних систем, виявляють труднощі у сприйнятті зовнішніх подразників.', 'zadzerkalya' ),
	),
	array(
		'title'       => __( 'Використовують сучасне спеціальне обладнання', 'zadzerkalya' ),
		'description' => __( 'Заняття проходять з використанням сенсорних тунелів, балансувальних платформ, мʼячів та іншого інвентарю, що сприяє розвитку сенсорної інтеграції.', 'zadzerkalya' ),
	),
	array(
		'title'       => __( 'Розвивають сенсорне сприйняття', 'zadzerkalya' ),
		'description' => __( 'Діти виконують вправи для стимуляції тактильних, вестибулярних, зорових і слухових відчуттів.', 'zadzerkalya' ),
	),
	array(
		'title'       => __( 'Покращують навички дрібної моторики', 'zadzerkalya' ),
		'description' => __( 'На заняттях застосовуються ігрові методики, які активізують дрібну моторику рук, координацію та точність рухів.', 'zadzerkalya' ),
	),
	array(
		'title'       => __( 'Проводять корекцію харчової вибірковості', 'zadzerkalya' ),
		'description' => __( 'Розробляють індивідуальний покроковий план, який дозволяє розширити харчовий репертуар дитини за рахунок зниження гіперчутливості до різних текстур, запахів та смаків.', 'zadzerkalya' ),
	),
	array(
		'title'       => __( 'Надають підтримку батькам', 'zadzerkalya' ),
		'description' => __( 'Спеціалісти складають програму занять з сенсорної інтеграції, яку батьки можуть реалізовувати в домашніх умовах.', 'zadzerkalya' ),
	),
);

$items = array();
foreach ( (array) $args['items'] as $item ) {
	$title = trim( (string) ( is_array( $item ) ? ( $item['title'] ?? '' ) : '' ) );
	$text  = trim( (string) ( is_array( $item ) ? ( $item['description'] ?? '' ) : '' ) );
	if ( '' === $title && '' === $text ) {
		continue;
	}
	$items[] = array(
		'title'       => $title,
		'description' => $text,
	);
}

if ( ! $items ) {
	$items = $default_items;
}

$title = trim( (string) $args['title'] );
if ( '' === $title ) {
	$title = __( 'Спеціалісти нашого центру:', 'zadzerkalya' );
}

$total = count( $items );
?>
<section class="service-specialists" aria-labelledby="service-specialists-title">
	<h2 id="service-specialists-title"><?php echo esc_html( $title ); ?></h2>

	<div class="service-specialists__cards">
		<?php foreach ( $items as $index => $item ) : ?>
			<article class="service-specialists__card">
				<img class="service-specialists__stroke service-specialists__stroke--a" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/service-specialists-stroke-a.svg' ); ?>" alt="" aria-hidden="true">
				<img class="service-specialists__stroke service-specialists__stroke--b" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/service-specialists-stroke-b.svg' ); ?>" alt="" aria-hidden="true">
				<div class="service-specialists__head">
					<p class="service-specialists__counter">
						<span class="service-specialists__counter-current"><?php echo esc_html( (string) ( $index + 1 ) ); ?>/</span><span class="service-specialists__counter-total"><?php echo esc_html( (string) $total ); ?></span>
					</p>
					<?php if ( '' !== $item['title'] ) : ?>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<?php endif; ?>
				</div>
				<?php if ( '' !== $item['description'] ) : ?>
					<p class="service-specialists__text"><?php echo esc_html( $item['description'] ); ?></p>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>
