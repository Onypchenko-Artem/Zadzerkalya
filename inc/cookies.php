<?php
/**
 * Згода на cookies і першосторонній збір.
 *
 * Необхідна кука `zadzerkalya_consent` зберігає вибір: `all` або `necessary`.
 * Додаткова `zadzerkalya_visitor` (випадковий id і час першої згоди) ставиться
 * лише після «Прийняти всі cookies». Хрестик і «лише необхідні» її не створюють
 * і стирають, якщо вона вже була.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZADZERKALYA_CONSENT_COOKIE', 'zadzerkalya_consent' );
define( 'ZADZERKALYA_VISITOR_COOKIE', 'zadzerkalya_visitor' );

/**
 * Поточний рівень згоди з куки запиту.
 *
 * @return string all|necessary|порожній рядок, якщо вибору ще немає.
 */
function zadzerkalya_cookie_consent() {
	if ( empty( $_COOKIE[ ZADZERKALYA_CONSENT_COOKIE ] ) ) {
		return '';
	}

	$level = sanitize_key( wp_unslash( $_COOKIE[ ZADZERKALYA_CONSENT_COOKIE ] ) );

	return in_array( $level, array( 'all', 'necessary' ), true ) ? $level : '';
}

/**
 * Чи можна ставити необовʼязкові cookies.
 *
 * @return bool
 */
function zadzerkalya_optional_cookies_allowed() {
	return 'all' === zadzerkalya_cookie_consent();
}
