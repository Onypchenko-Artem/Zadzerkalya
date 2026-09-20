<?php
/**
 * Сторінка блогу (записи).
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<div class="container section">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				$blog_page_id = (int) get_option( 'page_for_posts' );
				echo esc_html( $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Блог', 'zadzerkalya' ) );
				?>
			</h1>
			<?php if ( get_option( 'page_for_posts' ) && get_post_field( 'post_content', get_option( 'page_for_posts' ) ) ) : ?>
				<div class="page-intro prose"><?php echo wp_kses_post( apply_filters( 'the_content', get_post_field( 'post_content', get_option( 'page_for_posts' ) ) ) ); ?></div>
			<?php endif; ?>
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
