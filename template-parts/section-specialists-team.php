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
		<svg class="specialists-team__line" viewBox="0 0 1823 13" fill="none" aria-hidden="true">
			<path d="M0.5 0.5C0.641897 0.5 0.7838 0.5 248.208 2.02533C495.632 3.55067 990.334 6.60134 1253.83 8.82848C1517.32 11.0556 1534.61 12.3668 1568.19 10.8407C1651.61 7.04883 1706.74 0.218509 1720.85 1.41592C1768.44 7.97265 1797.08 6.62842 1809.56 6.23012C1815.02 5.91405 1818.7 5.36663 1822.5 4.80261" stroke="currentColor" stroke-linecap="round" />
		</svg>

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
