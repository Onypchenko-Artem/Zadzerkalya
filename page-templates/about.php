<?php
/**
 * Template Name: Про нас
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'container section' ); ?>>
			<header class="page-header">
				<p class="kicker"><?php esc_html_e( 'Центр', 'zadzerkalya' ); ?></p>
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="article-hero">
					<?php the_post_thumbnail( 'zadzerkalya-hero' ); ?>
				</div>
			<?php endif; ?>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;

	$specialists = zadzerkalya_query_latest( 'specialist', 4 );
	if ( $specialists->have_posts() ) :
		?>
		<section class="section section--alt">
			<div class="container">
				<div class="section-head">
					<h2><?php esc_html_e( 'Команда', 'zadzerkalya' ); ?></h2>
					<a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'specialist' ) ); ?>"><?php esc_html_e( 'Усі спеціалісти', 'zadzerkalya' ); ?></a>
				</div>
				<div class="cards-grid cards-grid--4">
					<?php
					while ( $specialists->have_posts() ) :
						$specialists->the_post();
						get_template_part( 'template-parts/card', 'specialist' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
