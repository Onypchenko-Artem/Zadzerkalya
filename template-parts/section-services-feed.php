<?php
/**
 * Перелік послуг: заголовок і сітка карток.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string   $title        Заголовок секції.
 *     @type string   $heading_id   id заголовка.
 *     @type WP_Query $query        Запит послуг.
 *     @type string   $button_label Текст кнопки картки.
 * }
 */

$title        = $args['title'] ?? __( 'Наші послуги', 'zadzerkalya' );
$heading_id   = $args['heading_id'] ?? 'services-feed-title';
$query        = $args['query'] ?? null;
$button_label = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );

if ( ! $query instanceof WP_Query ) {
	return;
}

$has_posts = $query->have_posts();
?>
<section class="services-feed" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="services-feed__intro">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="services-feed__title"><?php echo esc_html( $title ); ?></h2>
	</div>

	<div class="services-feed__articles">
		<?php if ( $has_posts ) : ?>
			<div class="services-feed__list">
				<div class="services-feed__row">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						get_template_part(
							'template-parts/card',
							'service',
							array(
								'button_label' => $button_label,
							)
						);
					endwhile;
					?>
				</div>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
