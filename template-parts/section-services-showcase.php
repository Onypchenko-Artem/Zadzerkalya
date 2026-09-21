<?php
/**
 * Вітрина послуг на головній сторінці.
 *
 * @package Zadzerkalya
 */

$archive_url = get_post_type_archive_link( 'service' ) ?: home_url( '/services/' );
$defaults    = array(
	__( 'Первинна консультація в «Задзеркаллі»: перший крок до розкриття потенціалу дитини', 'zadzerkalya' ),
	__( 'Логопед-дефектолог: ключ до мовленнєвого розвитку дитини', 'zadzerkalya' ),
	__( 'Психолог-дефектолог: комплексний розвиток уваги, пам’яті та мислення', 'zadzerkalya' ),
	__( 'Логопед: допомога у формуванні мовлення та правильної звуковимови', 'zadzerkalya' ),
	__( 'АВА-терапія для дітей: розвиток навичок і корекція поведінки', 'zadzerkalya' ),
	__( 'Сенсорна інтеграція: основа розвитку вашої дитини', 'zadzerkalya' ),
	__( 'Нейродіагностика: від аналізу до дії', 'zadzerkalya' ),
);
$items       = array();
$services    = zadzerkalya_query_latest( 'service', 7 );

while ( $services->have_posts() ) {
	$services->the_post();
	$items[] = array(
		'title' => get_the_title(),
		'url'   => get_permalink(),
	);
}
wp_reset_postdata();

for ( $index = count( $items ); $index < 7; ++$index ) {
	$items[] = array(
		'title' => $defaults[ $index ],
		'url'   => $archive_url,
	);
}
?>
<section class="services-showcase" aria-labelledby="services-showcase-title">
	<div class="services-showcase__heading">
		<h2 id="services-showcase-title"><?php esc_html_e( 'Понад 25 корекційних, освітніх та реабілітаційних послуг, зібраних в одному центрі!', 'zadzerkalya' ); ?></h2>
		<p><?php esc_html_e( 'В «Задзеркаллі» пропонуємо як психоемоційну, так і фізичну корекцію в межах одного простору для вашої зручності.', 'zadzerkalya' ); ?></p>
	</div>

	<div class="services-showcase__grid">
		<div class="services-showcase__illustration-placeholder">
			<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/alice.png' ); ?>" alt="" aria-hidden="true">
		</div>

		<?php foreach ( $items as $item ) : ?>
			<article class="services-showcase__card">
				<h3><?php echo esc_html( $item['title'] ); ?></h3>
				<span class="services-showcase__line" aria-hidden="true"></span>
				<div class="services-showcase__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Місце для фото послуги', 'zadzerkalya' ); ?>">
					<span><?php esc_html_e( 'Фото', 'zadzerkalya' ); ?></span>
				</div>
				<span class="services-showcase__line services-showcase__line--reverse" aria-hidden="true"></span>
				<?php
				zadzerkalya_button(
					array(
						'label'   => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
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
					'label'   => __( 'переглянути всі послуги', 'zadzerkalya' ),
					'url'     => $archive_url,
					'variant' => 'primary',
				)
			);
			?>
		</div>
	</div>
</section>
