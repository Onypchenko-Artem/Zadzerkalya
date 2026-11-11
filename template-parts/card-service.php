<?php
/**
 * Картка послуги: заголовок, фото з лініями, кнопка запису.
 * Той самий вигляд, що й картка вітрини на головній.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title        Заголовок. Якщо не передано — назва поточного запису.
 *     @type string $url          Посилання заголовка. Якщо не передано — permalink.
 *     @type mixed  $image        Зображення ACF або ID вкладення. Ключ має бути лише для картки поза циклом.
 *     @type string $button_label Текст кнопки.
 *     @type string $button_url   Посилання кнопки.
 * }
 */

$button_label = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );
$button_url   = $args['button_url'] ?? '#contact-form';
$is_custom    = isset( $args['title'] ) || isset( $args['url'] ) || array_key_exists( 'image', $args );

if ( $is_custom ) {
	$title     = $args['title'] ?? '';
	$permalink = $args['url'] ?? '#';
	$image     = $args['image'] ?? null;
} else {
	$title     = get_the_title();
	$permalink = get_permalink();
	$image     = null;
}
?>
<?php if ( $is_custom ) : ?>
<article class="service-card">
<?php else : ?>
<article <?php post_class( 'service-card' ); ?>>
<?php endif; ?>
	<a class="service-card__link" href="<?php echo esc_url( $permalink ); ?>">
		<h3 class="service-card__title"><?php echo esc_html( $title ); ?></h3>
	</a>
	<span class="service-card__line" aria-hidden="true"></span>
	<div class="service-card__photo">
		<?php
		$has_photo = false;

		if ( $is_custom ) {
			if ( $image ) {
				$has_photo = zadzerkalya_acf_image( $image, 'large', array( 'alt' => $title ) );
			}
		} elseif ( has_post_thumbnail() ) {
			the_post_thumbnail( 'large', array( 'alt' => $title ) );
			$has_photo = true;
		} elseif ( function_exists( 'get_field' ) ) {
			$has_photo = zadzerkalya_acf_image( get_field( 'service_hero_photo' ), 'large', array( 'alt' => $title ) );
		}

		if ( ! $has_photo ) :
			?>
			<span class="service-card__photo-fallback"><?php esc_html_e( 'Фото', 'zadzerkalya' ); ?></span>
			<?php
		endif;
		?>
	</div>
	<span class="service-card__line service-card__line--reverse" aria-hidden="true"></span>
	<?php
	zadzerkalya_button(
		array(
			'label'      => $button_label,
			'url'        => $button_url,
			'variant'    => 'primary',
			'attributes' => array(
				'data-service' => $title,
			),
		)
	);
	?>
</article>
