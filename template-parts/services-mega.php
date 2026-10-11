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
		<div class="services-mega__cats">
			<a class="services-mega__all" href="<?php echo esc_url( $mega['all_url'] ); ?>"><?php esc_html_e( 'Всі послуги', 'zadzerkalya' ); ?></a>
			<?php foreach ( $mega['categories'] as $index => $category ) : ?>
				<a
					class="services-mega__cat<?php echo 0 === $index ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $category['url'] ); ?>"
					data-mega-target="services-mega-<?php echo esc_attr( (string) $category['id'] ); ?>"
				><?php echo esc_html( $category['name'] ); ?></a>
			<?php endforeach; ?>
		</div>

		<span class="services-mega__rule" aria-hidden="true"></span>

		<div class="services-mega__services">
			<?php foreach ( $mega['categories'] as $index => $category ) : ?>
				<div
					class="services-mega__panel<?php echo 0 === $index ? ' is-active' : ''; ?>"
					id="services-mega-<?php echo esc_attr( (string) $category['id'] ); ?>"
				>
					<?php foreach ( $category['services'] as $service ) : ?>
						<a class="services-mega__service" href="<?php echo esc_url( $service['url'] ); ?>"><?php echo esc_html( $service['title'] ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="services-mega__visual" aria-hidden="true">
			<?php foreach ( $mega['categories'] as $index => $category ) : ?>
				<?php
				if ( ! $category['image_id'] ) {
					continue;
				}
				echo wp_get_attachment_image(
					(int) $category['image_id'],
					'large',
					false,
					array(
						'class'            => 'services-mega__photo' . ( 0 === $index ? ' is-active' : '' ),
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
