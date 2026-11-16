<?php
/**
 * Метабокси для спеціалістів, послуг і подій.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_add_meta_boxes() {
	add_meta_box(
		'zdk_specialist_details',
		__( 'Дані спеціаліста', 'zadzerkalya' ),
		'zadzerkalya_render_specialist_meta_box',
		'specialist',
		'normal',
		'high'
	);

	add_meta_box(
		'zdk_service_details',
		__( 'Вартість і тривалість', 'zadzerkalya' ),
		'zadzerkalya_render_service_meta_box',
		'service',
		'side',
		'high'
	);

	add_meta_box(
		'zdk_event_details',
		__( 'Дані події', 'zadzerkalya' ),
		'zadzerkalya_render_event_meta_box',
		'event',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'zadzerkalya_add_meta_boxes' );

function zadzerkalya_render_specialist_meta_box( $post ) {
	wp_nonce_field( 'zadzerkalya_save_meta', 'zadzerkalya_meta_nonce' );

	$position  = get_post_meta( $post->ID, '_zdk_position', true );
	$education = get_post_meta( $post->ID, '_zdk_education', true );
	$order     = (int) $post->menu_order;
	$shown     = get_post_meta( $post->ID, '_zdk_show_on_team', true );
	$is_shown  = '0' !== (string) $shown;

	echo '<div class="zdk-metabox">';
	printf(
		'<p><label for="zdk_position"><strong>%1$s</strong></label><br /><input type="text" class="widefat" id="zdk_position" name="zdk_position" value="%2$s" /></p>',
		esc_html__( 'Посада', 'zadzerkalya' ),
		esc_attr( $position )
	);
	printf(
		'<p><label for="zdk_education"><strong>%1$s</strong></label><br /><textarea class="widefat" rows="4" id="zdk_education" name="zdk_education">%2$s</textarea></p>',
		esc_html__( 'Освіта', 'zadzerkalya' ),
		esc_textarea( $education )
	);
	printf(
		'<p><label for="zdk_order"><strong>%1$s</strong></label><br /><input type="number" class="small-text" min="0" step="1" id="zdk_order" name="zdk_order" value="%2$d" /><br /><span class="description">%3$s</span></p>',
		esc_html__( 'Порядок на сторінці спеціалістів', 'zadzerkalya' ),
		$order,
		esc_html__( 'Менше число — вище в списку. Однаковий порядок сортується за іменем.', 'zadzerkalya' )
	);
	printf(
		'<p><label><input type="checkbox" name="zdk_show_on_team" value="1" %1$s /> %2$s</label></p>',
		checked( $is_shown, true, false ),
		esc_html__( 'Показувати на сторінці спеціалістів', 'zadzerkalya' )
	);
	echo '</div>';
}

function zadzerkalya_render_service_meta_box( $post ) {
	wp_nonce_field( 'zadzerkalya_save_meta', 'zadzerkalya_meta_nonce' );

	$price    = get_post_meta( $post->ID, '_zdk_price', true );
	$duration = get_post_meta( $post->ID, '_zdk_duration', true );
	$from     = get_post_meta( $post->ID, '_zdk_price_from', true );
	$note     = get_post_meta( $post->ID, '_zdk_price_note', true );
	$order    = (int) $post->menu_order;

	printf(
		'<p><label for="zdk_price"><strong>%s</strong></label><br /><input type="text" class="widefat" id="zdk_price" name="zdk_price" value="%s" placeholder="1500 грн" /></p>',
		esc_html__( 'Ціна', 'zadzerkalya' ),
		esc_attr( $price )
	);
	printf(
		'<p><label><input type="checkbox" name="zdk_price_from" value="1" %s /> %s</label></p>',
		checked( $from, '1', false ),
		esc_html__( 'Ціна «від»', 'zadzerkalya' )
	);
	printf(
		'<p><label for="zdk_duration"><strong>%s</strong></label><br /><input type="text" class="widefat" id="zdk_duration" name="zdk_duration" value="%s" placeholder="50 хв" /></p>',
		esc_html__( 'Тривалість', 'zadzerkalya' ),
		esc_attr( $duration )
	);
	printf(
		'<p><label for="zdk_price_note"><strong>%s</strong></label><br /><input type="text" class="widefat" id="zdk_price_note" name="zdk_price_note" value="%s" /></p>',
		esc_html__( 'Примітка до ціни', 'zadzerkalya' ),
		esc_attr( $note )
	);
	printf(
		'<p><label for="zdk_order"><strong>%1$s</strong></label><br /><input type="number" class="small-text" min="0" step="1" id="zdk_order" name="zdk_order" value="%2$d" /><br /><span class="description">%3$s</span></p>',
		esc_html__( 'Порядок на сторінці послуг', 'zadzerkalya' ),
		$order,
		esc_html__( 'Менше число — вище в списку. Однаковий порядок сортується за датою: новіші вище.', 'zadzerkalya' )
	);
}

function zadzerkalya_render_event_meta_box( $post ) {
	wp_nonce_field( 'zadzerkalya_save_meta', 'zadzerkalya_meta_nonce' );

	$date  = get_post_meta( $post->ID, '_zdk_event_date', true );
	$time  = get_post_meta( $post->ID, '_zdk_event_time', true );
	$place = get_post_meta( $post->ID, '_zdk_event_place', true );

	printf(
		'<p><label for="zdk_event_date"><strong>%s</strong></label><br /><input type="date" class="widefat" id="zdk_event_date" name="zdk_event_date" value="%s" /></p>',
		esc_html__( 'Дата події', 'zadzerkalya' ),
		esc_attr( $date )
	);
	printf(
		'<p><label for="zdk_event_time"><strong>%s</strong></label><br /><input type="text" class="widefat" id="zdk_event_time" name="zdk_event_time" value="%s" placeholder="18:00" /></p>',
		esc_html__( 'Час', 'zadzerkalya' ),
		esc_attr( $time )
	);
	printf(
		'<p><label for="zdk_event_place"><strong>%s</strong></label><br /><input type="text" class="widefat" id="zdk_event_place" name="zdk_event_place" value="%s" /></p>',
		esc_html__( 'Місце', 'zadzerkalya' ),
		esc_attr( $place )
	);
}

function zadzerkalya_save_meta_boxes( $post_id ) {
	if ( ! isset( $_POST['zadzerkalya_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zadzerkalya_meta_nonce'] ) ), 'zadzerkalya_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$type = get_post_type( $post_id );

	$map = array();

	if ( 'specialist' === $type ) {
		if ( isset( $_POST['zdk_position'] ) ) {
			update_post_meta( $post_id, '_zdk_position', sanitize_text_field( wp_unslash( $_POST['zdk_position'] ) ) );
		}
		if ( isset( $_POST['zdk_education'] ) ) {
			update_post_meta( $post_id, '_zdk_education', sanitize_textarea_field( wp_unslash( $_POST['zdk_education'] ) ) );
		}
		if ( isset( $_POST['zdk_order'] ) ) {
			$order = max( 0, (int) $_POST['zdk_order'] );
			if ( (int) get_post_field( 'menu_order', $post_id ) !== $order ) {
				remove_action( 'save_post', 'zadzerkalya_save_meta_boxes' );
				wp_update_post(
					array(
						'ID'         => $post_id,
						'menu_order' => $order,
					)
				);
				add_action( 'save_post', 'zadzerkalya_save_meta_boxes' );
			}
		}
		update_post_meta( $post_id, '_zdk_show_on_team', isset( $_POST['zdk_show_on_team'] ) ? '1' : '0' );
		return;
	} elseif ( 'service' === $type ) {
		$map  = array( 'price', 'duration', 'price_note' );
		$from = isset( $_POST['zdk_price_from'] ) ? '1' : '';
		update_post_meta( $post_id, '_zdk_price_from', $from );

		if ( isset( $_POST['zdk_order'] ) ) {
			$order = max( 0, (int) $_POST['zdk_order'] );
			if ( (int) get_post_field( 'menu_order', $post_id ) !== $order ) {
				remove_action( 'save_post', 'zadzerkalya_save_meta_boxes' );
				wp_update_post(
					array(
						'ID'         => $post_id,
						'menu_order' => $order,
					)
				);
				add_action( 'save_post', 'zadzerkalya_save_meta_boxes' );
			}
		}
	} elseif ( 'event' === $type ) {
		$map = array( 'event_date', 'event_time', 'event_place' );
	}

	foreach ( $map as $key ) {
		$field = 'zdk_' . $key;
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, '_zdk_' . $key, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post', 'zadzerkalya_save_meta_boxes' );

/**
 * Числовий ID запису з формату ACF.
 *
 * @param mixed $post_id ID запису.
 * @return int
 */
