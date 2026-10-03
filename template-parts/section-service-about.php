<?php
/**
 * Опис послуги: ілюстрація, два тексти і фото.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $text         Ліва колонка, Body L.
 *     @type string $lead         Права колонка, H3.
 *     @type mixed  $illustration Ілюстрація зліва. Якщо порожньо — Аліса.
 *     @type mixed  $photo        Фото під текстами.
 * }
 */

$text         = $args['text'] ?? '';
$lead         = $args['lead'] ?? '';
$illustration = $args['illustration'] ?? null;
$photo        = $args['photo'] ?? null;

$format_text = static function ( $value ) {
	$value = (string) $value;

	if ( $value === wp_strip_all_tags( $value ) ) {
		$value = wpautop( $value );
	}

	return wp_kses_post( $value );
};

$has_text = '' !== trim( wp_strip_all_tags( (string) $text ) );
$has_lead = '' !== trim( wp_strip_all_tags( (string) $lead ) );

if ( ! $has_text && ! $has_lead && empty( $photo ) ) {
	return;
}
?>
<section class="service-about">
	<div class="service-about__illustration">
		<?php
		if ( ! zadzerkalya_acf_image( $illustration, 'full', array( 'alt' => '' ) ) ) {
			?>
			<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/alice.png' ); ?>" alt="" aria-hidden="true">
			<?php
		}
		?>
	</div>

	<div class="service-about__body">
		<?php if ( $has_text || $has_lead ) : ?>
			<div class="service-about__columns">
				<?php if ( $has_text ) : ?>
					<div class="service-about__text"><?php echo $format_text( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
				<?php if ( $has_lead ) : ?>
					<div class="service-about__lead"><?php echo $format_text( $lead ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $photo ) ) : ?>
			<div class="service-about__photo">
				<?php zadzerkalya_acf_image( $photo, 'full', array( 'class' => 'service-about__photo-image', 'alt' => get_the_title() ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
