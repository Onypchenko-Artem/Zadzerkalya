<?php
/**
 * Прайс-лист сторінки «Вартість послуг».
 *
 * @package Zadzerkalya
 */

$title = function_exists( 'get_field' ) ? trim( (string) get_field( 'prices_list_title' ) ) : '';

$rows  = function_exists( 'get_field' ) ? get_field( 'prices_list_items' ) : null;
$items = array();

if ( is_array( $rows ) ) {
	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$service_id = isset( $row['service'] ) ? (int) $row['service'] : 0;
		$service_id = ( $service_id && 'publish' === get_post_status( $service_id ) ) ? $service_id : 0;
		$item_title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';
		$item_price = isset( $row['price'] ) ? trim( (string) $row['price'] ) : '';

		if ( '' === $item_title && $service_id ) {
			$item_title = get_the_title( $service_id );
		}

		if ( '' === $item_title && '' === $item_price ) {
			continue;
		}

		$items[] = array(
			'title' => $item_title,
			'price' => $item_price,
			'url'   => $service_id ? zadzerkalya_service_url( $service_id ) : '',
		);
	}
}

if ( ! $items ) {
	$items = zadzerkalya_get_default_price_items();
}

?>
<section class="price-list"<?php echo '' !== $title ? ' aria-labelledby="price-list-title"' : ''; ?>>
	<?php if ( '' !== $title ) : ?>
		<header class="price-list__heading">
			<img class="price-list__heading-line price-list__heading-line--left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-heading-line-left.svg' ); ?>" alt="" aria-hidden="true">
			<h2 id="price-list-title"><?php echo esc_html( $title ); ?></h2>
			<img class="price-list__heading-line price-list__heading-line--right" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-heading-line-right.svg' ); ?>" alt="" aria-hidden="true">
		</header>
	<?php endif; ?>

	<div class="price-list__items">
		<?php foreach ( $items as $item ) : ?>
			<?php
			$price_lines = preg_split( "/\r\n|\n|\r/", (string) $item['price'] );
			$price_lines = is_array( $price_lines ) ? $price_lines : array( (string) $item['price'] );
			$price_lines = array_values( array_filter( array_map( 'trim', $price_lines ), 'strlen' ) );
			?>
			<article class="price-list__item">
				<div class="price-list__row<?php echo count( $price_lines ) > 1 ? ' price-list__row--multiline' : ''; ?>">
					<div class="price-list__details">
						<h3>
							<?php if ( ! empty( $item['url'] ) ) : ?>
								<a class="price-list__link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['title'] ); ?>
							<?php endif; ?>
						</h3>
						<p>
							<?php foreach ( $price_lines as $line ) : ?>
								<span class="price-list__price-line"><?php echo esc_html( $line ); ?></span>
							<?php endforeach; ?>
						</p>
					</div>

					<?php
					zadzerkalya_button(
						array(
							'label'      => __( 'записатися', 'zadzerkalya' ),
							'variant'    => 'primary',
							'type'       => 'button',
							'attributes' => array(
								'data-contact-popup-open'   => 'true',
								'data-contact-popup-label'  => __( 'забронювати консультацію', 'zadzerkalya' ),
								'data-contact-popup-source' => 'price-popup',
								'aria-haspopup'             => 'dialog',
								'aria-controls'             => 'contact-popup',
								'data-service'              => $item['title'],
							),
						)
					);
					?>
				</div>
				<img class="price-list__separator" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/price-row-line.svg' ); ?>" width="721" height="5" alt="" aria-hidden="true">
			</article>
		<?php endforeach; ?>
	</div>
</section>
