<?php
/**
 * Template Name: Контакти
 *
 * @package Zadzerkalya
 */

get_header();

$field = static function ( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$hero_title      = $field( 'contacts_hero_title', __( 'Наші контакти', 'zadzerkalya' ) );
$hero_photo      = $field( 'contacts_hero_photo' );
$hero_background = $field( 'contacts_hero_background' );
$info_title      = $field( 'contacts_info_title', "Чекаємо на вас у нашому\nЗадзеркальному просторі!" );
?>
<main id="content" class="site-main contacts-page">
	<?php
	get_template_part(
		'template-parts/section',
		'contacts-hero',
		array(
			'title'      => $hero_title,
			'photo'      => $hero_photo,
			'background' => $hero_background,
			'current'    => get_the_title(),
			'heading_id' => 'contacts-hero-title',
		)
	);
	?>

	<?php
	get_template_part(
		'template-parts/section',
		'contacts-info',
		array(
			'title' => $info_title,
		)
	);
	?>

	<?php
	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'source' => 'contacts',
		)
	);
	?>
</main>
<?php
get_footer();
