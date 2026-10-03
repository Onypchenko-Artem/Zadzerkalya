<?php
/**
 * Сторінка послуги.
 *
 * @package Zadzerkalya
 */

get_header();

$field = static function ( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
};

$flag = static function ( $name, $default = true ) {
	$value = get_post_meta( get_the_ID(), $name, true );

	if ( '' === $value ) {
		return $default;
	}

	return in_array( (string) $value, array( '1', 'true' ), true );
};
?>
<main id="content" class="site-main service-page">
	<?php
	while ( have_posts() ) :
		the_post();
		$price    = zadzerkalya_service_price_html();
		$duration = get_post_meta( get_the_ID(), '_zdk_duration', true );
		$note     = get_post_meta( get_the_ID(), '_zdk_price_note', true );

		$hero_title       = $field( 'service_hero_title', get_the_title() );
		$hero_description = $field( 'service_hero_description', get_the_excerpt() );
		$hero_photo       = $field( 'service_hero_photo', get_post_thumbnail_id() );
		$hero_background  = $field( 'service_hero_background' );
		$button_label     = $field( 'service_hero_button_label', __( 'забронювати первинну консультацію', 'zadzerkalya' ) );
		$button_url       = $field( 'service_hero_button_url', zadzerkalya_get_page_url( 'contacts' ) );
		$button_variant   = $field( 'service_hero_button_variant', 'primary' );

		get_template_part(
			'template-parts/section',
			'service-hero',
			array(
				'title'            => $hero_title,
				'description'      => $hero_description,
				'photo'            => $hero_photo,
				'background'       => $hero_background,
				'show_description' => $flag( 'service_hero_show_description', true ),
				'show_button'      => $flag( 'service_hero_show_button', true ),
				'show_photo'       => $flag( 'service_hero_show_photo', true ),
				'button_label'     => $button_label,
				'button_url'       => $button_url,
				'button_variant'   => $button_variant,
				'current'          => get_the_title(),
				'heading_id'       => 'service-hero-title',
			)
		);

		get_template_part(
			'template-parts/section',
			'service-about',
			array(
				'text'         => $field( 'service_about_text' ),
				'lead'         => $field( 'service_about_lead' ),
				'illustration' => $field( 'service_about_illustration' ),
				'photo'        => $field( 'service_about_photo' ),
			)
		);

		$cta_slides = function_exists( 'get_field' ) ? get_field( 'service_cta_slides' ) : array();

		get_template_part(
			'template-parts/section',
			'service-cta',
			array(
				'title'            => $field( 'service_cta_title' ),
				'description'      => $field( 'service_cta_description' ),
				'show_description' => $flag( 'service_cta_show_description', false ),
				'illustration'     => $field( 'service_cta_illustration' ),
				'background'       => $field( 'service_cta_background' ),
				'button_label'     => $field( 'service_cta_button_label' ),
				'button_url'       => $field( 'service_cta_button_url' ),
				'button_variant'   => $field( 'service_cta_button_variant', 'primary' ),
				'slides'           => is_array( $cta_slides ) ? $cta_slides : array(),
			)
		);
		?>
		<article <?php post_class( 'container section' ); ?>>
			<?php if ( $price || $duration || $note ) : ?>
				<div class="price-box">
					<?php if ( $price ) : ?>
						<p class="price"><?php echo esc_html( $price ); ?></p>
					<?php endif; ?>
					<?php if ( $duration ) : ?>
						<p><?php echo esc_html( $duration ); ?></p>
					<?php endif; ?>
					<?php if ( $note ) : ?>
						<p class="muted"><?php echo esc_html( $note ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="prose">
				<?php the_content(); ?>
			</div>
			<p>
				<a class="text-link" href="<?php echo esc_url( zadzerkalya_get_page_url( 'prices' ) ); ?>"><?php esc_html_e( 'Дивитися вартість послуг', 'zadzerkalya' ); ?></a>
			</p>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
