<?php
/**
 * Герой-банер сторінки «Про нас».
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title      Заголовок. Розриви рядків зберігаються.
 *     @type string $quote      Текст під заголовком.
 *     @type mixed  $scene      Зображення ACF (id, масив або URL).
 *     @type mixed  $background Фон банера (id, масив або URL).
 *     @type string $current    Поточний пункт хлібних крихт.
 *     @type string $heading_id id заголовка.
 * }
 */

$title      = $args['title'] ?? '';
$quote      = $args['quote'] ?? '';
$scene      = $args['scene'] ?? null;
$background = $args['background'] ?? null;
$current    = $args['current'] ?? __( 'Про нас', 'zadzerkalya' );
$heading_id = $args['heading_id'] ?? 'about-hero-title';

$background_url = '';
if ( is_numeric( $background ) ) {
	$background_url = wp_get_attachment_image_url( (int) $background, 'full' );
} elseif ( is_array( $background ) ) {
	$background_url = $background['url'] ?? '';
} elseif ( is_string( $background ) ) {
	$background_url = $background;
}

$hero_class = 'about-hero';
$hero_style = '';
if ( $background_url ) {
	$hero_class .= ' about-hero--has-background';
	$hero_style  = '--about-hero-background: url(' . esc_url( $background_url ) . ');';
}
?>
<section class="<?php echo esc_attr( $hero_class ); ?>"<?php echo $hero_style ? ' style="' . esc_attr( $hero_style ) . '"' : ''; ?> aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="about-hero__inner">
		<div class="about-hero__copy">
			<div class="about-hero__heading">
				<nav class="about-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Навігаційний ланцюжок', 'zadzerkalya' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Головна', 'zadzerkalya' ); ?></a>
					<svg viewBox="0 0 20 12" aria-hidden="true">
						<path d="M1 6h17M13 1l5 5-5 5" />
					</svg>
					<span aria-current="page"><?php echo esc_html( $current ); ?></span>
				</nav>

				<h1 id="<?php echo esc_attr( $heading_id ); ?>">
					<?php echo wp_kses( nl2br( esc_html( $title ) ), array( 'br' => array() ) ); ?>
				</h1>
			</div>

			<div class="about-hero__footer">
				<p><?php echo esc_html( $quote ); ?></p>
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-left-line.svg' ); ?>" alt="" aria-hidden="true">
			</div>
		</div>

		<img class="about-hero__vertical-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-vertical-line.svg' ); ?>" alt="" aria-hidden="true">

		<div class="about-hero__visual" aria-hidden="true">
			<div class="about-hero__scene">
				<?php
				if ( ! zadzerkalya_acf_image( $scene, 'large', array( 'class' => 'about-hero__scene-image', 'alt' => '' ) ) ) {
					?>
					<img class="about-hero__scene-image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-img.png' ); ?>" alt="">
					<?php
				}
				?>
			</div>
			<img class="about-hero__right-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-right-line.svg' ); ?>" alt="">
		</div>
	</div>
</section>