function zadzerkalya_service_cta_post_id( $post_id ) {
	if ( is_numeric( $post_id ) ) {
		return (int) $post_id;
	}

	if ( is_string( $post_id ) && preg_match( '/(\d+)$/', $post_id, $matches ) ) {
		return (int) $matches[1];
	}

	return 0;
}

/**
 * Рядки зі старої структури «слайд → рядки». Старі ключі не змінює.
 *
 * @param mixed $post_id ID послуги.
 * @return array<int, array<string, mixed>>
 */
function zadzerkalya_service_cta_legacy_rows( $post_id ) {
	$post_id = zadzerkalya_service_cta_post_id( $post_id );
	if ( $post_id < 1 || ! metadata_exists( 'post', $post_id, 'service_cta_slides_0_rows' ) ) {
		return array();
	}

	$items = array();
	for ( $slide = 0; $slide < 50; $slide++ ) {
		if ( ! metadata_exists( 'post', $post_id, "service_cta_slides_{$slide}_rows" ) ) {
			break;
		}

		$row_count = (int) get_post_meta( $post_id, "service_cta_slides_{$slide}_rows", true );
		for ( $row = 0; $row < $row_count; $row++ ) {
			$prefix  = "service_cta_slides_{$slide}_rows_{$row}_";
			$items[] = array(
				'title'            => (string) get_post_meta( $post_id, $prefix . 'title', true ),
				'show_description' => get_post_meta( $post_id, $prefix . 'show_description', true ),
				'description'      => (string) get_post_meta( $post_id, $prefix . 'description', true ),
			);
		}
	}

	return $items;
}

