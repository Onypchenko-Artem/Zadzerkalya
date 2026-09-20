<?php
/**
 * Звичайна сторінка.
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
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
