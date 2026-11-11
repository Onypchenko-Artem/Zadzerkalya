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
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_zdk_show_on_team',
				'value'   => '1',
				'compare' => '=',
			),
			array(
				'key'     => '_zdk_show_on_team',
				'compare' => 'NOT EXISTS',
			),
		),
	)
);
?>
<section class="specialists-team" aria-labelledby="specialists-team-title">
	<h2 id="specialists-team-title"><?php esc_html_e( 'Наша команда', 'zadzerkalya' ); ?></h2>

	<div class="specialists-team__list">
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
