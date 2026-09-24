<?php
/**
 * Template Name: Спеціалісти
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main specialists-page">
	<?php
	while ( have_posts() ) :
		the_post();

		$field = static function ( $name, $fallback = '' ) {
			$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		get_template_part(
			'template-parts/section',
			'about-hero',
			array(
				'title'      => $field( 'specialists_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" ),
				'quote'      => $field( 'specialists_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' ),
				'scene'      => $field( 'specialists_hero_scene' ),
				'background' => $field( 'specialists_hero_background' ),
				'current'    => get_the_title(),
				'heading_id' => 'specialists-hero-title',
			)
		);
	endwhile;

	get_template_part( 'template-parts/section', 'specialists-team' );

	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'title'        => $field( 'specialists_form_title', 'Кожен день — важливий!' ),
			'description'  => $field( 'specialists_form_text', 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!' ),
			'button_label' => $field( 'specialists_form_button', 'забронювати первинну консультацію' ),
			'source'       => 'specialists',
		)
	);
	?>
</main>
<?php
get_footer();
