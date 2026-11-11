<?php
/**
 * Template Name: Послуги
 *
 * @package Zadzerkalya
 */

get_header();

$field = static function ( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$page_title      = get_the_title();
$services_url    = get_permalink();
$hero_title      = $field( 'services_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
$hero_quote      = $field( 'services_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
$hero_scene      = $field( 'services_hero_scene' );
$hero_background = $field( 'services_hero_background' );
$current_term = zadzerkalya_get_current_service_type();
$services     = zadzerkalya_query_services_feed( $current_term );
?>
<main id="content" class="site-main services-page">
	<?php
	get_template_part(
		'template-parts/section',
		'about-hero',
		array(
			'title'      => $hero_title,
			'quote'      => $hero_quote,
			'scene'      => $hero_scene,
			'background' => $hero_background,
			'current'    => $page_title,
			'heading_id' => 'services-hero-title',
		)
	);

	get_template_part(
		'template-parts/section',
		'services-feed',
		array(
			'title'         => $field( 'services_feed_title', __( 'Наші послуги', 'zadzerkalya' ) ),
			'heading_id'    => 'services-feed-title',
			'all_label'     => __( 'Всі послуги', 'zadzerkalya' ),
			'all_url'       => $services_url,
			'taxonomy'      => 'service_type',
			'current_term'  => $current_term,
			'query'         => $services,
			'button_label'  => $field( 'services_card_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
		)
	);

	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'title'        => $field( 'services_form_title', __( 'Кожен день — важливий!', 'zadzerkalya' ) ),
			'description'  => $field( 'services_form_text', __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ) ),
			'button_label' => $field( 'services_form_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
			'source'       => 'services',
		)
	);
	?>
</main>
<?php
get_footer();
