<?php
/**
 * Показання для звернення до спеціаліста.
 *
 * @package Zadzerkalya
 */

$default_items = array(
	__( 'Якщо дитині більше 2-х років, а вона досі не розмовляє — це може бути ознакою порушення мовленнєвого розвитку.', 'zadzerkalya' ),
	__( 'Не відгукується на власне ім’я або не виконує ваші прохання.', 'zadzerkalya' ),
	__( 'Демонструє гіперактивну поведінку — варто перевірити розвиток уваги та емоційної регуляції.', 'zadzerkalya' ),
	__( 'Не проявляє інтересу до соціальної взаємодії — що може відображати труднощі у розвитку комунікативних навичок.', 'zadzerkalya' ),
	__( 'Має дивні, повторювані ігри — частий маркер сенсорних або поведінкових особливостей.', 'zadzerkalya' ),
	__( 'Проявляє часті істерики без зрозумілої причини — це не просто «характер», а можливий сигнал про особливості розвитку.', 'zadzerkalya' ),
	__( 'Не реагує на звернення оточуючих — це може бути ознакою порушення слуху або когнітивного розвитку.', 'zadzerkalya' ),
);

$acf_title    = function_exists( 'get_field' ) ? get_field( 'service_when_title' ) : '';
$acf_subtitle = function_exists( 'get_field' ) ? get_field( 'service_when_subtitle' ) : '';
$acf_items    = function_exists( 'get_field' ) ? get_field( 'service_when_items' ) : '';

$items = array();
if ( is_array( $acf_items ) ) {
	foreach ( $acf_items as $row ) {
		$text = is_array( $row ) ? ( $row['text'] ?? '' ) : $row;
		$text = is_string( $text ) ? trim( $text ) : '';
		if ( '' !== $text ) {
			$items[] = $text;
		}
	}
}

$section = apply_filters(
	'zadzerkalya_service_when_data',
	array(
		'title'    => $acf_title ?: __( 'Коли варто звернутися до спеціалістів?', 'zadzerkalya' ),
		'subtitle' => $acf_subtitle ?: __( 'Помітили хоча б один із цих симптомів? Важливо діяти швидко!', 'zadzerkalya' ),
		'items'    => $items ?: $default_items,
	),
	get_the_ID()
);

if ( empty( $section['items'] ) ) {
	return;
}

$rows = array_chunk( array_values( $section['items'] ), 4 );
?>
<section class="service-when" aria-labelledby="service-when-title">
	<div class="service-when__heading">
		<h2 id="service-when-title"><?php echo esc_html( $section['title'] ); ?></h2>
		<p><?php echo esc_html( $section['subtitle'] ); ?></p>
	</div>

	<div class="service-when__cards">
		<?php
		$number = 1;
		foreach ( $rows as $row ) :
			?>
			<div class="service-when__row">
				<?php foreach ( $row as $item ) : ?>
					<article class="service-when__card">
						<div class="service-when__card-inner">
							<img class="service-when__line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/top-line.svg' ); ?>" alt="" aria-hidden="true">
							<div class="service-when__card-content">
								<span class="service-when__number" aria-hidden="true"><?php echo esc_html( $number ); ?></span>
								<p><?php echo esc_html( $item ); ?></p>
							</div>
							<img class="service-when__line service-when__line--bottom" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/bottom-line.svg' ); ?>" alt="" aria-hidden="true">
						</div>
					</article>
					<?php
					++$number;
				endforeach;
				?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
