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

$title      = zadzerkalya_field( 'help_title', __( 'Ми починаємо роботу з первинної консультації, щоб:', 'zadzerkalya' ) );
$reasons    = zadzerkalya_field_lines( 'help_reasons' ) ?: $default_reasons;
$button     = zadzerkalya_field( 'help_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) );
$button_url = zadzerkalya_field( 'help_button_url', zadzerkalya_get_page_url( 'contacts' ) );
$banner     = zadzerkalya_field( 'help_banner' );
$banner_mobile = zadzerkalya_field( 'help_banner_mobile' );
$banner_alt = zadzerkalya_field( 'help_banner_alt', __( 'Ви — не одні! Ми поруч, щоб вислухати, зрозуміти та допомогти', 'zadzerkalya' ) );
$bottle     = zadzerkalya_field( 'help_bottle' );
$banner_fallback = ZADZERKALYA_URI . '/assets/images/help-banner.png';
$banner_url = '';
$banner_mobile_url = '';

if ( is_numeric( $banner ) ) {
	$banner_url = (string) wp_get_attachment_image_url( (int) $banner, 'large' );
} elseif ( is_array( $banner ) ) {
	$banner_url = ! empty( $banner['ID'] )
		? (string) wp_get_attachment_image_url( (int) $banner['ID'], 'large' )
		: (string) ( $banner['url'] ?? '' );
} elseif ( is_string( $banner ) && '' !== $banner ) {
	$banner_url = $banner;
}

if ( '' === $banner_url ) {
	$banner_url = $banner_fallback;
}

if ( is_numeric( $banner_mobile ) ) {
	$banner_mobile_url = (string) wp_get_attachment_image_url( (int) $banner_mobile, 'large' );
} elseif ( is_array( $banner_mobile ) ) {
	$banner_mobile_url = ! empty( $banner_mobile['ID'] )
		? (string) wp_get_attachment_image_url( (int) $banner_mobile['ID'], 'large' )
		: (string) ( $banner_mobile['url'] ?? '' );
} elseif ( is_string( $banner_mobile ) && '' !== $banner_mobile ) {
	$banner_mobile_url = $banner_mobile;
}

if ( '' === $banner_mobile_url ) {
	$banner_mobile_url = $banner_url;
}
?>
<section class="help">
	<div class="help__support">
		<picture>
			<source media="(max-width: 767px)" srcset="<?php echo esc_url( $banner_mobile_url ); ?>">
			<img
				src="<?php echo esc_url( $banner_url ); ?>"
				width="816"
				height="816"
				alt="<?php echo esc_attr( $banner_alt ); ?>"
			>
		</picture>
	</div>

	<div class="help__consultation">
		<img class="help__divider help__divider--left help__divider--left-desk" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-left-line.svg' ); ?>" alt="" aria-hidden="true">
		<img class="help__divider help__divider--left help__divider--left-mob" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/help-right-line.svg' ); ?>" alt="" aria-hidden="true">

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
