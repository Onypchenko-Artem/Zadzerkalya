<?php
/**
 * Форма зворотного звʼязку на сторінці контактів.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Повертає на сторінку форми. wp_get_referer() порожній, коли форма відправлена на ту саму адресу.
 *
 * @param string $status sent|error|invalid.
 */
function zadzerkalya_contact_redirect( $status, $args = array() ) {
	$target = wp_get_referer();

	if ( ! $target ) {
		$raw    = wp_get_raw_referer();
		$target = $raw ? wp_validate_redirect( $raw, false ) : false;
	}

	if ( ! $target ) {
		$target = home_url( '/' );
	}

	$args['contact'] = $status;
	wp_safe_redirect( add_query_arg( $args, $target ) );
	exit;
}

function zadzerkalya_handle_contact_form() {
	if ( ! isset( $_POST['zadzerkalya_contact_submit'] ) ) {
		return;
	}

	$source       = isset( $_POST['zadzerkalya_form_source'] ) ? sanitize_key( wp_unslash( $_POST['zadzerkalya_form_source'] ) ) : 'contacts';
	$is_event_next = 'event-next' === $source;
	$redirect_args = $is_event_next ? array( 'contact_popup' => '1' ) : array();

	if ( ! isset( $_POST['zadzerkalya_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zadzerkalya_contact_nonce'] ) ), 'zadzerkalya_contact' ) ) {
		zadzerkalya_contact_redirect( 'invalid', $redirect_args );
	}

	if ( ! empty( $_POST['zadzerkalya_website'] ) ) {
		zadzerkalya_contact_redirect( 'sent', $redirect_args );
	}

	$name    = isset( $_POST['zadzerkalya_name'] ) ? sanitize_text_field( wp_unslash( $_POST['zadzerkalya_name'] ) ) : '';
	$email   = isset( $_POST['zadzerkalya_email'] ) ? sanitize_email( wp_unslash( $_POST['zadzerkalya_email'] ) ) : '';
	$phone   = isset( $_POST['zadzerkalya_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['zadzerkalya_phone'] ) ) : '';
	$digits  = preg_replace( '/\D+/', '', $phone );
	if ( ! is_string( $digits ) || ! preg_match( '/^380\d{9}$/', $digits ) ) {
		$phone = '';
	}
	$message = isset( $_POST['zadzerkalya_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['zadzerkalya_message'] ) ) : '';
	$is_hero    = 'hero' === $source;
	$is_service = 'service' === $source;
	$is_consult = $is_hero || $is_service || $is_event_next;
	$is_home    = 'home' === $source;

	if (
		! $name
		|| ( $is_consult && ! $phone )
		|| ( $is_home && ( ! $phone || ! is_email( $email ) || ! $message ) )
		|| ( ! $is_consult && ! $is_home && ( ! is_email( $email ) || ! $message ) )
	) {
		zadzerkalya_contact_redirect( 'error', $redirect_args );
	}

	if ( $is_hero ) {
		$message = __( 'Заявка на первинну консультацію з головного банера.', 'zadzerkalya' );
	} elseif ( $is_service ) {
		$message = __( 'Заявка на первинну консультацію зі сторінки послуги.', 'zadzerkalya' );
	} elseif ( $is_event_next ) {
		$event_title = isset( $_POST['zadzerkalya_event_title'] ) ? sanitize_text_field( wp_unslash( $_POST['zadzerkalya_event_title'] ) ) : '';
		$message     = $event_title
			? sprintf( __( 'Прохання повідомити про наступну подію. Подія: %s', 'zadzerkalya' ), $event_title )
			: __( 'Прохання повідомити про наступну подію.', 'zadzerkalya' );
	}

	$to      = zadzerkalya_theme_mod( 'email', get_option( 'admin_email' ) );
	if ( $is_event_next ) {
		$subject = sprintf( __( 'Наступна подія: %s', 'zadzerkalya' ), $name );
	} elseif ( $is_consult || $is_home ) {
		$subject = sprintf( __( 'Первинна консультація: %s', 'zadzerkalya' ), $name );
	} else {
		$subject = sprintf( __( 'Заявка з сайту від %s', 'zadzerkalya' ), $name );
	}
	$body    = sprintf(
		"Імʼя: %s\nEmail: %s\nТелефон: %s\n\n%s",
		$name,
		$email,
		$phone,
		$message
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
	);
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	$sent = wp_mail( $to, $subject, $body, $headers );
	zadzerkalya_contact_redirect( $sent ? 'sent' : 'error', $redirect_args );
}
add_action( 'template_redirect', 'zadzerkalya_handle_contact_form' );

function zadzerkalya_contact_notice( $context = '' ) {
	$is_popup = ! empty( $_GET['contact_popup'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ( 'popup' === $context ) !== $is_popup ) {
		return;
	}

	if ( empty( $_GET['contact'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$status = sanitize_text_field( wp_unslash( $_GET['contact'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$map    = array(
		'sent'    => array( 'success', __( 'Дякуємо. Ми звʼяжемося з вами найближчим часом.', 'zadzerkalya' ) ),
		'error'   => array( 'error', __( 'Перевірте поля форми і спробуйте ще раз.', 'zadzerkalya' ) ),
		'invalid' => array( 'error', __( 'Сесію форми вичерпано. Оновіть сторінку.', 'zadzerkalya' ) ),
	);

	if ( ! isset( $map[ $status ] ) ) {
		return;
	}

	printf(
		'<div class="form-notice form-notice--%1$s" role="status">%2$s</div>',
		esc_attr( $map[ $status ][0] ),
		esc_html( $map[ $status ][1] )
	);
}
