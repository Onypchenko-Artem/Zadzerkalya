<?php
/**
 * Допоміжні функції шаблонів.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_get_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function zadzerkalya_posted_on() {
	$time_string = sprintf(
		'<time class="entry-date published" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	echo '<span class="posted-on">' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function zadzerkalya_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => __( 'Назад', 'zadzerkalya' ),
			'next_text' => __( 'Далі', 'zadzerkalya' ),
		)
	);
}

function zadzerkalya_service_price_html( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$price   = get_post_meta( $post_id, '_zdk_price', true );

	if ( ! $price ) {
		return '';
	}

	$prefix = get_post_meta( $post_id, '_zdk_price_from', true ) ? __( 'від ', 'zadzerkalya' ) : '';
	return esc_html( $prefix . $price );
}

function zadzerkalya_query_latest( $post_type, $count = 3 ) {
	return new WP_Query(
		array(
			'post_type'           => $post_type,
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}

function zadzerkalya_body_open() {
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
}

/**
 * CTA-кнопка з Lottie-контуром (Primary / Secondary).
 *
 * @param array $args {
 *     @type string $label   Текст лейбла.
 *     @type string $url     Якщо є — рендериться <a>.
 *     @type string $variant primary|secondary.
 *     @type string $type    type для <button>.
 *     @type string $name    name для <button>.
 *     @type string $value   value для <button>.
 *     @type bool   $echo    Вивести чи повернути HTML.
 * }
 * @return string
 */
function zadzerkalya_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label'   => '',
			'url'     => '',
			'variant' => 'primary',
			'type'    => 'submit',
			'name'    => '',
			'value'   => '',
			'echo'    => true,
		)
	);

	$variant = in_array( $args['variant'], array( 'primary', 'secondary' ), true ) ? $args['variant'] : 'primary';
	$label   = esc_html( $args['label'] );
	$class   = 'lottie-button lottie-button--' . $variant;
	$inner   = '<span class="lottie-button__anim" aria-hidden="true"></span><span class="lottie-button__label">' . $label . '</span>';

	if ( $args['url'] ) {
		$html = sprintf(
			'<a class="%1$s" data-variant="%2$s" href="%3$s">%4$s</a>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_url( $args['url'] ),
			$inner
		);
	} else {
		$name  = $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : '';
		$value = '' !== $args['value'] ? ' value="' . esc_attr( $args['value'] ) . '"' : '';
		$html  = sprintf(
			'<button class="%1$s" data-variant="%2$s" type="%3$s"%4$s%5$s>%6$s</button>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_attr( $args['type'] ),
			$name,
			$value,
			$inner
		);
	}

	if ( $args['echo'] ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $html;
}

/**
 * Вивід ACF-зображення (id, масив або URL).
 *
 * @param mixed  $field Значення поля.
 * @param string $size  Розмір.
 * @param array  $attr  Атрибути img.
 * @return bool Чи було виведено зображення.
 */
function zadzerkalya_acf_image( $field, $size = 'full', $attr = array() ) {
	if ( empty( $field ) ) {
		return false;
	}

	if ( is_numeric( $field ) ) {
		$html = wp_get_attachment_image( (int) $field, $size, false, $attr );
		if ( $html ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return true;
		}
		return false;
	}

	if ( is_array( $field ) ) {
		$id = isset( $field['ID'] ) ? (int) $field['ID'] : 0;
		if ( $id ) {
			$html = wp_get_attachment_image( $id, $size, false, $attr );
			if ( $html ) {
				echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				return true;
			}
		}
		$url = $field['url'] ?? '';
		if ( $url ) {
			printf(
				'<img src="%s" alt="%s">',
				esc_url( $url ),
				esc_attr( $attr['alt'] ?? ( $field['alt'] ?? '' ) )
			);
			return true;
		}
		return false;
	}

	if ( is_string( $field ) ) {
		printf(
			'<img src="%s" alt="%s">',
			esc_url( $field ),
			esc_attr( $attr['alt'] ?? '' )
		);
		return true;
	}

	return false;
}
