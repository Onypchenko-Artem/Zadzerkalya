<?php
/**
 * Template Name: Події
 *
 * @package Zadzerkalya
 */

get_header();

$field = static function ( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$page_title      = get_the_title();
$hero_title      = $field( 'events_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
$hero_quote      = $field( 'events_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
$hero_scene      = $field( 'events_hero_scene' );
$hero_background = $field( 'events_hero_background' );
$paged           = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$events          = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'meta_key'       => '_zdk_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'DESC',
	)
);
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
			'heading_id' => 'events-hero-title',
		)
	);
	?>

	<div class="container section">
		<header class="page-header">
			<h2 class="page-title"><?php esc_html_e( 'Усі події', 'zadzerkalya' ); ?></h2>
		</header>

		<?php if ( $events->have_posts() ) : ?>
			<div class="cards-grid">
				<?php
				while ( $events->have_posts() ) :
					$events->the_post();
					get_template_part( 'template-parts/card', 'event' );
				endwhile;
				?>
			</div>

			<?php
			$original_query       = $GLOBALS['wp_query'];
			$GLOBALS['wp_query'] = $events;
			zadzerkalya_pagination();
			$GLOBALS['wp_query'] = $original_query;
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</main>
<?php
get_footer();
