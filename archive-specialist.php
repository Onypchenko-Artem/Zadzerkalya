<?php
/**
 * Сторінка всіх спеціалістів.
 *
 * @package Zadzerkalya
 */

get_header();

$hero_title = zadzerkalya_about_field( 'about_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
$hero_quote = zadzerkalya_about_field( 'about_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
$hero_scene = zadzerkalya_about_field( 'about_hero_scene' );
?>
<main id="content" class="site-main specialists-page">
	<?php
	get_template_part(
		'template-parts/section',
		'about-hero',
		array(
			'title'      => $hero_title,
			'quote'      => $hero_quote,
			'scene'      => $hero_scene,
			'current'    => __( 'Спеціалісти', 'zadzerkalya' ),
			'heading_id' => 'specialists-hero-title',
		)
	);
	?>

	<?php
	get_template_part( 'template-parts/section', 'specialists-team' );
	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'source' => 'specialists',
		)
	);
	?>
</main>
<?php
get_footer();
