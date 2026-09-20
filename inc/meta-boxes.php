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

	$fields = array(
		'position'   => __( 'Посада', 'zadzerkalya' ),
		'experience' => __( 'Досвід', 'zadzerkalya' ),
		'education'  => __( 'Освіта', 'zadzerkalya' ),
		'phone'      => __( 'Телефон', 'zadzerkalya' ),
		'email'      => __( 'Email', 'zadzerkalya' ),
	);

	echo '<div class="zdk-metabox">';
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, '_zdk_' . $key, true );
		printf(
			'<p><label for="zdk_%1$s"><strong>%2$s</strong></label><br /><input type="text" class="widefat" id="zdk_%1$s" name="zdk_%1$s" value="%3$s" /></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
	echo '</div>';
}

function zadzerkalya_render_service_meta_box( $post ) {
	wp_nonce_field( 'zadzerkalya_save_meta', 'zadzerkalya_meta_nonce' );

	$price    = get_post_meta( $post->ID, '_zdk_price', true );
	$duration = get_post_meta( $post->ID, '_zdk_duration', true );
	$from     = get_post_meta( $post->ID, '_zdk_price_from', true );
	$note     = get_post_meta( $post->ID, '_zdk_price_note', true );

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
		$map = array( 'position', 'experience', 'education', 'phone', 'email' );
	} elseif ( 'service' === $type ) {
		$map = array( 'price', 'duration', 'price_note' );
		$from = isset( $_POST['zdk_price_from'] ) ? '1' : '';
		update_post_meta( $post_id, '_zdk_price_from', $from );
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
