<?php
/**
 * Тема Задзеркалля.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZADZERKALYA_VERSION', '1.3.1' );
define( 'ZADZERKALYA_DIR', get_template_directory() );
define( 'ZADZERKALYA_URI', get_template_directory_uri() );

require_once ZADZERKALYA_DIR . '/inc/setup.php';
require_once ZADZERKALYA_DIR . '/inc/enqueue.php';
require_once ZADZERKALYA_DIR . '/inc/cpt.php';
require_once ZADZERKALYA_DIR . '/inc/meta-boxes.php';
require_once ZADZERKALYA_DIR . '/inc/customizer.php';
require_once ZADZERKALYA_DIR . '/inc/template-tags.php';
require_once ZADZERKALYA_DIR . '/inc/icons.php';
require_once ZADZERKALYA_DIR . '/inc/contact-form.php';
require_once ZADZERKALYA_DIR . '/inc/setup-defaults.php';
