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
 *     @type string $crumb_label Середній пункт хлібних крихт (напр. «Події», «Блог»). Порожній рядок — без середнього пункту.
 *     @type string $crumb_url   URL середнього пункту.
 *     @type string $heading_id  id заголовка.
 * }
 */

$title       = $args['title'] ?? get_the_title();
$description = $args['description'] ?? '';
$photo       = $args['photo'] ?? null;
$background  = $args['background'] ?? null;
$current     = $args['current'] ?? get_the_title();
$crumb_label = $args['crumb_label'] ?? __( 'Події', 'zadzerkalya' );
$crumb_url   = $args['crumb_url'] ?? zadzerkalya_get_events_url();
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
							<?php if ( '' !== trim( (string) $crumb_label ) ) : ?>
								<span class="event-hero__crumb-arrow" aria-hidden="true"></span>
								<?php if ( $crumb_url ) : ?>
									<a href="<?php echo esc_url( $crumb_url ); ?>"><?php echo esc_html( $crumb_label ); ?></a>
								<?php else : ?>
									<span><?php echo esc_html( $crumb_label ); ?></span>
								<?php endif; ?>
							<?php endif; ?>
							<span class="event-hero__crumb-arrow" aria-hidden="true"></span>
							<span aria-current="page"><?php echo esc_html( $current ); ?></span>
						</nav>

						<?php $has_description = is_string( $description ) && '' !== trim( wp_strip_all_tags( $description ) ); ?>
						<div class="event-hero__title<?php echo $has_description ? '' : ' event-hero__title--solo'; ?>">
							<h1 id="<?php echo esc_attr( $heading_id ); ?>"><?php echo esc_html( $title ); ?></h1>
							<?php if ( $has_description ) : ?>
								<div class="event-hero__description">
									<?php
									$description_html = (string) $description;
									if ( $description_html === wp_strip_all_tags( $description_html ) ) {
										$description_html = wpautop( $description_html );
									}
									echo wp_kses_post( $description_html );
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<span class="event-hero__bottom-line" aria-hidden="true"></span>
			</div>
		</div>

		<span class="event-hero__vertical-line" aria-hidden="true"></span>

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

			<div class="event-hero__photo-line" aria-hidden="true"></div>
		</div>
	</div>
</section>
