<?php
/**
 * Стрічка статей / подій: заголовок, фільтри, сітка карток, пагінація.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string        $title          Заголовок секції.
 *     @type string        $heading_id     id заголовка.
 *     @type string        $all_label      Підпис фільтра «усі».
 *     @type string        $all_url        URL без фільтра.
 *     @type string        $taxonomy       Таксономія фільтрів.
 *     @type string        $topic_param    GET-параметр фільтра.
 *     @type WP_Term|null  $current_term   Активний термін.
 *     @type WP_Query      $query          Запит записів.
 *     @type int           $paged          Поточна сторінка.
 * }
 */

$title        = $args['title'] ?? __( 'Дізнайтеся більше', 'zadzerkalya' );
$heading_id   = $args['heading_id'] ?? 'articles-feed-title';
$all_label    = $args['all_label'] ?? __( 'Всі новини', 'zadzerkalya' );
$all_url      = $args['all_url'] ?? home_url( '/' );
$taxonomy     = $args['taxonomy'] ?? 'category';
$topic_param  = $args['topic_param'] ?? 'topic';
$current_term = $args['current_term'] ?? null;
$query        = $args['query'] ?? null;
$paged        = max( 1, (int) ( $args['paged'] ?? 1 ) );

if ( ! $query instanceof WP_Query ) {
	return;
}

$terms = get_terms(
	array(
		'taxonomy'   => $taxonomy,
		'hide_empty' => true,
		'exclude'    => 'category' === $taxonomy ? array( (int) get_option( 'default_category' ) ) : array(),
	)
);
if ( is_wp_error( $terms ) ) {
	$terms = array();
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

$filter_url = static function ( $term = null ) use ( $all_url, $topic_param ) {
	if ( ! $term ) {
		return $all_url;
	}
	return add_query_arg( $topic_param, $term->slug, $all_url );
};

$total_pages = max( 1, (int) $query->max_num_pages );
$has_posts   = $query->have_posts();
$prev_url    = $paged > 1 ? $feed_page_url( $paged - 1 ) : '';
$next_url    = $paged < $total_pages ? $feed_page_url( $paged + 1 ) : '';
?>
<section class="articles-feed" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="articles-feed__title"><?php echo esc_html( $title ); ?></h2>

	<div class="articles-feed__body">
		<nav class="articles-feed__filters" aria-label="<?php esc_attr_e( 'Фільтр статей', 'zadzerkalya' ); ?>">
			<a
				class="articles-filter<?php echo $current_term ? '' : ' is-active'; ?>"
				href="<?php echo esc_url( $filter_url() ); ?>"
				<?php echo $current_term ? '' : ' aria-current="page"'; ?>
			>
				<span class="articles-filter__label"><?php echo esc_html( $all_label ); ?></span>
				<span class="articles-filter__line" aria-hidden="true">
					<svg viewBox="0 0 81 5" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0.5 4.5C1.13457 4.5 4.67943 4.43088 13.4694 3.60628C19.0452 3.08321 26.8699 1.41107 32.5336 0.85024C38.1972 0.289405 41.4588 0.651098 44.8081 0.634903C51.4976 0.602558 56.3098 0.108575 59.3165 1.23988C62.8523 2.5703 78.3919 1.10505 80.5 1.23988" stroke="currentColor" stroke-linecap="round" vector-effect="non-scaling-stroke"/>
					</svg>
				</span>
			</a>

			<?php foreach ( $terms as $term ) : ?>
				<?php $is_active = $current_term && (int) $current_term->term_id === (int) $term->term_id; ?>
				<a
					class="articles-filter<?php echo $is_active ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $filter_url( $term ) ); ?>"
					<?php echo $is_active ? ' aria-current="page"' : ''; ?>
				>
					<span class="articles-filter__label"><?php echo esc_html( $term->name ); ?></span>
					<span class="articles-filter__line" aria-hidden="true">
					<svg viewBox="0 0 81 5" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M0.5 4.5C1.13457 4.5 4.67943 4.43088 13.4694 3.60628C19.0452 3.08321 26.8699 1.41107 32.5336 0.85024C38.1972 0.289405 41.4588 0.651098 44.8081 0.634903C51.4976 0.602558 56.3098 0.108575 59.3165 1.23988C62.8523 2.5703 78.3919 1.10505 80.5 1.23988" stroke="currentColor" stroke-linecap="round" vector-effect="non-scaling-stroke"/>
					</svg>
				</span>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="articles-feed__articles">
			<?php if ( $has_posts ) : ?>
				<div class="articles-feed__list">
					<div class="articles-feed__row">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							get_template_part( 'template-parts/card', 'article' );
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
	</div>
</section>
<?php
wp_reset_postdata();
