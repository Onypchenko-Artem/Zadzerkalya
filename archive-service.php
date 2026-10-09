<?php
/**
 * Перелік послуг, доки сторінку з шаблоном «Послуги» не створено.
 *
 * @package Zadzerkalya
 */

get_header();

$services_page_id = zadzerkalya_get_services_page_id();
$services_url     = $services_page_id ? get_permalink( $services_page_id ) : get_post_type_archive_link( 'service' );
$field            = static function ( $name, $fallback = '' ) use ( $services_page_id ) {
	$value = ( $services_page_id && function_exists( 'get_field' ) ) ? get_field( $name, $services_page_id ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$type_slug    = isset( $_GET['type'] ) ? sanitize_title( wp_unslash( $_GET['type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$current_term = $type_slug ? get_term_by( 'slug', $type_slug, 'service_type' ) : null;
if ( ! $current_term || is_wp_error( $current_term ) ) {
	$current_term = null;
}

$query_args = array(
	'post_type'      => 'service',
	'posts_per_page' => -1,
	'orderby'        => array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
	),
);

if ( $current_term ) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => 'service_type',
			'field'    => 'term_id',
			'terms'    => (int) $current_term->term_id,
		),
	);
}

$services = new WP_Query( $query_args );
?>
<main id="content" class="site-main services-page">
	<?php
	get_template_part(
		'template-parts/section',
		'about-hero',
		array(
			'title'      => $field( 'services_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" ),
			'quote'      => $field( 'services_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' ),
			'scene'      => $field( 'services_hero_scene' ),
			'background' => $field( 'services_hero_background' ),
			'current'    => __( 'Послуги', 'zadzerkalya' ),
			'heading_id' => 'services-hero-title',
		)
	);

	get_template_part(
		'template-parts/section',
		'services-feed',
		array(
			'title'        => $field( 'services_feed_title', __( 'Наші послуги', 'zadzerkalya' ) ),
			'heading_id'   => 'services-feed-title',
			'all_label'    => __( 'Всі послуги', 'zadzerkalya' ),
			'all_url'      => $services_url ? $services_url : home_url( '/services/' ),
			'taxonomy'     => 'service_type',
			'topic_param'  => 'type',
			'current_term' => $current_term,
			'query'        => $services,
			'button_label' => $field( 'services_card_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
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
