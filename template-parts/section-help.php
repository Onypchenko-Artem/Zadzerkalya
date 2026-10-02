<?php
/**
 * Блок підтримки та первинної консультації.
 *
 * @package Zadzerkalya
 */

$default_reasons = array(
	__( 'познайомитися з вами та дитиною;', 'zadzerkalya' ),
	__( 'дізнатися всі важливі деталі розвитку;', 'zadzerkalya' ),
	__( 'оцінити поточний рівень сформованих навичок;', 'zadzerkalya' ),
	__( 'визначити пріоритети та цілі роботи;', 'zadzerkalya' ),
	__( 'підібрати команду фахівців (логопед, психолог, дефектолог тощо);', 'zadzerkalya' ),
	__( 'скласти індивідуальний план занять;', 'zadzerkalya' ),
	__( 'відповісти на ваші запитання.', 'zadzerkalya' ),
);

$title   = zadzerkalya_field( 'help_title', __( 'Ми починаємо роботу з первинної консультації, щоб:', 'zadzerkalya' ) );
$reasons = zadzerkalya_field_lines( 'help_reasons' ) ?: $default_reasons;
$button  = zadzerkalya_field( 'help_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) );
$button_url = zadzerkalya_field( 'help_button_url', zadzerkalya_get_page_url( 'contacts' ) );
$banner  = zadzerkalya_field( 'help_banner' );
$banner_alt = zadzerkalya_field( 'help_banner_alt', __( 'Ви — не одні! Ми поруч, щоб вислухати, зрозуміти та допомогти', 'zadzerkalya' ) );
$bottle  = zadzerkalya_field( 'help_bottle' );
?>
<section class="help">
	<div class="help__support">
		<?php if ( ! zadzerkalya_acf_image( $banner, 'large', array( 'alt' => $banner_alt ) ) ) : ?>
			<img
				src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-banner.png' ); ?>"
				width="816"
				height="816"
				alt="<?php echo esc_attr( $banner_alt ); ?>"
			>
		<?php endif; ?>
	</div>

	<div class="help__consultation">
		<img class="help__divider help__divider--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-left-line.svg' ); ?>" alt="" aria-hidden="true">

		<div class="help__consultation-content">
			<h3><?php echo esc_html( $title ); ?></h3>
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
					'label'   => $button,
					'url'     => $button_url,
					'variant' => 'primary',
				)
			);
			?>
		</div>

		<?php if ( ! zadzerkalya_acf_image( $bottle, 'medium', array( 'class' => 'help__bottle', 'alt' => '', 'aria-hidden' => 'true' ) ) ) : ?>
			<img class="help__bottle" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-bottle.png' ); ?>" alt="" aria-hidden="true">
		<?php endif; ?>
		<img class="help__divider help__divider--right" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-right-line.svg' ); ?>" alt="" aria-hidden="true">
	</div>
</section>
