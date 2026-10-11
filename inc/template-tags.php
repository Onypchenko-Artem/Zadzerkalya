<?php
/**
 * Допоміжні функції шаблонів.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Опублікована сторінка з шаблоном «Спеціалісти».
 *
 * @return int
 */
function zadzerkalya_get_specialists_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-templates/specialists.php',
		)
	);

	$page_id = $pages ? (int) $pages[0] : 0;

	return $page_id;
}

/**
 * Адреса сторінки спеціалістів або архіву, доки сторінку не створено.
 *
 * @return string
 */
function zadzerkalya_get_specialists_url() {
	$page_id = zadzerkalya_get_specialists_page_id();

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	$archive = get_post_type_archive_link( 'specialist' );

	return $archive ? $archive : home_url( '/specialists/' );
}

/**
 * Опублікована сторінка з шаблоном «Події».
 *
 * @return int
 */
function zadzerkalya_get_events_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-templates/events.php',
		)
	);

	$page_id = $pages ? (int) $pages[0] : 0;

	return $page_id;
}

/**
 * Адреса сторінки подій або архіву, доки сторінку не створено.
 *
 * @return string
 */
function zadzerkalya_get_events_url() {
	$page_id = zadzerkalya_get_events_page_id();

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	$archive = get_post_type_archive_link( 'event' );

	return $archive ? $archive : home_url( '/events/' );
}

/**
 * Опублікована сторінка з шаблоном «Послуги».
 *
 * @return int
 */
function zadzerkalya_get_services_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'page-templates/services.php',
		)
	);

	$page_id = $pages ? (int) $pages[0] : 0;

	return $page_id;
}

/**
 * Адреса сторінки послуг або архіву, доки сторінку не створено.
 *
 * @return string
 */
function zadzerkalya_get_services_url() {
	$page_id = zadzerkalya_get_services_page_id();

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	$archive = get_post_type_archive_link( 'service' );

	return $archive ? $archive : home_url( '/services/' );
}

function zadzerkalya_get_blog_url() {
	$page_id = (int) get_option( 'page_for_posts' );

	if ( $page_id ) {
		return get_permalink( $page_id );
	}

	return home_url( '/blog/' );
}

/**
 * Час прочитання статті в хвилинах.
 * Бере ACF `post_reading_time`, інакше рахує з тексту (~200 слів/хв).
 *
 * @param int $post_id ID запису.
 * @return int
 */
function zadzerkalya_reading_time_minutes( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( function_exists( 'get_field' ) ) {
		$manual = get_field( 'post_reading_time', $post_id );
		if ( null !== $manual && false !== $manual && '' !== $manual ) {
			return max( 1, (int) $manual );
		}
	}

	$plain = trim( wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post_id ) ) ) );
	$words = '' === $plain ? 0 : count( preg_split( '/\s+/u', $plain ) );

	return max( 1, (int) ceil( $words / 200 ) );
}

function zadzerkalya_get_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * Поле ACF зі сторінки «Про нас».
 *
 * @param string $name     Імʼя поля.
 * @param mixed  $fallback Значення, якщо поле порожнє.
 * @return mixed
 */
function zadzerkalya_about_field( $name, $fallback = '' ) {
	$page = get_page_by_path( 'about' );

	if ( ! $page ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template',
				'meta_value'     => 'page-templates/about.php',
			)
		);
		$page_id = $pages ? (int) $pages[0] : 0;
	} else {
		$page_id = (int) $page->ID;
	}

	$value = ( $page_id && function_exists( 'get_field' ) ) ? get_field( $name, $page_id ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
}

/**
 * Поле ACF поточної сторінки.
 *
 * @param string $name     Імʼя поля.
 * @param mixed  $fallback Значення, якщо поле порожнє.
 * @return mixed
 */
function zadzerkalya_field( $name, $fallback = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

	return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
}

/**
 * Текстові рядки репітера ACF.
 *
 * @param string $name Імʼя репітера.
 * @param string $key  Ключ підполя.
 * @return string[]
 */