/**
 * Старі пункти, якщо новий список ще не збережений.
 *
 * @param mixed $post_id ID послуги.
 * @return array<int, array<string, mixed>>|null
 */
function zadzerkalya_service_cta_legacy_items( $post_id ) {
	$post_id = zadzerkalya_service_cta_post_id( $post_id );
	if ( $post_id < 1 || metadata_exists( 'post', $post_id, 'service_cta_slides_0_title' ) || metadata_exists( 'post', $post_id, 'service_cta_slides_0_description' ) ) {
		return null;
	}

	$items = zadzerkalya_service_cta_legacy_rows( $post_id );
	return $items ? $items : null;
}

/**
 * Записує старі рядки в плоский список, не стираючи попередні ключі.
 *
 * @param mixed $post_id ID послуги.
 * @return bool
 */
function zadzerkalya_persist_service_cta_items( $post_id ) {
	$post_id = zadzerkalya_service_cta_post_id( $post_id );
	$items   = zadzerkalya_service_cta_legacy_items( $post_id );
	if ( ! $items ) {
		return false;
	}

	update_post_meta( $post_id, 'service_cta_slides', count( $items ) );
	foreach ( $items as $index => $item ) {
		update_post_meta( $post_id, "service_cta_slides_{$index}_title", $item['title'] );
		update_post_meta( $post_id, "_service_cta_slides_{$index}_title", 'field_zadzerkalya_service_cta_row_title' );
		update_post_meta( $post_id, "service_cta_slides_{$index}_show_description", $item['show_description'] );
		update_post_meta( $post_id, "_service_cta_slides_{$index}_show_description", 'field_zadzerkalya_service_cta_row_show_description' );
		update_post_meta( $post_id, "service_cta_slides_{$index}_description", $item['description'] );
		update_post_meta( $post_id, "_service_cta_slides_{$index}_description", 'field_zadzerkalya_service_cta_row_description' );
	}

	return true;
}

/**
 * Після синхронізації поля показує і зберігає старі рядки як окремі пункти.
 *
 * @param mixed $value   Значення поля.
 * @param mixed $post_id ID запису.
 * @param array $field   Налаштування поля.
 * @return mixed
 */
function zadzerkalya_load_service_cta_items( $value, $post_id, $field ) {
	$names = array_column( (array) ( $field['sub_fields'] ?? array() ), 'name' );
	if ( in_array( 'rows', $names, true ) ) {
		return $value;
	}

	$legacy = zadzerkalya_service_cta_legacy_items( $post_id );
	if ( null === $legacy ) {
		return $value;
	}

	zadzerkalya_persist_service_cta_items( $post_id );
	return $legacy;
}
add_filter( 'acf/load_value/name=service_cta_slides', 'zadzerkalya_load_service_cta_items', 10, 3 );
