<?php
/**
 * Стилі та скрипти.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_enqueue_assets() {
	wp_enqueue_style(
		'zadzerkalya-fonts',
		'https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Inter+Tight:wght@300;400;500&family=M+PLUS+Rounded+1c:wght@500&display=swap',
		array(),
		null
	);

	$styles = array(
		'zadzerkalya-colors'      => array( 'file' => '/assets/css/colors.css', 'deps' => array() ),
		'zadzerkalya-fluid'       => array( 'file' => '/assets/css/fluid.css', 'deps' => array() ),
		'zadzerkalya-typography'  => array( 'file' => '/assets/css/typography.css', 'deps' => array( 'zadzerkalya-fonts', 'zadzerkalya-fluid' ) ),
		'zadzerkalya-base'        => array( 'file' => '/assets/css/base.css', 'deps' => array( 'zadzerkalya-colors', 'zadzerkalya-fluid', 'zadzerkalya-typography' ) ),
		'zadzerkalya-header'      => array( 'file' => '/assets/css/components/header.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-button'      => array( 'file' => '/assets/css/components/button.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-hero'        => array( 'file' => '/assets/css/components/hero.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-cards'       => array( 'file' => '/assets/css/components/cards.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-cta'         => array( 'file' => '/assets/css/components/cta.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-service-when'=> array( 'file' => '/assets/css/components/service-when.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-help'        => array( 'file' => '/assets/css/components/help.css', 'deps' => array( 'zadzerkalya-base', 'zadzerkalya-button' ) ),
		'zadzerkalya-services-showcase'=> array( 'file' => '/assets/css/components/services-showcase.css', 'deps' => array( 'zadzerkalya-base', 'zadzerkalya-button' ) ),
		'zadzerkalya-numbers'     => array( 'file' => '/assets/css/components/numbers.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-benefits'    => array( 'file' => '/assets/css/components/benefits.css', 'deps' => array( 'zadzerkalya-base', 'zadzerkalya-button' ) ),
		'zadzerkalya-reviews'     => array( 'file' => '/assets/css/components/reviews.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-faq'         => array( 'file' => '/assets/css/components/faq.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-form-home'   => array( 'file' => '/assets/css/components/form-home.css', 'deps' => array( 'zadzerkalya-base', 'zadzerkalya-button', 'zadzerkalya-forms' ) ),
		'zadzerkalya-footer'      => array( 'file' => '/assets/css/components/footer.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-forms'       => array( 'file' => '/assets/css/components/forms.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-page-content'=> array( 'file' => '/assets/css/pages/content.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-front-page'  => array( 'file' => '/assets/css/pages/front-page.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-page-article'=> array( 'file' => '/assets/css/pages/article.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-page-contacts'=> array( 'file' => '/assets/css/pages/contacts.css', 'deps' => array( 'zadzerkalya-base' ) ),
		'zadzerkalya-page-prices' => array( 'file' => '/assets/css/pages/prices.css', 'deps' => array( 'zadzerkalya-base' ) ),
	);

	foreach ( $styles as $handle => $style ) {
		wp_enqueue_style(
			$handle,
			ZADZERKALYA_URI . $style['file'],
			$style['deps'],
			ZADZERKALYA_VERSION
		);
	}

	wp_enqueue_script(
		'lottie-web',
		ZADZERKALYA_URI . '/assets/js/vendor/lottie.min.js',
		array(),
		'5.12.2',
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_enqueue_script(
		'zadzerkalya-button-anims',
		ZADZERKALYA_URI . '/assets/js/button-animations.js',
		array(),
		ZADZERKALYA_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_enqueue_script(
		'zadzerkalya-main',
		ZADZERKALYA_URI . '/assets/js/main.js',
		array( 'lottie-web', 'zadzerkalya-button-anims' ),
		ZADZERKALYA_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zadzerkalya_enqueue_assets' );

function zadzerkalya_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => 'anonymous',
		);
		$urls[] = 'https://fonts.gstatic.com';
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'zadzerkalya_resource_hints', 10, 2 );
