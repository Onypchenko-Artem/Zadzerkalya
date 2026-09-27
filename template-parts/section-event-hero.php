<?php
/**
 * Головний банер окремої події.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title       Заголовок банера.
 *     @type string $description Текст під заголовком.
 *     @type mixed  $photo       Зображення ACF (id, масив або URL).
 *     @type mixed  $background  Фон банера (id, масив або URL).
 *     @type string $current     Поточний пункт хлібних крихт.
 *     @type string $heading_id  id заголовка.
 * }
 */

$title       = $args['title'] ?? get_the_title();
$description = $args['description'] ?? '';
$photo       = $args['photo'] ?? null;
$background  = $args['background'] ?? null;
$current     = $args['current'] ?? get_the_title();
$heading_id  = $args['heading_id'] ?? 'event-hero-title';

$background_url = '';
if ( is_numeric( $background ) ) {
	$background_url = wp_get_attachment_image_url( (int) $background, 'full' );
} elseif ( is_array( $background ) ) {
	$background_url = $background['url'] ?? '';
} elseif ( is_string( $background ) ) {
	$background_url = $background;
}

$hero_class = 'event-hero';
$hero_style = '';
if ( $background_url ) {
	$hero_class .= ' event-hero--has-background';
	$hero_style  = '--event-hero-background: url(' . esc_url( $background_url ) . ');';
}
?>
<section class="<?php echo esc_attr( $hero_class ); ?>"<?php echo $hero_style ? ' style="' . esc_attr( $hero_style ) . '"' : ''; ?> aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="event-hero__inner">
		<div class="event-hero__main">
			<div class="event-hero__container">
				<div class="event-hero__content">
					<div class="event-hero__breadcrumbs-container">
						<nav class="event-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Навігаційний ланцюжок', 'zadzerkalya' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Головна', 'zadzerkalya' ); ?></a>
							<svg viewBox="0 0 20 12" aria-hidden="true">
								<path d="M1 6h17M13 1l5 5-5 5" />
							</svg>
							<a href="<?php echo esc_url( zadzerkalya_get_events_url() ); ?>"><?php esc_html_e( 'Події', 'zadzerkalya' ); ?></a>
							<svg viewBox="0 0 20 12" aria-hidden="true">
								<path d="M1 6h17M13 1l5 5-5 5" />
							</svg>
							<span aria-current="page"><?php echo esc_html( $current ); ?></span>
						</nav>

						<div class="event-hero__title">
							<h1 id="<?php echo esc_attr( $heading_id ); ?>"><?php echo esc_html( $title ); ?></h1>
							<?php if ( $description ) : ?>
								<div class="event-hero__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<img class="event-hero__bottom-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-left-line.svg' ); ?>" alt="" aria-hidden="true">
			</div>

			<div class="event-hero__vertical-line">
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-vertical-line.svg' ); ?>" alt="" aria-hidden="true">
			</div>
		</div>

		<div class="event-hero__photo">
			<div class="event-hero__image" aria-hidden="true">
				<?php
				if ( ! zadzerkalya_acf_image( $photo, 'full', array( 'class' => 'event-hero__photo-image', 'alt' => '' ) ) ) {
					?>
					<img class="event-hero__photo-image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/team.jpg' ); ?>" alt="">
					<?php
				}
				?>
			</div>

			<div class="event-hero__photo-line" aria-hidden="true">
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-right-line.svg' ); ?>" alt="">
			</div>
		</div>
	</div>
</section>
