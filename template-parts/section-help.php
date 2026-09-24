<?php
/**
 * Блок підтримки та первинної консультації.
 *
 * @package Zadzerkalya
 */

$reasons = array(
	__( 'познайомитися з вами та дитиною;', 'zadzerkalya' ),
	__( 'дізнатися всі важливі деталі розвитку;', 'zadzerkalya' ),
	__( 'оцінити поточний рівень сформованих навичок;', 'zadzerkalya' ),
	__( 'визначити пріоритети та цілі роботи;', 'zadzerkalya' ),
	__( 'підібрати команду фахівців (логопед, психолог, дефектолог тощо);', 'zadzerkalya' ),
	__( 'скласти індивідуальний план занять;', 'zadzerkalya' ),
	__( 'відповісти на ваші запитання.', 'zadzerkalya' ),
);
?>
<section class="help">
	<div class="help__support">
		<img
			src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-banner.png' ); ?>"
			width="816"
			height="816"
			alt="<?php esc_attr_e( 'Ви — не одні! Ми поруч, щоб вислухати, зрозуміти та допомогти', 'zadzerkalya' ); ?>"
		>
	</div>

	<div class="help__consultation">
		<img class="help__divider help__divider--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-left-line.svg' ); ?>" alt="" aria-hidden="true">

		<div class="help__consultation-content">
			<h3><?php esc_html_e( 'Ми починаємо роботу з первинної консультації, щоб:', 'zadzerkalya' ); ?></h3>
			<ul class="help__reasons">
				<?php foreach ( $reasons as $reason ) : ?>
					<li>
						<img class="help__bullet" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/marker.svg' ); ?>" alt="" aria-hidden="true">
						<span><?php echo esc_html( $reason ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
			zadzerkalya_button(
				array(
					'label'   => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
					'url'     => zadzerkalya_get_page_url( 'contacts' ),
					'variant' => 'primary',
				)
			);
			?>
		</div>

		<img class="help__bottle" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-bottle.png' ); ?>" alt="" aria-hidden="true">
		<img class="help__divider help__divider--right" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-right-line.svg' ); ?>" alt="" aria-hidden="true">
	</div>
</section>
