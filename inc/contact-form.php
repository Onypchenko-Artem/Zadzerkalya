<?php
/**
 * Форма зворотного звʼязку на сторінці контактів.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_handle_contact_form() {
	if ( ! isset( $_POST['zadzerkalya_contact_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['zadzerkalya_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zadzerkalya_contact_nonce'] ) ), 'zadzerkalya_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalid', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	if ( ! empty( $_POST['zadzerkalya_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	$name    = isset( $_POST['zadzerkalya_name'] ) ? sanitize_text_field( wp_unslash( $_POST['zadzerkalya_name'] ) ) : '';
	$email   = isset( $_POST['zadzerkalya_email'] ) ? sanitize_email( wp_unslash( $_POST['zadzerkalya_email'] ) ) : '';
	$phone   = isset( $_POST['zadzerkalya_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['zadzerkalya_phone'] ) ) : '';
	$message = isset( $_POST['zadzerkalya_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['zadzerkalya_message'] ) ) : '';
	$source  = isset( $_POST['zadzerkalya_form_source'] ) ? sanitize_key( wp_unslash( $_POST['zadzerkalya_form_source'] ) ) : 'contacts';
	$is_hero = 'hero' === $source;
	$is_home = 'home' === $source;

	if (
		! $name
		|| ( $is_hero && ! $phone )
		|| ( $is_home && ( ! $phone || ! is_email( $email ) || ! $message ) )
		|| ( ! $is_hero && ! $is_home && ( ! is_email( $email ) || ! $message ) )
	) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
		exit;
	}

	if ( $is_hero ) {
		$message = __( 'Заявка на первинну консультацію з головного банера.', 'zadzerkalya' );
	}

	$to      = zadzerkalya_theme_mod( 'email', get_option( 'admin_email' ) );
	$subject = $is_hero || $is_home
		? sprintf( __( 'Первинна консультація: %s', 'zadzerkalya' ), $name )
		: sprintf( __( 'Заявка з сайту від %s', 'zadzerkalya' ), $name );
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
	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', wp_get_referer() ? wp_get_referer() : home_url( '/' ) ) );
	exit;
}
add_action( 'template_redirect', 'zadzerkalya_handle_contact_form' );

function zadzerkalya_contact_notice() {
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
