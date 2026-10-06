<?php
/**
 * Картка послуги: заголовок, фото з лініями, кнопка запису.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $button_label Текст кнопки.
 * }
 */

$button_label = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );
$permalink    = get_permalink();
?>
<article <?php post_class( 'service-card' ); ?>>
	<div class="service-card__head">
		<h3 class="service-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
		</h3>
	</div>

	<div class="service-card__media">
		<span class="service-card__line" aria-hidden="true"></span>
		<a class="service-card__photo" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
			<?php
			$has_photo = false;
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array( 'alt' => '' ) );
				$has_photo = true;
			} elseif ( function_exists( 'get_field' ) ) {
				$has_photo = zadzerkalya_acf_image( get_field( 'service_hero_photo' ), 'large', array( 'alt' => '' ) );
			}
			if ( ! $has_photo ) :
				?>
				<span class="service-card__photo-fallback"><?php esc_html_e( 'Фото', 'zadzerkalya' ); ?></span>
				<?php
			endif;
			?>
		</a>
		<span class="service-card__line service-card__line--bottom" aria-hidden="true"></span>
	</div>

	<?php
	zadzerkalya_button(
		array(
			'label'   => $button_label,
			'url'     => $permalink,
			'variant' => 'primary',
		)
	);
	?>
</article>
