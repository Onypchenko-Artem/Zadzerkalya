<?php
/**
 * Template Name: Вартість послуг
 *
 * @package Zadzerkalya
 */

get_header();

$services = new WP_Query(
	array(
		'post_type'      => 'service',
		'posts_per_page' => 50,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);
?>
<main id="content" class="site-main">
	<div class="container section">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
				<div class="page-intro prose"><?php the_content(); ?></div>
			</header>
			<?php
		endwhile;
		?>

		<?php if ( $services->have_posts() ) : ?>
			<div class="price-table" role="table">
				<div class="price-table__head" role="row">
					<span><?php esc_html_e( 'Послуга', 'zadzerkalya' ); ?></span>
					<span><?php esc_html_e( 'Тривалість', 'zadzerkalya' ); ?></span>
					<span><?php esc_html_e( 'Вартість', 'zadzerkalya' ); ?></span>
				</div>
				<?php
				while ( $services->have_posts() ) :
					$services->the_post();
					$price    = zadzerkalya_service_price_html();
					$duration = get_post_meta( get_the_ID(), '_zdk_duration', true );
					$note     = get_post_meta( get_the_ID(), '_zdk_price_note', true );
					?>
					<div class="price-table__row" role="row">
						<div>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							<?php if ( $note ) : ?>
								<p class="muted"><?php echo esc_html( $note ); ?></p>
							<?php endif; ?>
						</div>
						<div><?php echo $duration ? esc_html( $duration ) : '—'; ?></div>
						<div class="price"><?php echo $price ? esc_html( $price ) : '—'; ?></div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p class="muted"><?php esc_html_e( 'Додайте послуги з полем ціни — вони зʼявляться в таблиці.', 'zadzerkalya' ); ?></p>
		<?php endif; ?>
	</div>
	<?php get_template_part( 'template-parts/section', 'cta' ); ?>
</main>
<?php
get_footer();
