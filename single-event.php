<?php
/**
 * Стаття події.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main event-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$date  = get_post_meta( get_the_ID(), '_zdk_event_date', true );
		$time  = get_post_meta( get_the_ID(), '_zdk_event_time', true );
		$place = get_post_meta( get_the_ID(), '_zdk_event_place', true );

		$field = static function ( $name, $fallback = '' ) {
			$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		$hero_title       = $field( 'event_hero_title', get_the_title() );
		$hero_description = $field( 'event_hero_description', get_the_excerpt() );
		$hero_photo       = $field( 'event_hero_photo', get_post_thumbnail_id() );
		$hero_background  = $field( 'event_hero_background' );
		$event_author     = $field( 'event_author' );
		$event_time       = $field( 'event_time', $time );
		$event_place      = $field( 'event_place', $place );
		$event_price      = $field( 'event_price' );
		$button_label     = $field( 'event_button_label', __( 'забронювати участь', 'zadzerkalya' ) );
		$button_url       = $field( 'event_button_url', zadzerkalya_get_page_url( 'contacts' ) );

		get_template_part(
			'template-parts/section',
			'event-hero',
			array(
				'title'       => $hero_title,
				'description' => $hero_description,
				'photo'       => $hero_photo,
				'background'  => $hero_background,
				'current'     => get_the_title(),
				'heading_id'  => 'event-hero-title',
			)
		);

		get_template_part(
			'template-parts/section',
			'event-article',
			array(
				'author'       => $event_author,
				'date'         => $date,
				'time'         => $event_time,
				'place'        => $event_place,
				'price'        => $event_price,
				'button_label' => $button_label,
				'button_url'   => $button_url,
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
