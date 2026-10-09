<?php
/**
 * Слайдер послуг для вставки в текст новини.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string   $title      Заголовок зліва.
 *     @type string   $button     Текст кнопки на картці.
 *     @type WP_Query $query      Послуги в порядку слайдів.
 *     @type string   $heading_id id заголовка.
 * }
 */

$title      = $args['title'] ?? '';
$button     = $args['button'] ?? '';
$query      = $args['query'] ?? null;
$heading_id = $args['heading_id'] ?? 'service-slider-title';

if ( ! $query instanceof WP_Query || ! $query->have_posts() ) {
	return;
}
?>
<section class="service-slider" data-service-slider aria-roledescription="<?php esc_attr_e( 'карусель', 'zadzerkalya' ); ?>" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="service-slider__aside">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="service-slider__title"><?php echo esc_html( $title ); ?></h2>
		<div class="service-slider__controls">
			<button type="button" class="service-slider__arrow service-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Попередня послуга', 'zadzerkalya' ); ?>"></button>
			<button type="button" class="service-slider__arrow service-slider__arrow--next" aria-label="<?php esc_attr_e( 'Наступна послуга', 'zadzerkalya' ); ?>"></button>
		</div>
	</div>

	<div class="service-slider__viewport">
		<div class="service-slider__track">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<div class="service-slider__slide">
					<?php
					get_template_part(
						'template-parts/card',
						'service',
						array(
							'button_label' => $button,
						)
					);
					?>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</section>
