<?php
/**
 * Блок «Читайте також»: слайдер карток статей.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title      Заголовок секції.
 *     @type string $post_type  Тип записів.
 *     @type int    $count      Кількість карток.
 *     @type int    $exclude    ID запису, який треба виключити.
 *     @type string $heading_id id заголовка для aria-labelledby.
 * }
 */

$title      = $args['title'] ?? __( 'Читайте також', 'zadzerkalya' );
$post_type  = $args['post_type'] ?? 'event';
$count      = (int) ( $args['count'] ?? 6 );
$exclude    = (int) ( $args['exclude'] ?? get_the_ID() );
$heading_id = $args['heading_id'] ?? 'recommend-title';

$query_args = array(
	'post_type'           => $post_type,
	'posts_per_page'      => $count,
	'post__not_in'        => $exclude ? array( $exclude ) : array(),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( 'event' === $post_type ) {
	$query_args['orderby'] = array(
		'meta_value' => 'DESC',
		'date'       => 'DESC',
	);
	$query_args['meta_key'] = '_zdk_event_date';
}

$recommend = new WP_Query( $query_args );

if ( ! $recommend->have_posts() ) {
	return;
}
?>
<section class="recommend" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-recommend>
	<div class="recommend__header">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="recommend__title"><?php echo esc_html( $title ); ?></h2>
		<div class="recommend__controls">
			<button type="button" class="recommend__arrow recommend__arrow--prev" aria-label="<?php esc_attr_e( 'Попередні статті', 'zadzerkalya' ); ?>"></button>
			<button type="button" class="recommend__arrow recommend__arrow--next" aria-label="<?php esc_attr_e( 'Наступні статті', 'zadzerkalya' ); ?>"></button>
		</div>
	</div>

	<div class="recommend__viewport">
		<div class="recommend__track">
			<?php
			while ( $recommend->have_posts() ) :
				$recommend->the_post();
				get_template_part( 'template-parts/card', 'article' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
