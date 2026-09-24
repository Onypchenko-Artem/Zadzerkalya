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
	$social_outline = '<path d="M2.9221 11.4193C1.92896 15.5413 1.14821 18.6693 0.719609 21.0625C0.16673 24.1498 0.758908 26.7885 1.37973 28.2253C2.41298 30.6166 6.51091 32.4854 10.6299 34.0148C12.0908 34.5572 14.528 34.5272 16.8233 34.4708C21.2901 34.3609 24.8567 33.1297 27.9691 31.3954C32.2228 29.0252 34.644 25.2846 35.8562 22.9885C38.0041 18.92 37.6303 14.5063 37.0482 11.5224C36.6012 9.23121 34.4825 7.25335 32.6979 5.39119C30.9973 3.61684 28.8785 2.51665 26.8198 1.57157C24.7225 0.608749 21.7843 0.493661 18.8244 0.500255C16.7412 0.504896 14.2218 1.66904 10.9153 3.20869C5.48858 5.73566 4.21053 8.00253 3.56711 9.15828C3.41023 9.52586 3.23928 9.98425 3.06192 10.5288C2.88456 11.0734 2.70598 11.6904 2.52199 12.326" stroke="currentColor" stroke-linecap="round" transform="translate(1 2.5)"/>';

	$icons = array(
		'instagram' => '<svg class="icon icon--instagram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_outline . '<rect x="12" y="12" width="16" height="16" rx="4.5" stroke="#fff" stroke-width="1.4"/><circle cx="20" cy="20" r="4" stroke="#fff" stroke-width="1.4"/><circle cx="25.6" cy="14.6" r="1.1" fill="#fff"/></svg>',
		'telegram'  => '<svg class="icon icon--telegram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_outline . '<path fill="#fff" d="M12.5 19.8 28 12.8l-3.6 14.2-4.2-3.8-3.5 2.4 0.8-4.6 7.2-6.4-8.9 5.2-4.3 0z"/></svg>',
		'tiktok'    => '<svg class="icon icon--tiktok" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_outline . '<path d="M22.2 11.5v11.1a5.1 5.1 0 1 1-4.4-5.05v3.05a2.25 2.25 0 1 0 1.55 2.14V11.5h2.85Zm0 0c.35 2.7 1.95 4.45 4.8 4.8v2.9a8.05 8.05 0 0 1-4.8-1.7" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
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
