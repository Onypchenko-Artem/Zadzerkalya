<?php
/**
 * Десктопне меню послуг: категорії, послуги категорії, фото.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mega = zadzerkalya_get_services_mega();

if ( empty( $mega['categories'] ) ) {
	return;
}
?>
<div id="services-mega" class="services-mega" aria-hidden="true">
	<div class="services-mega__layout">
		<button class="services-mega__close" type="button">
			<span class="screen-reader-text"><?php esc_html_e( 'Закрити', 'zadzerkalya' ); ?></span>
			<svg class="icon icon--close" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<circle cx="20" cy="20" r="20" fill="#88BBF2"/>
				<g transform="translate(1 2.5)">
					<path class="icon-social__stroke" d="M2.9221 11.4193C1.92896 15.5413 1.14821 18.6693 0.719609 21.0625C0.16673 24.1498 0.758908 26.7885 1.37973 28.2253C2.41298 30.6166 6.51091 32.4854 10.6299 34.0148C12.0908 34.5572 14.528 34.5272 16.8233 34.4708C21.2901 34.3609 24.8567 33.1297 27.9691 31.3954C32.2228 29.0252 34.644 25.2846 35.8562 22.9885C38.0041 18.92 37.6303 14.5063 37.0482 11.5224C36.6012 9.23121 34.4825 7.25335 32.6979 5.39119C30.9973 3.61684 28.8785 2.51665 26.8198 1.57157C24.7225 0.608749 21.7843 0.493661 18.8244 0.500255C16.7412 0.504896 14.2218 1.66904 10.9153 3.20869C5.48858 5.73566 4.21053 8.00253 3.56711 9.15828C3.41023 9.52586 3.23928 9.98425 3.06192 10.5288C2.88456 11.0734 2.70598 11.6904 2.52199 12.326" stroke="#0F1A2D" stroke-linecap="round"/>
				</g>
				<path stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" d="M14.2 14.2 25.8 25.8M25.8 14.2 14.2 25.8"/>
			</svg>
		</button>
		<div class="services-mega__cats">
			<a class="services-mega__all is-active" href="<?php echo esc_url( $mega['all_url'] ); ?>" data-mega-target="services-mega-all"><?php esc_html_e( 'Всі послуги', 'zadzerkalya' ); ?></a>
			<?php foreach ( $mega['categories'] as $category ) : ?>
				<a
					class="services-mega__cat"
					href="<?php echo esc_url( $category['url'] ); ?>"
					data-mega-target="services-mega-<?php echo esc_attr( (string) $category['id'] ); ?>"
				><?php echo esc_html( $category['name'] ); ?></a>
			<?php endforeach; ?>
		</div>

		<span class="services-mega__rule" aria-hidden="true"></span>

		<div class="services-mega__services">
			<?php
			$all_services = get_posts(
				array(
					'post_type'      => 'service',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'orderby'        => array(
						'menu_order' => 'ASC',
						'title'      => 'ASC',
					),
					'no_found_rows'  => true,
				)
			);
			?>
			<div class="services-mega__panel is-active" id="services-mega-all">
				<?php foreach ( $all_services as $service ) : ?>
					<a class="services-mega__service" href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( zadzerkalya_service_menu_title( $service->ID ) ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $mega['categories'] as $category ) : ?>
				<div
					class="services-mega__panel"
					id="services-mega-<?php echo esc_attr( (string) $category['id'] ); ?>"
				>
					<?php foreach ( $category['services'] as $service ) : ?>
						<a class="services-mega__service" href="<?php echo esc_url( $service['url'] ); ?>"><?php echo esc_html( $service['title'] ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="services-mega__visual" aria-hidden="true">
			<?php $first_category_id = (int) ( $mega['categories'][0]['id'] ?? 0 ); ?>
			<?php foreach ( $mega['categories'] as $category ) : ?>
				<?php
				if ( ! $category['image_id'] ) {
					continue;
				}
				echo wp_get_attachment_image(
					(int) $category['image_id'],
					'large',
					false,
					array(
						'class'            => 'services-mega__photo' . ( $first_category_id === (int) $category['id'] ? ' is-active' : '' ),
						'alt'              => '',
						'data-mega-target' => 'services-mega-' . $category['id'],
						'loading'          => 'eager',
					)
				);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</div>
