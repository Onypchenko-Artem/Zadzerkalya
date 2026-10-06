<?php
/**
 * Перелік послуг: заголовок, сітка карток, пагінація.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string       $title          Заголовок секції.
 *     @type string       $heading_id     id заголовка.
 *     @type string       $all_url        URL списку.
 *     @type string       $topic_param    GET-параметр фільтра для пагінації.
 *     @type WP_Term|null $current_term   Активний термін, якщо список відфільтровано.
 *     @type WP_Query     $query          Запит послуг.
 *     @type int          $paged          Поточна сторінка.
 *     @type string       $button_label   Текст кнопки картки.
 * }
 */

$title        = $args['title'] ?? __( 'Наші послуги', 'zadzerkalya' );
$heading_id   = $args['heading_id'] ?? 'services-feed-title';
$all_url      = $args['all_url'] ?? zadzerkalya_get_services_url();
$topic_param  = $args['topic_param'] ?? 'type';
$current_term = $args['current_term'] ?? null;
$query        = $args['query'] ?? null;
$paged        = max( 1, (int) ( $args['paged'] ?? 1 ) );
$button_label = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );

if ( ! $query instanceof WP_Query ) {
	return;
}

$feed_page_url = static function ( $page ) use ( $all_url, $topic_param, $current_term ) {
	$url = $all_url;
	if ( $page > 1 ) {
		$url = trailingslashit( $all_url ) . user_trailingslashit( 'page/' . $page, 'paged' );
	}
	if ( $current_term ) {
		$url = add_query_arg( $topic_param, $current_term->slug, $url );
	}
	return $url;
};

$total_pages = max( 1, (int) $query->max_num_pages );
$has_posts   = $query->have_posts();
$prev_url    = $paged > 1 ? $feed_page_url( $paged - 1 ) : '';
$next_url    = $paged < $total_pages ? $feed_page_url( $paged + 1 ) : '';
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

			<?php if ( $total_pages > 1 ) : ?>
				<div class="articles-feed__pagination">
					<?php
					if ( $next_url ) {
						zadzerkalya_button(
							array(
								'label'   => __( 'показати ще', 'zadzerkalya' ),
								'url'     => $next_url,
								'variant' => 'secondary',
							)
						);
					}
					?>

					<div class="articles-feed__slider" aria-label="<?php esc_attr_e( 'Пагінація', 'zadzerkalya' ); ?>">
						<?php if ( $prev_url ) : ?>
							<a class="articles-feed__arrow articles-feed__arrow--prev" href="<?php echo esc_url( $prev_url ); ?>" aria-label="<?php esc_attr_e( 'Попередня сторінка', 'zadzerkalya' ); ?>"></a>
						<?php else : ?>
							<span class="articles-feed__arrow articles-feed__arrow--prev is-disabled" aria-hidden="true"></span>
						<?php endif; ?>

						<div class="articles-feed__counter" aria-live="polite">
							<span class="articles-feed__counter-current"><?php echo esc_html( (string) $paged ); ?>/</span><span class="articles-feed__counter-total"><?php echo esc_html( (string) $total_pages ); ?></span>
						</div>

						<?php if ( $next_url ) : ?>
							<a class="articles-feed__arrow articles-feed__arrow--next" href="<?php echo esc_url( $next_url ); ?>" aria-label="<?php esc_attr_e( 'Наступна сторінка', 'zadzerkalya' ); ?>"></a>
						<?php else : ?>
							<span class="articles-feed__arrow articles-feed__arrow--next is-disabled" aria-hidden="true"></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
