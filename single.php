<?php
/**
 * Окрема стаття блогу — той самий макет, що й у події (без CTA-кнопки).
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main event-page">
	<?php
	while ( have_posts() ) :
		the_post();

		$field = static function ( $name, $fallback = '' ) {
			$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		$hero_title       = $field( 'event_hero_title', get_the_title() );
		$hero_description = $field( 'event_hero_description', get_the_excerpt() );
		$hero_photo       = $field( 'event_hero_photo', get_post_thumbnail_id() );
		$hero_background  = $field( 'event_hero_background' );
		$event_author     = $field( 'event_author' );
		$minutes          = zadzerkalya_reading_time_minutes();
		/* translators: %d — хвилини читання. */
		$reading_time     = sprintf( _n( '%d хв', '%d хв', $minutes, 'zadzerkalya' ), $minutes );

		get_template_part(
			'template-parts/section',
			'event-hero',
			array(
				'title'       => $hero_title,
				'description' => $hero_description,
				'photo'       => $hero_photo,
				'background'  => $hero_background,
				'current'     => get_the_title(),
				'crumb_label' => __( 'Блог', 'zadzerkalya' ),
				'crumb_url'   => zadzerkalya_get_blog_url(),
				'heading_id'  => 'post-hero-title',
			)
		);

		get_template_part(
			'template-parts/section',
			'event-article',
			array(
				'author'       => $event_author,
				'date'         => get_the_date( 'Y-m-d' ),
				'reading_time' => $reading_time,
				'share'        => true,
			)
		);

		get_template_part(
			'template-parts/section',
			'recommend',
			array(
				'title'      => __( 'Читайте також', 'zadzerkalya' ),
				'post_type'  => 'post',
				'count'      => 6,
				'exclude'    => get_the_ID(),
				'heading_id' => 'post-recommend-title',
			)
		);

		$blog_page_id = (int) get_option( 'page_for_posts' );
		$blog_field   = static function ( $name, $fallback = '' ) use ( $blog_page_id ) {
			$value = ( $blog_page_id && function_exists( 'get_field' ) ) ? get_field( $name, $blog_page_id ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		get_template_part(
			'template-parts/section',
			'form-home',
			array(
				'title'        => $blog_field( 'blog_form_title', __( 'Кожен день — важливий!', 'zadzerkalya' ) ),
				'description'  => $blog_field( 'blog_form_text', __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ) ),
				'button_label' => $blog_field( 'blog_form_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
				'source'       => 'blog',
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
