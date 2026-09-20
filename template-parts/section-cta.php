<?php
/**
 * Заклик до запису.
 *
 * @package Zadzerkalya
 */

$url = zadzerkalya_get_page_url( 'contacts' );
?>
<section class="cta">
	<div class="container">
		<h2><?php esc_html_e( 'Готові зробити перший крок?', 'zadzerkalya' ); ?></h2>
		<p><?php esc_html_e( 'Напишіть нам — підберемо спеціаліста і зручний час.', 'zadzerkalya' ); ?></p>
		<?php
		zadzerkalya_button(
			array(
				'label'   => __( 'Звʼяжіться з нами', 'zadzerkalya' ),
				'url'     => $url,
				'variant' => 'secondary',
			)
		);
		?>
	</div>
</section>
