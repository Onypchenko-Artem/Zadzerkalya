<?php
/**
 * Перелік послуг: заголовок і сітка карток.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string       $title        Заголовок секції.
 *     @type string       $heading_id   id заголовка.
 *     @type string       $all_label    Підпис фільтра «усі».
 *     @type string       $all_url      URL без фільтра.
 *     @type string       $taxonomy     Таксономія фільтрів.
 *     @type string       $topic_param  GET-параметр фільтра.
 *     @type WP_Term|null $current_term Активний термін.
 *     @type WP_Query     $query        Запит послуг.
 *     @type string       $button_label Текст кнопки картки.
 * }
 */

$title        = $args['title'] ?? __( 'Наші послуги', 'zadzerkalya' );
$heading_id   = $args['heading_id'] ?? 'services-feed-title';
$all_label    = $args['all_label'] ?? __( 'Всі послуги', 'zadzerkalya' );
$all_url      = $args['all_url'] ?? '';
$taxonomy     = $args['taxonomy'] ?? 'service_type';
$topic_param  = $args['topic_param'] ?? 'type';
$current_term = $args['current_term'] ?? null;
$query        = $args['query'] ?? null;
$button_label = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );

if ( ! $query instanceof WP_Query ) {
	return;
}

$terms = array();
if ( $all_url && taxonomy_exists( $taxonomy ) ) {
	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
		)
	);
	if ( is_wp_error( $terms ) ) {
		$terms = array();
	}
}

$filter_url = static function ( $term = null ) use ( $all_url, $topic_param ) {
	if ( ! $term || ! $all_url ) {
		return $all_url;
	}

	return add_query_arg( $topic_param, $term->slug, $all_url );
};

$has_posts = $query->have_posts();
?>
<section class="services-feed" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="services-feed__intro">
		<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="services-feed__title"><?php echo esc_html( $title ); ?></h2>
		<?php if ( $all_url ) : ?>
			<nav class="services-feed__filters" aria-label="<?php esc_attr_e( 'Фільтр послуг', 'zadzerkalya' ); ?>">
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
		<?php endif; ?>
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
