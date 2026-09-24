<?php
/**
 * Секція «Наша команда».
 *
 * @package Zadzerkalya
 */

$specialists = new WP_Query(
	array(
		'post_type'      => 'specialist',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
	)
);
?>
<section class="specialists-team" aria-labelledby="specialists-team-title">
	<h2 id="specialists-team-title"><?php esc_html_e( 'Наша команда', 'zadzerkalya' ); ?></h2>

	<div class="specialists-team__list">
		<img class="specialists-team__line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/team-line.svg' ); ?>" alt="" aria-hidden="true">

		<?php if ( $specialists->have_posts() ) : ?>
			<div class="specialists-team__row">
				<?php
				while ( $specialists->have_posts() ) :
					$specialists->the_post();
					get_template_part( 'template-parts/card', 'specialist-slot' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>
