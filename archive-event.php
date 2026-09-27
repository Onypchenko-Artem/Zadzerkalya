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
	?>

	<div class="container section">
		<header class="page-header">
			<h2 class="page-title"><?php esc_html_e( 'Усі події', 'zadzerkalya' ); ?></h2>
			<?php the_archive_description( '<div class="page-intro prose">', '</div>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'event' );
				endwhile;
				?>
			</div>
			<?php zadzerkalya_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
