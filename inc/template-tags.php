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

/**
 * Подія вже відбулася: після вказаного часу, або наприкінці дня, якщо час не задано.
 *
 * @param string $date Дата Y-m-d.
 * @param string $time Час або діапазон, наприклад 18:00–20:00.
 * @return bool
 */
function zadzerkalya_event_has_passed( $date, $time = '' ) {
	$date = trim( (string) $date );
	if ( '' === $date ) {
		return false;
	}

	$clock = '23:59:59';
	if ( preg_match_all( '/(\d{1,2}):(\d{2})/', (string) $time, $matches, PREG_SET_ORDER ) ) {
		$last  = end( $matches );
		$clock = sprintf( '%02d:%02d:00', (int) $last[1], (int) $last[2] );
	}

	$moment = date_create( $date . ' ' . $clock, wp_timezone() );
	if ( ! $moment ) {
		return false;
	}

	return $moment < new DateTimeImmutable( 'now', wp_timezone() );
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
	$privacy_url = get_privacy_policy_url();
	if ( ! $privacy_url ) {
		$privacy_url = home_url( '/privacy-policy/' );
	}

	$consent_url  = home_url( '/zgoda-na-obrobku-personalnyh-danyh/' );
	$consent_page = get_page_by_path( 'zgoda-na-obrobku-personalnyh-danyh' );
	if ( ! $consent_page ) {
		$consent_page = get_page_by_path( 'consent' );
	}
	if ( $consent_page instanceof WP_Post ) {
		$consent_url = get_permalink( $consent_page );
	}

	printf(
		'<p class="form-consent">%1$s <a href="%2$s">%3$s</a> %4$s <a href="%5$s">%6$s</a>.</p>',
		esc_html__( 'Натискаючи на кнопку нижче, Ви погоджуєтесь з', 'zadzerkalya' ),
		esc_url( $privacy_url ),
		esc_html__( 'Політикою конфіденційності', 'zadzerkalya' ),
		esc_html__( 'та', 'zadzerkalya' ),
		esc_url( $consent_url ),
		esc_html__( 'Згодою на обробку персональних даних', 'zadzerkalya' )
	);
}

/**
 * CTA-кнопка з Lottie-контуром (Primary / Secondary).
 *
 * @param array $args {
 *     @type string $label   Текст лейбла.
 *     @type string $url     Якщо є — рендериться <a>.
 *     @type string $variant primary|secondary.
 *     @type string $anim    Фіксована Lottie-анімація, наприклад button-secondary-mobile.
 *     @type string $type       type для <button>.
 *     @type string $name       name для <button>.
 *     @type string $value      value для <button>.
 *     @type array  $attributes Додаткові HTML-атрибути.
 *     @type bool   $echo       Вивести чи повернути HTML.
 * }
 * @return string
 */
