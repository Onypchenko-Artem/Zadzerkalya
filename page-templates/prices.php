<?php
/**
 * Template Name: Вартість послуг
 *
 * @package Zadzerkalya
 */

get_header();

$field = static function ( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$hero_title      = $field( 'prices_hero_title', __( 'Вартість послуг', 'zadzerkalya' ) );
$hero_photo      = $field( 'prices_hero_photo' );
$hero_background = $field( 'prices_hero_background' );
?>
<main id="content" class="site-main prices-page">
	<?php
	get_template_part(
		'template-parts/section',
		'contacts-hero',
		array(
			'title'      => $hero_title,
			'photo'      => $hero_photo,
			'background' => $hero_background,
			'current'    => get_the_title(),
			'heading_id' => 'prices-hero-title',
		)
	);
	?>

	<?php get_template_part( 'template-parts/section', 'price-list' ); ?>

	<?php
	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'source' => 'prices',
		)
	);
	?>
</main>
<?php
get_footer();
