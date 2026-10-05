<?php
/**
 * Переваги звернення на сторінці послуги.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title        Заголовок секції.
 *     @type string $intro        Текст біля першої картки.
 *     @type string $aside        Текст у лівій колонці.
 *     @type mixed  $image        Ілюстрація лівої колонки.
 *     @type string $button_label Підпис кнопки.
 *     @type string $button_url   Посилання кнопки.
 *     @type string $button_variant primary|secondary.
 *     @type bool   $show_row     Показати ряд карток 2–3.
 *     @type bool   $show_row_2   Показати ряд карток 6–8.
 *     @type array  $cards        Картки: title, description, show, show_description.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'          => '',
		'intro'          => '',
		'aside'          => '',
		'image'          => null,
		'button_label'   => '',
		'button_url'     => '',
		'button_variant' => 'primary',
		'show_row'       => true,
		'show_row_2'     => false,
		'cards'          => array(),
	)
);

$default_cards = array(
	array(
		'title'            => __( 'Індивідуальний підхід до кожної дитини', 'zadzerkalya' ),
		'description'      => __( 'Програма підбирається з урахуванням особливостей, потреб і темпу розвитку кожного малюка.', 'zadzerkalya' ),
		'show'             => true,
		'show_description' => true,
	),
	array(
		'title'            => __( 'Заняття в адаптованому просторі', 'zadzerkalya' ),
		'description'      => __( 'Середовище, обладнане спеціально для роботи з процесами сенсорної інтеграції', 'zadzerkalya' ),
		'show'             => true,
		'show_description' => true,
	),
	array(
		'title'            => __( 'Регулярна оцінка прогресу дитини', 'zadzerkalya' ),
		'description'      => __( 'Використовуємо інструменти, які дозволяють фіксувати навіть незначні зміни та досягнення дитини.', 'zadzerkalya' ),
		'show'             => true,
		'show_description' => true,
	),
	array(
		'title'            => '',
		'description'      => '',
		'show'             => false,
		'show_description' => true,
	),
	array(
		'title'            => __( 'Постійне залучення батьків до процесу', 'zadzerkalya' ),
		'description'      => __( 'Даємо практичні інструменти для розвитку дитини вдома та забезпечуємо постійну зворотну підтримку.', 'zadzerkalya' ),
		'show'             => true,
		'show_description' => true,
	),
);

$is_on = static function ( $item, $key, $default = true ) {
	if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) || null === $item[ $key ] || '' === $item[ $key ] ) {
		return $default;
	}

	return in_array( (string) $item[ $key ], array( '1', 'true' ), true );
};

$cards = array();
foreach ( array_slice( (array) $args['cards'], 0, 8 ) as $item ) {
	if ( ! is_array( $item ) ) {
		continue;
	}
	$cards[] = array(
		'title'            => trim( (string) ( $item['title'] ?? '' ) ),
		'description'      => trim( (string) ( $item['description'] ?? '' ) ),
		'show'             => $is_on( $item, 'show', true ),
		'show_description' => $is_on( $item, 'show_description', true ),
	);
}

$has_custom = '' !== trim( (string) $args['title'] )
	|| '' !== trim( (string) $args['intro'] )
	|| '' !== trim( (string) $args['aside'] )
	|| ! empty( $args['image'] )
	|| $cards;

if ( ! $cards ) {
	$cards = $default_cards;
}

$title = trim( (string) $args['title'] );
$intro = trim( (string) $args['intro'] );
$aside = trim( (string) $args['aside'] );

if ( ! $has_custom ) {
	$title = __( 'Переваги звернення у центр «Задзеркалля»', 'zadzerkalya' );
	$intro = __( 'Ми створюємо безпечний та комфортний простір для дітей із сенсорними порушеннями. Наші спеціалісти мають багаторічний досвід та використовують сучасні методики, що дозволяють досягти стійкого прогресу.', 'zadzerkalya' );
	$aside = __( 'Ми прагнемо не просто зменшити труднощі, а допомогти дитині відчути впевненість, розкрити свій потенціал і набути необхідних навичок для щасливого життя.', 'zadzerkalya' );
}

if ( '' === trim( (string) $args['button_label'] ) ) {
	$args['button_label'] = __( 'забронювати первинну консультацію', 'zadzerkalya' );
}

$button_url = trim( (string) $args['button_url'] );
if ( '' === $button_url ) {
	$button_url = zadzerkalya_get_page_url( 'contacts' );
}

$show_row   = (bool) $args['show_row'];
$show_row_2 = (bool) $args['show_row_2'];
$visible    = array();

foreach ( $cards as $index => $card ) {
	if ( ! $card['show'] ) {
		continue;
	}
	if ( in_array( $index, array( 1, 2 ), true ) && ! $show_row ) {
		continue;
	}
	if ( $index >= 5 && ! $show_row_2 ) {
		continue;
	}
	if ( '' === $card['title'] && '' === $card['description'] ) {
		continue;
	}
	$card['number'] = $index + 1;
	$visible[]      = $card;
}

$lead  = $visible ? array_shift( $visible ) : null;
$pairs = array_chunk( $visible, 2 );

$render_card = static function ( $card ) {
	$number = (string) ( $card['number'] ?? 1 );
	?>
	<article class="service-benefits__card">
		<div class="service-benefits__card-top">
			<div class="service-benefits__mark" aria-hidden="true">
				<img class="service-benefits__spiral service-benefits__spiral--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/service-benefits-quote.svg' ); ?>" alt="">
				<span class="service-benefits__digits">
					<?php for ( $i = 0; $i < 22; $i++ ) : ?>
						<span style="--i: <?php echo esc_attr( (string) round( $i / 21, 4 ) ); ?>"><?php echo esc_html( $number ); ?></span>
					<?php endfor; ?>
				</span>
				<img class="service-benefits__spiral" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/service-benefits-quote.svg' ); ?>" alt="">
			</div>
			<?php if ( '' !== $card['title'] ) : ?>
				<h3><?php echo esc_html( $card['title'] ); ?></h3>
			<?php endif; ?>
		</div>
		<?php if ( $card['show_description'] && '' !== $card['description'] ) : ?>
			<p><?php echo esc_html( $card['description'] ); ?></p>
		<?php endif; ?>
	</article>
	<?php
};
?>
<section class="service-benefits" aria-labelledby="service-benefits-title">
	<div class="service-benefits__aside">
		<?php if ( $aside ) : ?>
			<div class="service-benefits__aside-text">
				<p><?php echo esc_html( $aside ); ?></p>
			</div>
		<?php endif; ?>
		<div class="service-benefits__aside-foot">
			<div class="service-benefits__figure">
				<?php if ( ! zadzerkalya_acf_image( $args['image'], 'medium', array( 'alt' => '' ) ) ) : ?>
					<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/rabbit.png' ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
				<img class="service-benefits__figure-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/bottom-line.svg' ); ?>" alt="" aria-hidden="true">
			</div>
			<?php
			zadzerkalya_button(
				array(
					'label'   => $args['button_label'],
					'url'     => $button_url,
					'variant' => $args['button_variant'],
				)
			);
			?>
		</div>
	</div>

	<div class="service-benefits__main">
		<?php if ( $title ) : ?>
			<h2 id="service-benefits-title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<div class="service-benefits__rows">
			<?php if ( $intro || $lead ) : ?>
				<div class="service-benefits__row service-benefits__row--lead">
					<?php if ( $intro ) : ?>
						<p class="service-benefits__intro"><?php echo esc_html( $intro ); ?></p>
					<?php endif; ?>
					<?php
					if ( $lead ) {
						$render_card( $lead );
					}
					?>
				</div>
			<?php endif; ?>

			<?php foreach ( $pairs as $pair ) : ?>
				<div class="service-benefits__row">
					<?php foreach ( $pair as $card ) : ?>
						<?php $render_card( $card ); ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
