<?php
/**
 * SVG-іконки (Instagram, Telegram, burger, close).
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_icon( $name, $echo = true ) {
	$icons = array(
		'instagram' => '<svg class="icon icon--instagram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><ellipse cx="19.5" cy="20" rx="18.5" ry="18" stroke="currentColor"/><rect x="12" y="12" width="16" height="16" rx="4.5" stroke="#fff" stroke-width="1.4"/><circle cx="20" cy="20" r="4" stroke="#fff" stroke-width="1.4"/><circle cx="25.6" cy="14.6" r="1.1" fill="#fff"/></svg>',
		'telegram'  => '<svg class="icon icon--telegram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><ellipse cx="20.5" cy="19.5" rx="18.5" ry="18" stroke="currentColor"/><path fill="#fff" d="M12.5 19.8 28 12.8l-3.6 14.2-4.2-3.8-3.5 2.4 0.8-4.6 7.2-6.4-8.9 5.2-4.3 0z"/></svg>',
		'tiktok'    => '<svg class="icon icon--tiktok" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><ellipse cx="20" cy="20" rx="18.5" ry="18" stroke="currentColor"/><path d="M22.2 11.5v11.1a5.1 5.1 0 1 1-4.4-5.05v3.05a2.25 2.25 0 1 0 1.55 2.14V11.5h2.85Zm0 0c.35 2.7 1.95 4.45 4.8 4.8v2.9a8.05 8.05 0 0 1-4.8-1.7" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'burger'    => '<svg class="icon icon--burger" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><ellipse cx="20" cy="20" rx="18.5" ry="18" stroke="currentColor"/><path stroke="currentColor" stroke-linecap="round" d="M13 16h14M13 20h14M13 24h14"/></svg>',
		'close'     => '<svg class="icon icon--close" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><ellipse cx="20" cy="20" rx="18.5" ry="18" stroke="currentColor"/><path stroke="currentColor" stroke-linecap="round" d="M14 14l12 12M26 14 14 26"/></svg>',
	);

	$html = isset( $icons[ $name ] ) ? $icons[ $name ] : '';

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $html;
}

function zadzerkalya_tel_href( $phone ) {
	return 'tel:' . preg_replace( '/[^\d+]/', '', $phone );
}

function zadzerkalya_phones() {
	$phones = array_filter(
		array(
			zadzerkalya_theme_mod( 'phone', '+380 67 608 00 43' ),
			zadzerkalya_theme_mod( 'phone_2', '+380 50 608 00 43' ),
		)
	);

	return array_values( $phones );
}