function zadzerkalya_field_lines( $name, $key = 'text' ) {
	$rows = function_exists( 'get_field' ) ? get_field( $name ) : null;

	if ( ! is_array( $rows ) ) {
		return array();
	}

	$items = array();

	foreach ( $rows as $row ) {
		$text = is_array( $row ) ? ( $row[ $key ] ?? '' ) : $row;
		$text = is_string( $text ) ? trim( $text ) : '';

		if ( '' !== $text ) {
			$items[] = $text;
		}
	}

	return $items;
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
 * Текст згоди перед кнопкою форми.
 */
function zadzerkalya_form_consent() {
	echo '<p class="form-consent">' . esc_html__( 'Натискаючи на кнопку нижче, Ви погоджуєтесь з Політикою конфіденційності та Згодою на обробку персональних даних.', 'zadzerkalya' ) . '</p>';
}

/**
 * CTA-кнопка з Lottie-контуром (Primary / Secondary).
 *
 * @param array $args {
 *     @type string $label   Текст лейбла.
 *     @type string $url     Якщо є — рендериться <a>.
 *     @type string $variant primary|secondary.
 *     @type string $anim    Фіксована Lottie-анімація, наприклад button-secondary-mobile.
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
			'anim'    => '',
			'type'    => 'submit',
			'name'    => '',
			'value'   => '',
			'echo'    => true,
		)
	);

	$variant = in_array( $args['variant'], array( 'primary', 'secondary' ), true ) ? $args['variant'] : 'primary';
	$label   = esc_html( $args['label'] );
	$class   = 'lottie-button lottie-button--' . $variant;
	$anim    = preg_replace( '/[^a-z0-9-]/', '', (string) $args['anim'] );
	$anim_attr = $anim ? ' data-anim="' . esc_attr( $anim ) . '"' : '';
	$inner   = '<span class="lottie-button__anim" aria-hidden="true"></span><span class="lottie-button__label">' . $label . '</span>';
	$text    = trim( wp_strip_all_tags( $args['label'] ) );
	$length  = function_exists( 'mb_strlen' ) ? mb_strlen( $text ) : strlen( $text );
	$rows    = 'primary' === $variant && $length > 25 ? '2' : '1';

	if ( $args['url'] ) {
		$html = sprintf(
			'<a class="%1$s" data-variant="%2$s" data-mobile-rows="%3$s"%4$s href="%5$s">%6$s</a>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_attr( $rows ),
			$anim_attr,
			esc_url( $args['url'] ),
			$inner
		);
	} else {
		$name  = $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : '';
		$value = '' !== $args['value'] ? ' value="' . esc_attr( $args['value'] ) . '"' : '';
		$html  = sprintf(
			'<button class="%1$s" data-variant="%2$s" data-mobile-rows="%3$s"%4$s type="%5$s"%6$s%7$s>%8$s</button>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_attr( $rows ),
			$anim_attr,
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

/**
 * Пункт головного меню веде на сторінку послуг.
 *
 * @param WP_Post $item Пункт меню.
 * @return bool
 */
function zadzerkalya_is_services_menu_item( $item ) {
	if ( ! $item instanceof WP_Post ) {
		return false;
	}

	$page_id = zadzerkalya_get_services_page_id();
	if ( $page_id && 'page' === $item->object && (int) $item->object_id === $page_id ) {
		return true;
	}

	return untrailingslashit( (string) $item->url ) === untrailingslashit( zadzerkalya_get_services_url() );
}

/**
 * Категорії послуг і послуги всередині для десктопного меню.
 *
 * @return array{all_url: string, categories: array<int, array<string, mixed>>}
 */
function zadzerkalya_get_services_mega() {
	static $mega = null;

	if ( null !== $mega ) {
		return $mega;
	}

	$services_url = zadzerkalya_get_services_url();
	$empty        = array(
		'all_url'    => $services_url,
		'categories' => array(),
	);

	$terms = get_terms(
		array(
			'taxonomy'   => 'service_type',
			'hide_empty' => true,
			'parent'     => 0,
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'service_type',
				'hide_empty' => true,
			)
		);
	}

	if ( is_wp_error( $terms ) || ! $terms ) {
		$mega = $empty;
		return $mega;
	}

	usort(
		$terms,
		static function ( $a, $b ) {
			$order_a = function_exists( 'get_field' ) ? (int) get_field( 'service_type_order', $a ) : 0;
			$order_b = function_exists( 'get_field' ) ? (int) get_field( 'service_type_order', $b ) : 0;

			if ( $order_a !== $order_b ) {
				if ( 0 === $order_a ) {
					return 1;
				}
				if ( 0 === $order_b ) {
					return -1;
				}
				return $order_a <=> $order_b;
			}

			return $a->term_id <=> $b->term_id;
		}
	);

	$query = new WP_Query(
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

	if ( $query->posts ) {
		update_object_term_cache( wp_list_pluck( $query->posts, 'ID' ), 'service' );
	}

	$by_term = array();
	foreach ( $query->posts as $service ) {
		$service_terms = get_the_terms( $service, 'service_type' );
		if ( ! $service_terms || is_wp_error( $service_terms ) ) {
			continue;
		}

		foreach ( $service_terms as $service_term ) {
			$root_id = (int) $service_term->term_id;
			if ( $service_term->parent ) {
				$ancestors = get_ancestors( (int) $service_term->term_id, 'service_type', 'taxonomy' );
				$root_id   = $ancestors ? (int) end( $ancestors ) : $root_id;
			}
			$by_term[ $root_id ][] = $service;
		}
	}

	$categories = array();
	foreach ( $terms as $term ) {
		$items = $by_term[ (int) $term->term_id ] ?? array();
		if ( ! $items ) {
			continue;
		}

		$image_id = 0;
		if ( function_exists( 'get_field' ) ) {
			$image = get_field( 'service_type_image', $term );
			if ( is_numeric( $image ) ) {
				$image_id = (int) $image;
			} elseif ( is_array( $image ) ) {
				$image_id = (int) ( $image['ID'] ?? $image['id'] ?? 0 );
			}
		}
		if ( ! $image_id ) {
			$image_id = (int) get_post_thumbnail_id( $items[0] );
		}

		$services = array();
		$seen     = array();
		foreach ( $items as $service ) {
			if ( isset( $seen[ $service->ID ] ) ) {
				continue;
			}
			$seen[ $service->ID ] = true;
			$services[]           = array(
				'title' => get_the_title( $service ),
				'url'   => get_permalink( $service ),
			);
		}

		$categories[] = array(
			'id'       => (int) $term->term_id,
			'name'     => $term->name,
			'url'      => add_query_arg( 'type', $term->slug, $services_url ),
			'image_id' => $image_id,
			'services' => $services,
		);
	}

	$mega = array(
		'all_url'    => $services_url,
		'categories' => $categories,
	);

	return $mega;
}

/**
 * Клас пункту «Послуги», щоб відкрити меню категорій.
 *
 * @param string[] $classes Класи пункту.
 * @param WP_Post  $item    Пункт меню.
 * @param stdClass $args    Аргументи меню.
 * @return string[]
 */
function zadzerkalya_services_menu_item_class( $classes, $item, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $classes;
	}

	$mega = zadzerkalya_get_services_mega();
	if ( zadzerkalya_is_services_menu_item( $item ) && ! empty( $mega['categories'] ) ) {
		$classes[] = 'menu-item--services';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'zadzerkalya_services_menu_item_class', 10, 3 );

/**
 * Атрибути посилання «Послуги» для випадаючого меню.
 *
 * @param array<string, string> $atts Атрибути.
 * @param WP_Post               $item Пункт меню.
 * @param stdClass              $args Аргументи меню.
 * @return array<string, string>
 */
function zadzerkalya_services_menu_link_atts( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $atts;
	}

	$mega = zadzerkalya_get_services_mega();
	if ( ! zadzerkalya_is_services_menu_item( $item ) || empty( $mega['categories'] ) ) {
		return $atts;
	}

	$atts['aria-haspopup'] = 'true';
	$atts['aria-expanded'] = 'false';
	$atts['aria-controls'] = 'services-mega';

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'zadzerkalya_services_menu_link_atts', 10, 3 );
