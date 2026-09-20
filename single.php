<?php
/**
 * Окрема стаття блогу.
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
		<article <?php post_class( 'container section article' ); ?>>
			<header class="page-header">
				<?php zadzerkalya_posted_on(); ?>
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
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
