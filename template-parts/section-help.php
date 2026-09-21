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
		<div class="help__support-content">
			<h2><?php esc_html_e( 'Ви — не одні!', 'zadzerkalya' ); ?></h2>
			<img class="help__rabbit" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-rabbit.svg' ); ?>" alt="" aria-hidden="true">
			<p><?php esc_html_e( 'Ми поруч, щоб вислухати, зрозуміти та допомогти', 'zadzerkalya' ); ?></p>
		</div>
	</div>

	<div class="help__consultation">
		<img class="help__divider help__divider--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-left-line.svg' ); ?>" alt="" aria-hidden="true">

		<div class="help__consultation-content">
			<h3><?php esc_html_e( 'Ми починаємо роботу з первинної консультації, щоб:', 'zadzerkalya' ); ?></h3>
			<ul class="help__reasons">
				<?php foreach ( $reasons as $reason ) : ?>
					<li>
						<span class="help__bullet" aria-hidden="true"></span>
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
