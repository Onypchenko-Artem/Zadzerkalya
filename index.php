<?php
/**
 * Fallback-шаблон.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<div class="container section">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
					if ( is_home() && ! is_front_page() ) {
						single_post_title();
					} elseif ( is_search() ) {
						printf( esc_html__( 'Результати: %s', 'zadzerkalya' ), esc_html( get_search_query() ) );
					} elseif ( is_archive() ) {
						the_archive_title();
					} else {
						esc_html_e( 'Матеріали', 'zadzerkalya' );
					}
					?>
				</h1>
			</header>
			<div class="cards-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'post' );
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
