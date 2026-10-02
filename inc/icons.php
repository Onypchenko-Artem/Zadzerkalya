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
	$social_badge = '<circle cx="20" cy="20" r="20" fill="#88BBF2"/><path d="M2.9221 11.4193C1.92896 15.5413 1.14821 18.6693 0.719609 21.0625C0.16673 24.1498 0.758908 26.7885 1.37973 28.2253C2.41298 30.6166 6.51091 32.4854 10.6299 34.0148C12.0908 34.5572 14.528 34.5272 16.8233 34.4708C21.2901 34.3609 24.8567 33.1297 27.9691 31.3954C32.2228 29.0252 34.644 25.2846 35.8562 22.9885C38.0041 18.92 37.6303 14.5063 37.0482 11.5224C36.6012 9.23121 34.4825 7.25335 32.6979 5.39119C30.9973 3.61684 28.8785 2.51665 26.8198 1.57157C24.7225 0.608749 21.7843 0.493661 18.8244 0.500255C16.7412 0.504896 14.2218 1.66904 10.9153 3.20869C5.48858 5.73566 4.21053 8.00253 3.56711 9.15828C3.41023 9.52586 3.23928 9.98425 3.06192 10.5288C2.88456 11.0734 2.70598 11.6904 2.52199 12.326" stroke="currentColor" stroke-linecap="round" transform="translate(1 2.5)"/>';
	$instagram     = esc_url( ZADZERKALYA_URI . '/assets/images/inst.svg' );
	$facebook      = esc_url( ZADZERKALYA_URI . '/assets/images/facebook.svg' );

	$icons = array(
		'instagram' => '<svg class="icon icon--instagram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<image href="' . $instagram . '" x="8" y="8" width="24" height="24"/></svg>',
		'instagram-header' => '<svg class="icon icon--instagram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<svg x="8" y="8" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.46705 0H17.5124C21.0847 0 24 2.92294 24 6.48361V17.5164C24 21.0771 21.0847 24 17.5124 24H6.46705C2.91533 24 0 21.0771 0 17.5164V6.48361C0 2.92294 2.91533 0 6.46705 0ZM18.4363 4.17791C19.1753 4.17791 19.8118 4.8164 19.8118 5.55738C19.8118 6.31887 19.1753 6.9361 18.4363 6.9361C17.6356 6.9361 17.0607 6.31887 17.0607 5.55738C17.0607 4.8164 17.6356 4.17791 18.4363 4.17791ZM11.9692 5.45413H12.0308C15.5825 5.45413 18.5595 8.43933 18.5595 12C18.5595 15.6229 15.5825 18.5451 12.0308 18.5451H11.9692C8.41742 18.5451 5.48165 15.6229 5.48165 12C5.48165 8.43933 8.41742 5.45413 11.9692 5.45413ZM11.9692 7.69834H12.0308C14.3713 7.69834 16.3217 9.6328 16.3217 12C16.3217 14.4082 14.3713 16.3639 12.0308 16.3639H11.9692C9.60819 16.3639 7.65782 14.4082 7.65782 12H7.67835C7.67835 9.6328 9.60819 7.69834 11.9692 7.69834ZM6.52869 2.0582H17.4713C19.935 2.0582 21.9264 4.07541 21.9264 6.54511V17.4549C21.9264 19.9246 19.935 21.9418 17.4713 21.9418H6.52869C4.06506 21.9418 2.05305 19.9246 2.05305 17.4549V6.54511C2.05305 4.07541 4.06506 2.0582 6.52869 2.0582Z" fill="white"/></svg></svg>',
		'facebook'  => '<svg class="icon icon--facebook" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<image href="' . $facebook . '" x="14" y="9.5" width="12" height="21"/></svg>',
		'telegram'  => '<svg class="icon icon--telegram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<path fill="#fff" d="M12.5 19.8 28 12.8l-3.6 14.2-4.2-3.8-3.5 2.4 0.8-4.6 7.2-6.4-8.9 5.2-4.3 0z"/></svg>',
		'telegram-header' => '<svg class="icon icon--telegram" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<svg x="8.5" y="10.5" width="23" height="19" viewBox="0 0 23 19" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.39354 8.34474C6.61559 6.17782 12.936 3.08232 18.4073 0.977812C23.4634 -0.858782 23.3809 -0.363298 22.6143 4.67154C22.0333 8.69561 21.35 12.7197 20.6865 16.743C20.4381 18.6625 19.4223 19.6733 17.3089 18.4974L10.74 13.8956C9.82817 13.1322 10.0766 12.5545 10.8226 11.7911L16.8946 6.09562C18.4898 4.50639 17.7438 3.90815 15.8788 5.16705L7.54811 10.7796C6.36719 11.6259 5.10294 11.6259 3.75615 11.2127L0.895951 10.2019C-0.948431 9.52068 0.481669 8.77782 1.39354 8.34474Z" fill="white"/></svg></svg>',
		'tiktok'    => '<svg class="icon icon--tiktok" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $social_badge . '<path d="M22.2 11.5v11.1a5.1 5.1 0 1 1-4.4-5.05v3.05a2.25 2.25 0 1 0 1.55 2.14V11.5h2.85Zm0 0c.35 2.7 1.95 4.45 4.8 4.8v2.9a8.05 8.05 0 0 1-4.8-1.7" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
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
