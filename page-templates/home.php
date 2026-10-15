<?php
/**
 * Template Name: Головна
 *
 * @package Zadzerkalya
 */

get_header();

$hero_kicker        = function_exists( 'get_field' ) ? get_field( 'hero_kicker' ) : '';
$hero_title         = function_exists( 'get_field' ) ? get_field( 'hero_title' ) : '';
$hero_text          = function_exists( 'get_field' ) ? get_field( 'hero_text' ) : '';
$hero_btn           = function_exists( 'get_field' ) ? get_field( 'hero_btn' ) : '';
$hero_image         = function_exists( 'get_field' ) ? get_field( 'hero_image' ) : null;
$hero_illustration  = function_exists( 'get_field' ) ? get_field( 'hero_illustration' ) : null;

if ( ! $hero_title ) {
	$hero_title = __( 'Ми віримо в кожну дитину!', 'zadzerkalya' );
}

if ( ! $hero_btn ) {
	$hero_btn = __( 'Забронювати первинну консультацію', 'zadzerkalya' );
}
?>
<main id="content" class="site-main">
	<section class="hero">
		<div class="hero-inner">
			<div class="hero-top-lines" aria-hidden="true">
				<?php
				$hero_top_line = '<svg class="hero-top-lines__svg" viewBox="0 0 874 9" fill="none" preserveAspectRatio="none" focusable="false"><path d="M0.5 0.835174C85.0934 0.542463 169.687 0.249751 314.354 0.850013C459.02 1.45027 661.197 2.95238 869.5 4.5" stroke="#0F1A2D" stroke-linecap="round" vector-effect="non-scaling-stroke" transform="translate(2 2)"/></svg>';
				?>
				<span class="hero-top-lines__item"><?php echo $hero_top_line; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="hero-top-lines__item hero-top-lines__item--flip"><?php echo $hero_top_line; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>

			<div class="hero-layout">
				<div class="hero-copy">
					<?php if ( $hero_kicker ) : ?>
						<p class="hero-kicker"><?php echo esc_html( $hero_kicker ); ?></p>
					<?php endif; ?>
					<h1><?php echo esc_html( $hero_title ); ?></h1>

					<div class="hero-details">
						<div class="hero-description">
							<?php if ( $hero_text ) : ?>
								<p><?php echo wp_kses_post( nl2br( $hero_text ) ); ?></p>
							<?php endif; ?>

							<div class="hero-illustration">
								<?php
								if ( ! zadzerkalya_acf_image( $hero_illustration, 'medium' ) ) :
									?>
									<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-rabbit.png' ); ?>" alt="" aria-hidden="true">
								<?php endif; ?>
							</div>
						</div>

						<form class="hero-form" method="post" action="">
							<?php zadzerkalya_contact_notice(); ?>
							<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
							<input type="hidden" name="zadzerkalya_form_source" value="hero">
							<p class="hp-field" aria-hidden="true">
								<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
									<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
								</label>
							</p>
							<p class="hero-field">
								<label for="hero-name"><?php esc_html_e( 'Імʼя*', 'zadzerkalya' ); ?></label>
								<input id="hero-name" name="zadzerkalya_name" type="text" autocomplete="name" required>
							</p>
							<div class="hero-field-stack">
								<p class="hero-field">
									<label for="hero-phone"><?php esc_html_e( 'Телефон*', 'zadzerkalya' ); ?></label>
									<input id="hero-phone" name="zadzerkalya_phone" type="tel" autocomplete="tel" placeholder="+38 (000) 000-00-00" pattern="\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}" title="<?php esc_attr_e( 'Введіть номер повністю: +38 (000) 000-00-00', 'zadzerkalya' ); ?>" required>
								</p>
								<?php zadzerkalya_form_consent(); ?>
							</div>
							<?php
							zadzerkalya_button(
								array(
									'label'   => $hero_btn,
									'variant' => 'primary',
									'type'    => 'submit',
									'name'    => 'zadzerkalya_contact_submit',
									'value'   => '1',
								)
							);
							?>
						</form>
					</div>
				</div>

				<span class="hero-divider" aria-hidden="true"></span>

				<div class="hero-media">
					<?php
					if ( ! zadzerkalya_acf_image( $hero_image, 'zadzerkalya-hero', array( 'alt' => $hero_title ) ) ) :
						?>
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-img.png' ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>">
					<?php endif; ?>
				</div>
			</div>

			<span class="hero-bottom-line" aria-hidden="true"></span>
		</div>
	</section>

	<?php get_template_part( 'template-parts/section', 'service-when' ); ?>
	<?php get_template_part( 'template-parts/section', 'help' ); ?>
	<?php get_template_part( 'template-parts/section', 'services-showcase' ); ?>
	<?php get_template_part( 'template-parts/section', 'numbers' ); ?>
	<?php get_template_part( 'template-parts/section', 'benefits' ); ?>
	<?php get_template_part( 'template-parts/section', 'reviews' ); ?>
	<?php get_template_part( 'template-parts/section', 'faq' ); ?>
	<?php
	get_template_part(
		'template-parts/section',
		'form-home',
		array(
			'title'        => zadzerkalya_field( 'home_form_title', __( 'Кожен день — важливий!', 'zadzerkalya' ) ),
			'description'  => zadzerkalya_field( 'home_form_text', __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ) ),
			'button_label' => zadzerkalya_field( 'home_form_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) ),
			'source'       => 'home',
		)
	);
	?>
</main>
<?php
get_footer();
