<?php
/**
 * Сторінка блогу (усі новини).
 *
 * @package Zadzerkalya
 */

get_header();

$blog_page_id = (int) get_option( 'page_for_posts' );
$page_title   = $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Блог', 'zadzerkalya' );
$blog_url     = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/' );

$field = static function ( $name, $fallback = '' ) use ( $blog_page_id ) {
	$value = ( $blog_page_id && function_exists( 'get_field' ) ) ? get_field( $name, $blog_page_id ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$hero_title      = $field( 'blog_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
$hero_quote      = $field( 'blog_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
$hero_scene      = $field( 'blog_hero_scene' );
$hero_background = $field( 'blog_hero_background' );
$paged           = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$topic_slug   = isset( $_GET['topic'] ) ? sanitize_title( wp_unslash( $_GET['topic'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_term = $topic_slug ? get_term_by( 'slug', $topic_slug, 'category' ) : null;
if ( ! $current_term || is_wp_error( $current_term ) ) {
	$current_term = null;
	$topic_slug   = '';
}

$query_args = array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'paged'          => $paged,
);

if ( $current_term ) {
	$query_args['cat'] = (int) $current_term->term_id;
}

$articles = new WP_Query( $query_args );
?>
<main id="content" class="site-main events-page">
	<?php
	get_template_part(
		'template-parts/section',
		'about-hero',
		array(
			'title'      => $hero_title,
			'quote'      => $hero_quote,
			'scene'      => $hero_scene,
			'background' => $hero_background,
			'current'    => $page_title,
			'heading_id' => 'blog-hero-title',
		)
	);

	get_template_part(
		'template-parts/section',
		'articles-feed',
		array(
			'title'        => $field( 'blog_feed_title', __( 'Дізнайтеся більше', 'zadzerkalya' ) ),
			'heading_id'   => 'blog-feed-title',
			'all_label'    => __( 'Всі новини', 'zadzerkalya' ),
			'all_url'      => $blog_url,
			'taxonomy'     => 'category',
			'current_term' => $current_term,
			'query'        => $articles,
			'paged'        => $paged,
		)
	);

	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'title'        => $field( 'blog_form_title', __( 'Кожен день — важливий!', 'zadzerkalya' ) ),
			'description'  => $field( 'blog_form_text', __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ) ),
			'button_label' => $field( 'blog_form_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
			'source'       => 'blog',
		)
	);
	?>
</main>
<?php
get_footer();
