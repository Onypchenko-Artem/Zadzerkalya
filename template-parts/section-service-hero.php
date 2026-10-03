<?php
/**
 * Головний банер сторінки послуги.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title            Заголовок. Розриви рядків зберігаються.
 *     @type string $description      Текст під заголовком.
 *     @type mixed  $photo            Зображення ACF (id, масив або URL).
 *     @type mixed  $background       Фон банера (id, масив або URL).
 *     @type bool   $show_description Чи показувати опис.
 *     @type bool   $show_button      Чи показувати CTA.
 *     @type bool   $show_photo       Чи показувати фото.
 *     @type string $button_label     Текст кнопки.
 *     @type string $button_url       Посилання кнопки.
 *     @type string $button_variant   primary|secondary.
 *     @type string $current          Поточний пункт хлібних крихт.
 *     @type string $crumb_label      Середній пункт хлібних крихт.
 *     @type string $crumb_url        URL середнього пункту.
 *     @type string $heading_id       id заголовка.
 * }
 */

$title            = $args['title'] ?? get_the_title();
$description      = $args['description'] ?? '';
$photo            = $args['photo'] ?? null;
$background       = $args['background'] ?? null;
$show_description = array_key_exists( 'show_description', $args ) ? (bool) $args['show_description'] : true;
$show_button      = array_key_exists( 'show_button', $args ) ? (bool) $args['show_button'] : true;
$show_photo       = array_key_exists( 'show_photo', $args ) ? (bool) $args['show_photo'] : true;
$button_label     = $args['button_label'] ?? __( 'забронювати первинну консультацію', 'zadzerkalya' );
$button_url       = $args['button_url'] ?? zadzerkalya_get_page_url( 'contacts' );
$button_variant   = $args['button_variant'] ?? 'primary';
$current          = $args['current'] ?? get_the_title();
$crumb_label      = $args['crumb_label'] ?? __( 'Послуги', 'zadzerkalya' );
$crumb_url        = $args['crumb_url'] ?? '';
$heading_id       = $args['heading_id'] ?? 'service-hero-title';

if ( ! $crumb_url ) {
	$archive  = get_post_type_archive_link( 'service' );
	$crumb_url = $archive ? $archive : home_url( '/services/' );
}

$show_description = $show_description && '' !== trim( wp_strip_all_tags( $description ) );
$show_button      = $show_button && '' !== trim( wp_strip_all_tags( $button_label ) );

$background_url = '';
if ( is_numeric( $background ) ) {
	$background_url = wp_get_attachment_image_url( (int) $background, 'full' );
} elseif ( is_array( $background ) ) {
	$background_url = $background['url'] ?? '';
} elseif ( is_string( $background ) ) {
	$background_url = $background;
}

$hero_class = 'service-hero';
if ( ! $show_photo ) {
	$hero_class .= ' service-hero--no-photo';
}
$hero_style = '';
if ( $background_url ) {
	$hero_class .= ' service-hero--has-background';
	$hero_style  = '--service-hero-background: url(' . esc_url( $background_url ) . ');';
}
?>
<section class="<?php echo esc_attr( $hero_class ); ?>"<?php echo $hero_style ? ' style="' . esc_attr( $hero_style ) . '"' : ''; ?> aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="service-hero__inner">
		<div class="service-hero__main">
			<div class="service-hero__container">
				<div class="service-hero__content">
					<div class="service-hero__breadcrumbs-container">
						<nav class="service-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Навігаційний ланцюжок', 'zadzerkalya' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Головна', 'zadzerkalya' ); ?></a>
							<span class="service-hero__crumb-arrow" aria-hidden="true"></span>
							<a href="<?php echo esc_url( $crumb_url ); ?>"><?php echo esc_html( $crumb_label ); ?></a>
							<span class="service-hero__crumb-arrow" aria-hidden="true"></span>
							<span aria-current="page"><?php echo esc_html( $current ); ?></span>
						</nav>

						<div class="service-hero__title">
							<h1 id="<?php echo esc_attr( $heading_id ); ?>"><?php echo wp_kses( nl2br( esc_html( $title ) ), array( 'br' => array() ) ); ?></h1>

							<?php if ( $show_description || $show_button ) : ?>
								<div class="service-hero__copy">
									<?php if ( $show_description ) : ?>
										<div class="service-hero__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
									<?php endif; ?>

									<?php if ( $show_button ) : ?>
										<?php
										zadzerkalya_button(
											array(
												'label'   => $button_label,
												'url'     => $button_url,
												'variant' => $button_variant,
											)
										);
										?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<img class="service-hero__bottom-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-left-line.svg' ); ?>" alt="" aria-hidden="true">
			</div>
		</div>

		<img class="service-hero__vertical-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-vertical-line.svg' ); ?>" alt="" aria-hidden="true">

		<?php if ( $show_photo ) : ?>
			<div class="service-hero__photo">
				<div class="service-hero__image" aria-hidden="true">
					<?php
					if ( ! zadzerkalya_acf_image( $photo, 'full', array( 'class' => 'service-hero__photo-image', 'alt' => '' ) ) ) {
						?>
						<img class="service-hero__photo-image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/team.jpg' ); ?>" alt="">
						<?php
					}
					?>
				</div>

				<div class="service-hero__photo-line" aria-hidden="true">
					<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-right-line.svg' ); ?>" alt="">
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
