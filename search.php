<?php
/**
 * Пошук.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<div class="container section">
		<header class="page-header">
			<h1 class="page-title">
				<?php printf( esc_html__( 'Результати пошуку: %s', 'zadzerkalya' ), esc_html( get_search_query() ) ); ?>
			</h1>
			<?php get_search_form(); ?>
		</header>
		<?php if ( have_posts() ) : ?>
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
