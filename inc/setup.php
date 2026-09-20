<?php
/**
 * Підтримка теми, меню, розміри зображень.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_setup() {
	load_theme_textdomain( 'zadzerkalya', ZADZERKALYA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Головне меню', 'zadzerkalya' ),
			'footer'  => __( 'Меню в підвалі', 'zadzerkalya' ),
		)
	);

	add_image_size( 'zadzerkalya-card', 720, 480, true );
	add_image_size( 'zadzerkalya-portrait', 640, 800, true );
	add_image_size( 'zadzerkalya-hero', 1600, 900, true );
}
add_action( 'after_setup_theme', 'zadzerkalya_setup' );

function zadzerkalya_content_width() {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'zadzerkalya_content_width', 0 );

function zadzerkalya_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Підвал: колонка 1', 'zadzerkalya' ),
			'id'            => 'footer-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Підвал: колонка 2', 'zadzerkalya' ),
			'id'            => 'footer-2',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Підвал: колонка 3', 'zadzerkalya' ),
			'id'            => 'footer-3',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'zadzerkalya_widgets_init' );

function zadzerkalya_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'zadzerkalya_excerpt_more' );

function zadzerkalya_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'zadzerkalya_excerpt_length' );

// Використовуємо класичні редактори замість Gutenberg.
add_filter( 'use_block_editor_for_post', '__return_false', 100 );
add_filter( 'use_widgets_block_editor', '__return_false' );