function zadzerkalya_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'label'      => '',
			'url'        => '',
			'variant'    => 'primary',
			'anim'       => '',
			'type'       => 'submit',
			'name'       => '',
			'value'      => '',
			'attributes' => array(),
			'echo'       => true,
		)
	);

	$variant     = in_array( $args['variant'], array( 'primary', 'secondary' ), true ) ? $args['variant'] : 'primary';
	$label_plain = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $args['label'] ) ) );
	$label_key   = function_exists( 'mb_strtolower' ) ? mb_strtolower( $label_plain ) : strtolower( $label_plain );

	if ( 'повідомити про наступну подію' === $label_key ) {
		$args['label'] = "повідомити про наступну\nподію";
	}

	$label_lines = preg_split( "/\r\n|\n|\r/", (string) $args['label'] );
	$label_parts = array();

	foreach ( $label_lines as $label_line ) {
		$label_line = trim( $label_line );

		if ( '' === $label_line ) {
			continue;
		}

		$label_parts[] = esc_html( $label_line );
	}

	if ( count( $label_parts ) > 1 ) {
		$label = implode( '<br>', $label_parts );
	} else {
		$label = esc_html( $args['label'] );
	}
	$class   = 'lottie-button lottie-button--' . $variant;
	$anim      = preg_replace( '/[^a-z0-9-]/', '', (string) $args['anim'] );
	$anim_attr = $anim ? ' data-anim="' . esc_attr( $anim ) . '"' : '';
	$extra     = '';

	foreach ( (array) $args['attributes'] as $attr => $attr_value ) {
		$attr = preg_replace( '/[^a-z0-9_:-]/i', '', (string) $attr );
		if ( '' === $attr || in_array( $attr, array( 'class', 'href', 'type', 'name', 'value' ), true ) ) {
			continue;
		}
		$extra .= ' ' . $attr . '="' . esc_attr( (string) $attr_value ) . '"';
	}
	$inner   = '<span class="lottie-button__anim" aria-hidden="true"></span><span class="lottie-button__label">' . $label . '</span>';
	$text    = trim( wp_strip_all_tags( $args['label'] ) );
	$length  = function_exists( 'mb_strlen' ) ? mb_strlen( $text ) : strlen( $text );
	$rows    = 'primary' === $variant && $length > 25 ? '2' : '1';
	$booking = function_exists( 'mb_strtolower' ) ? mb_strtolower( $text ) : strtolower( $text );

	if ( $args['url'] && 'забронювати первинну консультацію' === $booking ) {
		$args['url'] = '#contact-form';
	}

	if ( $args['url'] ) {
		$html = sprintf(
			'<a class="%1$s" data-variant="%2$s" data-mobile-rows="%3$s"%4$s href="%5$s"%6$s>%7$s</a>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_attr( $rows ),
			$anim_attr,
			esc_url( $args['url'] ),
			$extra,
			$inner
		);
	} else {
		$name  = $args['name'] ? ' name="' . esc_attr( $args['name'] ) . '"' : '';
		$value = '' !== $args['value'] ? ' value="' . esc_attr( $args['value'] ) . '"' : '';
		$html  = sprintf(
			'<button class="%1$s" data-variant="%2$s" data-mobile-rows="%3$s"%4$s type="%5$s"%6$s%7$s%8$s>%9$s</button>',
			esc_attr( $class ),
			esc_attr( $variant ),
			esc_attr( $rows ),
			$anim_attr,
			esc_attr( $args['type'] ),
			$name,
			$value,
			$extra,
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
 * Зображення послуги для вітрини: мініатюра, інакше фото героя.
 *
 * @param int $service_id ID послуги.
 * @return mixed ID вкладення, масив ACF або порожнє значення.
 */
function zadzerkalya_service_showcase_image( $service_id ) {
	$service_id = (int) $service_id;
	$thumbnail  = $service_id ? get_post_thumbnail_id( $service_id ) : 0;

	if ( $thumbnail ) {
		return $thumbnail;
	}

	if ( $service_id && function_exists( 'get_field' ) ) {
		return get_field( 'service_hero_photo', $service_id );
	}

	return null;
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
 * Назва послуги у меню. Порожнє поле лишає запасний підпис.
 *
 * @param int    $service_id ID послуги.
 * @param string $fallback   Підпис, якщо поле порожнє.
 * @return string
 */
function zadzerkalya_service_menu_title( $service_id, $fallback = '' ) {
	$service_id = (int) $service_id;
	$title      = '';

	if ( $service_id && function_exists( 'get_field' ) ) {
		$title = trim( (string) get_field( 'service_menu_title', $service_id ) );
	}

	if ( '' !== $title ) {
		return $title;
	}

	if ( '' !== (string) $fallback ) {
		return (string) $fallback;
	}

	return $service_id ? get_the_title( $service_id ) : '';
}

/**
 * У меню пункт послуги показує окрему назву, якщо вона задана.
 *
 * @param string  $title Текст пункту.
 * @param WP_Post $item  Пункт меню.
 * @return string
 */
function zadzerkalya_service_nav_menu_title( $title, $item ) {
	if ( ! $item instanceof WP_Post || 'post_type' !== $item->type || 'service' !== $item->object ) {
		return $title;
	}

	return zadzerkalya_service_menu_title( (int) $item->object_id, $title );
}
add_filter( 'nav_menu_item_title', 'zadzerkalya_service_nav_menu_title', 10, 2 );

/**
 * Фото типу послуги з поля ACF.
 *
 * @param WP_Term $term Тип послуги.
 * @return int
 */
function zadzerkalya_service_type_image_id( $term ) {
	if ( ! $term instanceof WP_Term || ! function_exists( 'get_field' ) ) {
		return 0;
	}

	$image = get_field( 'service_type_image', $term );
	if ( is_numeric( $image ) ) {
		return (int) $image;
	}

	if ( is_array( $image ) ) {
		return (int) ( $image['ID'] ?? $image['id'] ?? 0 );
	}

	return 0;
}

/**
 * Опубліковані послуги типу — для колонки меню, якщо під типом нічого не вкладено.
 *
 * @param int $term_id ID типу послуги.
 * @return array{services: array<int, array{title: string, url: string}>, image_id: int}
 */
function zadzerkalya_services_of_type( $term_id ) {
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
			'tax_query'      => array(
				array(
					'taxonomy'         => 'service_type',
					'field'            => 'term_id',
					'terms'            => (int) $term_id,
					'include_children' => true,
				),
			),
		)
	);

	$services = array();
	$image_id = 0;

	foreach ( $query->posts as $service ) {
		if ( ! $image_id ) {
			$image    = zadzerkalya_service_showcase_image( $service->ID );
			$image_id = is_numeric( $image ) ? (int) $image : 0;
		}

		$services[] = array(
			'title' => zadzerkalya_service_menu_title( $service->ID ),
			'url'   => get_permalink( $service ),
		);
	}

	return array(
		'services' => $services,
		'image_id' => $image_id,
	);
}

/**
 * Випадаюче меню послуг з вкладених пунктів головного меню.
 * Порожній результат означає, що структуру ще не зібрали — лишається автоматичний перелік.
 *
 * @return array{all_url: string, categories: array<int, array<string, mixed>>}|null
 */
function zadzerkalya_services_mega_from_menu() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return null;
	}

	$items = wp_get_nav_menu_items( (int) $locations['primary'] );
	if ( ! is_array( $items ) || ! $items ) {
		return null;
	}

	$services_item = null;
	foreach ( $items as $item ) {
		if ( zadzerkalya_is_services_menu_item( $item ) ) {
			$services_item = $item;
			break;
		}
	}

	if ( ! $services_item ) {
		return null;
	}

	$children = array();
	foreach ( $items as $item ) {
		$children[ (int) $item->menu_item_parent ][] = $item;
	}

	$categories_items = $children[ (int) $services_item->ID ] ?? array();
	if ( ! $categories_items ) {
		return null;
	}

	$services_url = zadzerkalya_get_services_url();
	$categories   = array();

	foreach ( $categories_items as $category_item ) {
		$term     = null;
		$url      = $category_item->url;
		$image_id = 0;

		if ( 'taxonomy' === $category_item->type && 'service_type' === $category_item->object ) {
			$term = get_term( (int) $category_item->object_id, 'service_type' );
			if ( $term && ! is_wp_error( $term ) ) {
				$url      = add_query_arg( 'type', $term->slug, $services_url );
				$image_id = zadzerkalya_service_type_image_id( $term );
			} else {
				$term = null;
			}
		} elseif ( 'post_type' === $category_item->type && 'service' === $category_item->object ) {
			$image    = zadzerkalya_service_showcase_image( (int) $category_item->object_id );
			$image_id = is_numeric( $image ) ? (int) $image : 0;
		}

		$category_name = $category_item->title;
		if ( 'post_type' === $category_item->type && 'service' === $category_item->object ) {
			$category_name = zadzerkalya_service_menu_title( (int) $category_item->object_id, $category_item->title );
		}

		$services = array();
		foreach ( $children[ (int) $category_item->ID ] ?? array() as $service_item ) {
			$service_title = $service_item->title;
			if ( 'post_type' === $service_item->type && 'service' === $service_item->object ) {
				$service_title = zadzerkalya_service_menu_title( (int) $service_item->object_id, $service_item->title );
			}

			$services[] = array(
				'title' => $service_title,
				'url'   => $service_item->url,
			);

			if ( ! $image_id && 'post_type' === $service_item->type && 'service' === $service_item->object ) {
				$image    = zadzerkalya_service_showcase_image( (int) $service_item->object_id );
				$image_id = is_numeric( $image ) ? (int) $image : 0;
			}
		}

		if ( ! $services && $term ) {
			$filled   = zadzerkalya_services_of_type( (int) $term->term_id );
			$services = $filled['services'];
			if ( ! $image_id ) {
				$image_id = $filled['image_id'];
			}
		}

		$categories[] = array(
			'id'       => (int) $category_item->ID,
			'name'     => $category_name,
			'url'      => $url,
			'image_id' => $image_id,
			'services' => $services,
		);
	}

	return array(
		'all_url'    => $services_url,
		'categories' => $categories,
	);
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

	$from_menu = zadzerkalya_services_mega_from_menu();
	if ( null !== $from_menu ) {
		$mega = $from_menu;
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
				'title' => zadzerkalya_service_menu_title( $service->ID ),
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

/**
 * Якір заголовка для змісту політики.
 *
 * @param string             $text Текст заголовка.
 * @param array<string, int> $seen Уже використані якорі.
 * @return string
 */
function zadzerkalya_policy_heading_id( $text, &$seen ) {
	$base = sanitize_title( $text );
	if ( '' === $base ) {
		$base = 'section';
	}

	$id = $base;
	if ( isset( $seen[ $id ] ) ) {
		$seen[ $id ]++;
		$id = $base . '-' . $seen[ $id ];
	} else {
		$seen[ $id ] = 1;
	}

	return $id;
}

/**
 * Текст пункту списку без вкладених списків.
 *
 * @param DOMElement $node Елемент пункту або заголовка.
 * @return string
 */
function zadzerkalya_policy_node_label( DOMElement $node ) {
	$clone = $node->cloneNode( true );
	$lists = array();

	foreach ( array( 'ol', 'ul' ) as $tag ) {
		$found = $clone->getElementsByTagName( $tag );
		foreach ( $found as $list ) {
			$lists[] = $list;
		}
	}

	foreach ( $lists as $list ) {
		if ( $list->parentNode ) {
			$list->parentNode->removeChild( $list );
		}
	}

	$text = preg_replace( '/\s+/u', ' ', $clone->textContent );

	return trim( is_string( $text ) ? $text : '' );
}

/**
 * Заголовок верхнього пункту списку, без заголовків із вкладених списків.
 *
 * @param DOMElement $item Пункт списку.
 * @param DOMXPath   $xpath Запит по дереву.
 * @return DOMElement|null
 */
function zadzerkalya_policy_item_heading( DOMElement $item, DOMXPath $xpath ) {
	$heading = $xpath->query( './/*[self::h2 or self::h3 or self::h4][count(ancestor::ol)=1]', $item );

	if ( $heading && $heading->length && $heading->item( 0 ) instanceof DOMElement ) {
		return $heading->item( 0 );
	}

	return null;
}

/**
 * Чи є в елемента клас.
 *
 * @param DOMElement $node  Елемент.
 * @param string     $class Клас.
 * @return bool
 */
function zadzerkalya_policy_has_class( DOMElement $node, $class ) {
	$classes = preg_split( '/\s+/', trim( $node->getAttribute( 'class' ) ) );

	return is_array( $classes ) && in_array( $class, $classes, true );
}

/**
 * Шаблон підписів контактного рядка.
 *
 * @return string
 */
function zadzerkalya_policy_contact_label_pattern() {
	return '(?:e-?mail|email|ел\.?\s*пошта|електронна\s+пошта|тел(?:ефон)?|phone|tel|адреса|address|telegram|viber|instagram|facebook|сайт|website)';
}

/**
 * Відомий підпис контакту: E-mail, Телефон, Адреса тощо.
 *
 * @param string $label Підпис без двокрапки.
 * @return bool
 */
function zadzerkalya_policy_is_known_contact_label( $label ) {
	$pattern = '/^' . zadzerkalya_policy_contact_label_pattern() . '$/iu';

	return (bool) preg_match( $pattern, trim( (string) $label ) );
}

/**
 * Короткий рядок над контактами, без нумерації пункту і без речення.
 *
 * @param string $text Текст абзацу.
 * @return bool
 */
function zadzerkalya_policy_is_contact_title( $text ) {
	$text   = trim( (string) $text );
	$length = mb_strlen( $text );

	if ( $length < 3 || $length > 140 ) {
		return false;
	}

	if ( preg_match( '/^\d+(?:\.\d+)*\.?\s/u', $text ) ) {
		return false;
	}

	if ( preg_match( '/[.!?](?:\s|$)/u', $text ) ) {
		return false;
	}

	return true;
}

/**
 * Внутрішній HTML елемента.
 *
 * @param DOMElement $element Елемент.
 * @return string
 */
function zadzerkalya_policy_element_inner_html( DOMElement $element ) {
	$html = '';

	foreach ( $element->childNodes as $child ) {
		$html .= $element->ownerDocument->saveHTML( $child );
	}

	return $html;
}

/**
 * Додає фрагмент HTML всередину елемента.
 *
 * @param DOMElement $parent Батьківський елемент.
 * @param string     $html   Фрагмент.
 */
function zadzerkalya_policy_append_html( DOMElement $parent, $html ) {
	$html = trim( (string) $html );
	if ( '' === $html ) {
		return;
	}

	$document = $parent->ownerDocument;
	if ( ! $document instanceof DOMDocument ) {
		return;
	}

	if ( ! preg_match( '/[<&]/', $html ) ) {
		$parent->appendChild( $document->createTextNode( html_entity_decode( $html, ENT_QUOTES, 'UTF-8' ) ) );
		return;
	}

	$fragment = new DOMDocument();
	$previous = libxml_use_internal_errors( true );
	$fragment->loadHTML(
		'<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><div id="zdk-fragment">' . $html . '</div></body></html>'
	);
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	$source = ( new DOMXPath( $fragment ) )->query( '//*[@id="zdk-fragment"]' )->item( 0 );
	if ( ! $source instanceof DOMElement ) {
		$parent->appendChild( $document->createTextNode( wp_strip_all_tags( $html ) ) );
		return;
	}

	foreach ( iterator_to_array( $source->childNodes ) as $child ) {
		$parent->appendChild( $document->importNode( $child, true ) );
	}
}

/**
 * Нормалізує текст рядка контакту.
 *
 * @param string $text Текст.
 * @return string
 */
function zadzerkalya_policy_contact_plain_text( $text ) {
	$text = html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' );
	$text = str_replace( array( "\xc2\xa0", '：' ), array( ' ', ':' ), $text );
	$text = preg_replace( '/\s+/u', ' ', $text );

	return trim( is_string( $text ) ? $text : '' );
}

/**
 * Ділить абзац на рядки за <br>.
 *
 * @param string $html Внутрішній HTML.
 * @return array<int, array{html: string, text: string}>
 */
function zadzerkalya_policy_split_lines( $html ) {
	$parts = preg_split( '/<br\s*\/?>/i', (string) $html );
	$lines = array();

	if ( ! is_array( $parts ) ) {
		return $lines;
	}

	foreach ( $parts as $part ) {
		$part = trim( $part );
		$text = zadzerkalya_policy_contact_plain_text( wp_strip_all_tags( $part ) );
		if ( '' === $text ) {
			continue;
		}

		$lines[] = array(
			'html' => $part,
			'text' => $text,
		);
	}

	return $lines;
}

/**
 * Розбирає рядок «Підпис: значення».
 *
 * @param string $html   HTML рядка.
 * @param string $text   Текст рядка.
 * @param bool   $strict Лише відомі підписи.
 * @return array{label: string, value: string}|null
 */
function zadzerkalya_policy_parse_contact_line( $html, $text, $strict ) {
	if ( ! preg_match( '/^([^:]+):\s*(.+)$/u', $text, $matches ) ) {
		return null;
	}

	$label = trim( $matches[1] );
	$value = trim( $matches[2] );
	if ( '' === $label || '' === $value || mb_strlen( $label ) > 40 ) {
		return null;
	}

	if ( $strict && ! zadzerkalya_policy_is_known_contact_label( $label ) ) {
		return null;
	}

	$stripped = preg_replace(
		'/^\s*(?:<(?:strong|b)[^>]*>\s*)?' . preg_quote( $label, '/' ) . '\s*:\s*(?:\s*<\/(?:strong|b)>)?\s*/iu',
		'',
		$html,
		1,
		$count
	);

	if ( ! is_string( $stripped ) || ! $count || '' === trim( wp_strip_all_tags( $stripped ) ) ) {
		$stripped = esc_html( $value );
	} else {
		$stripped = zadzerkalya_policy_contact_value_html( trim( $stripped ) );
	}

	return array(
		'label' => $label . ':',
		'value' => $stripped,
	);
}

/**
 * Робить посилання з голого e-mail або телефону.
 *
 * @param string $html Значення.
 * @return string
 */
function zadzerkalya_policy_contact_value_html( $html ) {
	$plain = trim( wp_strip_all_tags( $html ) );
	if ( $plain !== trim( $html ) ) {
		return $html;
	}

	if ( is_email( $plain ) ) {
		return '<a href="mailto:' . esc_attr( $plain ) . '">' . esc_html( $plain ) . '</a>';
	}

	$tel = preg_replace( '/[^\d+]/', '', $plain );
	if ( is_string( $tel ) && preg_match( '/^\+?\d{10,15}$/', $tel ) && preg_match( '/^\+?[\d\s().-]+$/', $plain ) ) {
		return '<a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $plain ) . '</a>';
	}

	return $html;
}

/**
 * Чи послідовність рядків є назвою і контактами.
 *
 * @param array<int, array{html: string, text: string}> $lines  Рядки.
 * @param bool                                           $strict Лише відомі підписи.
 * @return bool
 */
function zadzerkalya_policy_lines_are_contact_sequence( array $lines, $strict ) {
	$seen_row = false;

	foreach ( $lines as $line ) {
		$row = zadzerkalya_policy_parse_contact_line( $line['html'], $line['text'], $strict );
		if ( null !== $row ) {
			$seen_row = true;
			continue;
		}

		if ( $seen_row || ( $strict && ! zadzerkalya_policy_is_contact_title( $line['text'] ) ) ) {
			return false;
		}
	}

	return $seen_row;
}

/**
 * Збирає назву і рядки контактів.
 *
 * @param array<int, array{html: string, text: string}> $lines  Рядки.
 * @param bool                                           $strict Лише відомі підписи.
 * @return array{title: string, rows: array<int, array{label: string, value: string}>}|null
 */
function zadzerkalya_policy_lines_to_contact( array $lines, $strict ) {
	$title    = array();
	$rows     = array();
	$seen_row = false;

	foreach ( $lines as $line ) {
		$row = zadzerkalya_policy_parse_contact_line( $line['html'], $line['text'], $strict );
		if ( null !== $row ) {
			$seen_row = true;
			$rows[]   = $row;
			continue;
		}

		if ( $seen_row || ( $strict && ! zadzerkalya_policy_is_contact_title( $line['text'] ) ) ) {
			return null;
		}

		$title[] = $line['html'];
	}

	$min_rows = $strict ? 2 : 1;
	if ( count( $rows ) < $min_rows ) {
		return null;
	}

	return array(
		'title' => implode( ' ', $title ),
		'rows'  => $rows,
	);
}

/**
 * Рядки зі списку визначень.
 *
 * @param DOMElement $list Список.
 * @return array<int, array{html: string, text: string}>
 */
function zadzerkalya_policy_dl_lines( DOMElement $list ) {
	$lines = array();
	$label = null;

	foreach ( $list->childNodes as $child ) {
		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		$tag = strtolower( $child->nodeName );
		if ( 'div' === $tag ) {
			$lines = array_merge( $lines, zadzerkalya_policy_dl_lines( $child ) );
			continue;
		}

		if ( 'dt' === $tag ) {
			$label = rtrim( zadzerkalya_policy_contact_plain_text( $child->textContent ), ':' );
			continue;
		}

		if ( 'dd' === $tag && null !== $label && '' !== $label ) {
			$value_html = trim( zadzerkalya_policy_element_inner_html( $child ) );
			$lines[]    = array(
				'html' => $label . ': ' . $value_html,
				'text' => zadzerkalya_policy_contact_plain_text( $label . ': ' . $child->textContent ),
			);
			$label      = null;
		}
	}

	return $lines;
}

/**
 * Вузли назви і списку рядків.
 *
 * @param DOMDocument                                        $dom        Документ.
 * @param string                                             $title_html HTML назви.
 * @param array<int, array{label: string, value: string}>    $rows       Рядки.
 * @return DOMElement[]
 */
function zadzerkalya_policy_create_contact_nodes( DOMDocument $dom, $title_html, array $rows ) {
	$nodes      = array();
	$title_html = trim( (string) $title_html );

	if ( '' !== $title_html ) {
		$title = $dom->createElement( 'p' );
		$title->setAttribute( 'class', 'policy-contacts__title' );
		zadzerkalya_policy_append_html( $title, $title_html );
		$nodes[] = $title;
	}

	$list = $dom->createElement( 'div' );
	$list->setAttribute( 'class', 'policy-contacts__list' );

	foreach ( $rows as $row ) {
		$line = $dom->createElement( 'div' );
		$line->setAttribute( 'class', 'policy-contacts__row' );

		$label = $dom->createElement( 'span' );
		$label->setAttribute( 'class', 'policy-contacts__label' );
		$label->appendChild( $dom->createTextNode( $row['label'] ) );

		$value = $dom->createElement( 'div' );
		$value->setAttribute( 'class', 'policy-contacts__value' );
		zadzerkalya_policy_append_html( $value, $row['value'] );

		$line->appendChild( $label );
		$line->appendChild( $value );
		$list->appendChild( $line );
	}

	$nodes[] = $list;

	return $nodes;
}

/**
 * Блок контактів із лініями.
 *
 * @param DOMDocument                                        $dom        Документ.
 * @param string                                             $title_html HTML назви.
 * @param array<int, array{label: string, value: string}>    $rows       Рядки.
 * @return DOMElement
 */
function zadzerkalya_policy_create_contacts_block( DOMDocument $dom, $title_html, array $rows ) {
	$block = $dom->createElement( 'div' );
	$block->setAttribute( 'class', 'policy-contacts' );

	foreach ( zadzerkalya_policy_create_contact_nodes( $dom, $title_html, $rows ) as $node ) {
		$block->appendChild( $node );
	}

	return $block;
}

/**
 * Група policy-contacts: абзаци або список визначень стають рядками з лініями.
 *
 * @param DOMElement $group Група.
 */
function zadzerkalya_policy_normalize_contact_group( DOMElement $group ) {
	$container = $group;

	foreach ( $group->childNodes as $child ) {
		if ( $child instanceof DOMElement && zadzerkalya_policy_has_class( $child, 'wp-block-group__inner-container' ) ) {
			$container = $child;
			break;
		}
	}

	foreach ( $container->childNodes as $child ) {
		if ( $child instanceof DOMElement && zadzerkalya_policy_has_class( $child, 'policy-contacts__list' ) ) {
			return;
		}
	}

	$lines = array();
	$nodes = array();

	foreach ( iterator_to_array( $container->childNodes ) as $child ) {
		if ( ! $child instanceof DOMElement ) {
			continue;
		}

		$tag = strtolower( $child->nodeName );
		if ( 'dl' === $tag ) {
			$lines   = array_merge( $lines, zadzerkalya_policy_dl_lines( $child ) );
			$nodes[] = $child;
			continue;
		}

		if ( 'p' === $tag ) {
			$lines   = array_merge( $lines, zadzerkalya_policy_split_lines( zadzerkalya_policy_element_inner_html( $child ) ) );
			$nodes[] = $child;
		}
	}

	$parsed = zadzerkalya_policy_lines_to_contact( $lines, false );
	if ( null === $parsed || ! $nodes ) {
		return;
	}

	$document = $group->ownerDocument;
	if ( ! $document instanceof DOMDocument ) {
		return;
	}

	$created = zadzerkalya_policy_create_contact_nodes( $document, $parsed['title'], $parsed['rows'] );
	$first   = $nodes[0];
	foreach ( $created as $node ) {
		$container->insertBefore( $node, $first );
	}

	foreach ( $nodes as $node ) {
		if ( $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		}
	}
}

/**
 * Послідовність абзаців, яку можна зібрати в контакти.
 *
 * @param DOMElement[] $children Елементи одного батька.
 * @param int          $start    Індекс початку.
 * @return array{nodes: DOMElement[], title: string, rows: array<int, array{label: string, value: string}>}|null
 */
function zadzerkalya_policy_match_sibling_run( array $children, $start ) {
	$count = count( $children );
	if ( $start >= $count || 'p' !== strtolower( $children[ $start ]->nodeName ) ) {
		return null;
	}

	$collected_nodes = array();
	$collected_lines = array();

	for ( $index = $start; $index < $count; $index++ ) {
		$current = $children[ $index ];
		if ( 'p' !== strtolower( $current->nodeName ) || zadzerkalya_policy_has_class( $current, 'policy-contacts__title' ) ) {
			break;
		}

		$element_lines = zadzerkalya_policy_split_lines( zadzerkalya_policy_element_inner_html( $current ) );
		if ( ! $element_lines ) {
			if ( ! $collected_nodes ) {
				return null;
			}
			$collected_nodes[] = $current;
			continue;
		}

		$trial = array_merge( $collected_lines, $element_lines );
		if ( ! zadzerkalya_policy_lines_are_contact_sequence( $trial, true ) ) {
			break;
		}

		$collected_nodes[] = $current;
		$collected_lines   = $trial;
	}

	$parsed = zadzerkalya_policy_lines_to_contact( $collected_lines, true );
	if ( null === $parsed ) {
		return null;
	}

	return array(
		'nodes' => $collected_nodes,
		'title' => $parsed['title'],
		'rows'  => $parsed['rows'],
	);
}

/**
 * Шукає контактні абзаци і згортає їх у блок із лініями.
 *
 * @param DOMElement $parent Батьківський елемент.
 */
function zadzerkalya_policy_find_contact_runs( DOMElement $parent ) {
	if ( zadzerkalya_policy_has_class( $parent, 'policy-contacts' ) ) {
		return;
	}

	$children = array();
	foreach ( $parent->childNodes as $child ) {
		if ( $child instanceof DOMElement ) {
			$children[] = $child;
		}
	}

	foreach ( $children as $index => $child ) {
		if ( zadzerkalya_policy_has_class( $child, 'policy-contacts' ) ) {
			continue;
		}

		$run = zadzerkalya_policy_match_sibling_run( $children, $index );
		if ( null !== $run ) {
			$document = $parent->ownerDocument;
			if ( ! $document instanceof DOMDocument ) {
				return;
			}

			$block = zadzerkalya_policy_create_contacts_block( $document, $run['title'], $run['rows'] );
			$parent->insertBefore( $block, $run['nodes'][0] );
			foreach ( $run['nodes'] as $node ) {
				if ( $node->parentNode ) {
					$node->parentNode->removeChild( $node );
				}
			}

			zadzerkalya_policy_find_contact_runs( $parent );
			return;
		}

		zadzerkalya_policy_find_contact_runs( $child );
	}
}

/**
 * Перетворює контактні абзаци політики на блок із горизонтальними лініями.
 *
 * @param DOMElement $root Корінь вмісту.
 */
function zadzerkalya_policy_enhance_contacts( DOMElement $root ) {
	$found = ( new DOMXPath( $root->ownerDocument ) )->query(
		'.//*[contains(concat(" ", normalize-space(@class), " "), " policy-contacts ")]',
		$root
	);

	if ( $found ) {
		foreach ( $found as $group ) {
			if ( $group instanceof DOMElement && ! zadzerkalya_policy_has_class( $group, 'policy-contacts__list' ) ) {
				zadzerkalya_policy_normalize_contact_group( $group );
			}
		}
	}

	zadzerkalya_policy_find_contact_runs( $root );
}

/**
 * Зміст політики: заголовки і верхні пункти нумерованих списків.
 *
 * Перший ненумерований h2 — назва документа, у зміст не входить.
 *
 * @param string $html HTML вмісту сторінки.
 * @return array{content: string, toc: array<int, array{id: string, text: string}>}
 */
function zadzerkalya_policy_prepare_content( $html ) {
	$result = array(
		'content' => $html,
		'toc'     => array(),
	);

	if ( ! is_string( $html ) || '' === trim( $html ) ) {
		return $result;
	}

	$previous = libxml_use_internal_errors( true );
	$dom      = new DOMDocument();
	$loaded   = $dom->loadHTML(
		'<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><div id="zdk-policy-root">' . $html . '</div></body></html>'
	);
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded ) {
		return $result;
	}

	$xpath = new DOMXPath( $dom );
	$root  = $xpath->query( '//*[@id="zdk-policy-root"]' )->item( 0 );
	if ( ! $root instanceof DOMElement ) {
		return $result;
	}

	zadzerkalya_policy_enhance_contacts( $root );

	$nodes = $xpath->query(
		'.//*[self::h2 or self::h3 or self::h4 or self::li][not(ancestor::li)]',
		$root
	);
	if ( ! $nodes ) {
		return $result;
	}

	$seen             = array();
	$is_first_heading = true;
	$list_numbers     = array();

	foreach ( $nodes as $node ) {
		if ( ! $node instanceof DOMElement ) {
			continue;
		}

		$tag    = strtolower( $node->nodeName );
		$target = $node;
		$text   = '';

		if ( 'li' === $tag ) {
			$parent = $node->parentNode;
			if ( ! $parent instanceof DOMElement || 'ol' !== strtolower( $parent->nodeName ) ) {
				continue;
			}

			$heading    = zadzerkalya_policy_item_heading( $node, $xpath );
			$has_nested = $xpath->query( './/ol', $node );
			if ( ! $heading && ( ! $has_nested || 0 === $has_nested->length ) ) {
				continue;
			}

			$text = $heading ? zadzerkalya_policy_node_label( $heading ) : zadzerkalya_policy_node_label( $node );
			if ( '' === $text ) {
				continue;
			}

			$list_id = spl_object_id( $parent );
			if ( ! isset( $list_numbers[ $list_id ] ) ) {
				$list_numbers[ $list_id ] = 0;
			}
			$list_numbers[ $list_id ]++;

			if ( ! preg_match( '/^\d+(?:\.\d+)*\.?\s/u', $text ) ) {
				$text = $list_numbers[ $list_id ] . '. ' . $text;
			}

			if ( $heading instanceof DOMElement ) {
				$target = $heading;
			}
		} else {
			$text = zadzerkalya_policy_node_label( $node );
			if ( '' === $text ) {
				continue;
			}

			if ( $is_first_heading && 'h2' === $tag && ! preg_match( '/^\d+\./u', $text ) ) {
				$is_first_heading = false;
				$id               = $node->getAttribute( 'id' );
				if ( '' === $id ) {
					$node->setAttribute( 'id', zadzerkalya_policy_heading_id( $text, $seen ) );
				}
				continue;
			}

			$is_first_heading = false;
		}

		$id = $target->getAttribute( 'id' );
		if ( '' === $id ) {
			$id = zadzerkalya_policy_heading_id( $text, $seen );
			$target->setAttribute( 'id', $id );
		} else {
			$seen[ $id ] = 1;
		}

		$result['toc'][] = array(
			'id'   => $id,
			'text' => $text,
		);
	}

	$content = '';
	foreach ( $root->childNodes as $child ) {
		$content .= $dom->saveHTML( $child );
	}

	$result['content'] = $content;

	return $result;
}
