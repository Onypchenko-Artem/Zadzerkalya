<?php
/**
 * 404.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<div class="container section empty-state">
		<p class="kicker">404</p>
		<h1><?php esc_html_e( 'Сторінку не знайдено', 'zadzerkalya' ); ?></h1>
		<p class="muted"><?php esc_html_e( 'Можливо, адресу змінили. Поверніться на головну або скористайтеся меню.', 'zadzerkalya' ); ?></p>
		<p>
			<?php
			zadzerkalya_button(
				array(
					'label'   => __( 'На головну', 'zadzerkalya' ),
					'url'     => home_url( '/' ),
					'variant' => 'secondary',
				)
			);
			?>
		</p>
	</div>
</main>
<?php
get_footer();
