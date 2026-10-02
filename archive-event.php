<?php
/**
 * Перелік статей подій.
 *
 * @package Zadzerkalya
 */

get_header();

$hero_title      = zadzerkalya_about_field( 'about_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
$hero_quote      = zadzerkalya_about_field( 'about_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
$hero_scene      = zadzerkalya_about_field( 'about_hero_scene' );
$hero_background = zadzerkalya_about_field( 'about_hero_background' );
$paged           = max( 1, (int) get_query_var( 'paged' ) );
$events_url      = get_post_type_archive_link( 'event' );

$topic_slug   = isset( $_GET['topic'] ) ? sanitize_title( wp_unslash( $_GET['topic'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_term = $topic_slug ? get_term_by( 'slug', $topic_slug, 'event_topic' ) : null;
if ( ! $current_term || is_wp_error( $current_term ) ) {
	$current_term = null;
}

$query_args = array(
	'post_type'      => 'event',
	'posts_per_page' => 3,
	'paged'          => $paged,
	'meta_key'       => '_zdk_event_date',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
);

if ( $current_term ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'event_topic',
			'field'    => 'term_id',
			'terms'    => (int) $current_term->term_id,
		),
	);
}

$events = new WP_Query( $query_args );
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
			'current'    => __( 'Події', 'zadzerkalya' ),
			'heading_id' => 'events-hero-title',
		)
	);

	get_template_part(
		'template-parts/section',
		'articles-feed',
		array(
			'title'        => __( 'Дізнайтеся більше', 'zadzerkalya' ),
			'heading_id'   => 'events-feed-title',
			'all_label'    => __( 'Всі події', 'zadzerkalya' ),
			'all_url'      => $events_url ? $events_url : home_url( '/events/' ),
			'taxonomy'     => 'event_topic',
			'topic_param'  => 'topic',
			'current_term' => $current_term,
			'query'        => $events,
			'paged'        => $paged,
		)
	);
	?>
</main>
<?php
get_footer();
