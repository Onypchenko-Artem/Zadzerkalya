<?php
/**
 * Вітрина послуг на головній сторінці.
 *
 * @package Zadzerkalya
 */

$archive_url = zadzerkalya_get_services_url();
$defaults    = array(
	__( 'Первинна консультація в «Задзеркаллі»: перший крок до розкриття потенціалу дитини', 'zadzerkalya' ),
	__( 'Логопед-дефектолог: ключ до мовленнєвого розвитку дитини', 'zadzerkalya' ),
	__( 'Психолог-дефектолог: комплексний розвиток уваги, пам’яті та мислення', 'zadzerkalya' ),
	__( 'Логопед: допомога у формуванні мовлення та правильної звуковимови', 'zadzerkalya' ),
	__( 'АВА-терапія для дітей: розвиток навичок і корекція поведінки', 'zadzerkalya' ),
	__( 'Сенсорна інтеграція: основа розвитку вашої дитини', 'zadzerkalya' ),
	__( 'Нейродіагностика: від аналізу до дії', 'zadzerkalya' ),
);

$title       = zadzerkalya_field( 'services_title', __( "Понад 25 корекційних, освітніх\nта реабілітаційних послуг,\nзібраних в одному центрі!", 'zadzerkalya' ) );
$text        = zadzerkalya_field( 'services_text', __( 'В «Задзеркаллі» пропонуємо як психоемоційну, так і фізичну корекцію в межах одного простору для вашої зручності.', 'zadzerkalya' ) );
$illustration = zadzerkalya_field( 'services_illustration' );
$card_button = zadzerkalya_field( 'services_card_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) );
$all_label   = zadzerkalya_field( 'services_all_label', __( 'переглянути всі послуги', 'zadzerkalya' ) );
$all_url     = zadzerkalya_field( 'services_all_url', $archive_url );
$rows        = function_exists( 'get_field' ) ? get_field( 'services_items' ) : null;
$items       = array();

if ( is_array( $rows ) ) {
	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$item_title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';
		$item_url   = isset( $row['url'] ) ? trim( (string) $row['url'] ) : '';

		if ( '' === $item_title ) {
			continue;
		}

		$items[] = array(
			'title' => $item_title,
			'url'   => $item_url ?: $archive_url,
			'image' => $row['image'] ?? null,
		);
	}
}

if ( ! $items ) {
	$services = zadzerkalya_query_latest( 'service', 7 );

	while ( $services->have_posts() ) {
		$services->the_post();
		$items[] = array(
			'title' => get_the_title(),
			'url'   => get_permalink(),
			'image' => get_post_thumbnail_id(),
		);
	}
	wp_reset_postdata();

	for ( $index = count( $items ); $index < 7; ++$index ) {
		$items[] = array(
			'title' => $defaults[ $index ],
			'url'   => $archive_url,
			'image' => null,
		);
	}
}
?>
<section class="services-showcase" aria-labelledby="services-showcase-title">
	<div class="services-showcase__heading">
		<h2 id="services-showcase-title"><?php echo wp_kses( nl2br( esc_html( $title ) ), array( 'br' => array() ) ); ?></h2>
		<p><?php echo esc_html( $text ); ?></p>
	</div>

	<div class="services-showcase__grid">
		<div class="services-showcase__illustration-placeholder">
			<?php if ( ! zadzerkalya_acf_image( $illustration, 'large', array( 'alt' => '' ) ) ) : ?>
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/alice.png' ); ?>" alt="" aria-hidden="true">
			<?php endif; ?>
		</div>

		<?php foreach ( $items as $item ) : ?>
			<article class="services-showcase__card">
				<h3><?php echo esc_html( $item['title'] ); ?></h3>
				<span class="services-showcase__line" aria-hidden="true"></span>
				<div class="services-showcase__image-placeholder">
					<?php
					if ( ! zadzerkalya_acf_image( $item['image'], 'large', array( 'alt' => $item['title'] ) ) ) :
						?>
						<span><?php esc_html_e( 'Фото', 'zadzerkalya' ); ?></span>
					<?php endif; ?>
				</div>
				<span class="services-showcase__line services-showcase__line--reverse" aria-hidden="true"></span>
				<?php
				zadzerkalya_button(
					array(
						'label'   => $card_button,
						'url'     => $item['url'],
						'variant' => 'primary',
					)
				);
				?>
			</article>
		<?php endforeach; ?>

		<div class="services-showcase__all">
			<?php
			zadzerkalya_button(
				array(
					'label'   => $all_label,
					'url'     => $all_url,
					'variant' => 'primary',
				)
			);
			?>
		</div>
	</div>
</section>
