<?php
/**
 * Перелік послуг.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<div class="container section">
		<header class="page-header">
			<h1 class="page-title"><?php post_type_archive_title(); ?></h1>
			<?php the_archive_description( '<div class="page-intro prose">', '</div>' ); ?>
		</header>
		<?php if ( have_posts() ) : ?>
			<div class="cards-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card', 'service' );
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
