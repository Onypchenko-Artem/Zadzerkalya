<?php
/**
 * Створення сторінок і меню після активації теми.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_create_page( $slug, $title, $template = '' ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		if ( $template ) {
			update_post_meta( $existing->ID, '_wp_page_template', $template );
		}
		return (int) $existing->ID;
	}

	$page_id = wp_insert_post(
		array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_content'=> '',
		),
		true
	);

	if ( is_wp_error( $page_id ) ) {
		return 0;
	}

	if ( $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return (int) $page_id;
}

function zadzerkalya_setup_defaults() {
	if ( get_option( 'zadzerkalya_defaults_installed' ) ) {
		return;
	}

	$home_id    = zadzerkalya_create_page( 'home', __( 'Головна', 'zadzerkalya' ), 'page-templates/home.php' );
	$about_id   = zadzerkalya_create_page( 'about', __( 'Про нас', 'zadzerkalya' ), 'page-templates/about.php' );
	$prices_id  = zadzerkalya_create_page( 'prices', __( 'Вартість послуг', 'zadzerkalya' ), 'page-templates/prices.php' );
	$contacts_id = zadzerkalya_create_page( 'contacts', __( 'Контакти', 'zadzerkalya' ), 'page-templates/contacts.php' );
	$blog_id    = zadzerkalya_create_page( 'blog', __( 'Блог', 'zadzerkalya' ) );

	if ( $home_id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	if ( $blog_id ) {
		update_option( 'page_for_posts', $blog_id );
	}

	$menu_name = __( 'Головне меню', 'zadzerkalya' );
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );

		$items = array(
			array( 'title' => __( 'Головна', 'zadzerkalya' ), 'url' => home_url( '/' ) ),
			array( 'title' => __( 'Про нас', 'zadzerkalya' ), 'object' => $about_id, 'type' => 'post_type' ),
			array( 'title' => __( 'Послуги', 'zadzerkalya' ), 'url' => get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ) ),
			array( 'title' => __( 'Вартість', 'zadzerkalya' ), 'object' => $prices_id, 'type' => 'post_type' ),
			array( 'title' => __( 'Спеціалісти', 'zadzerkalya' ), 'url' => zadzerkalya_get_specialists_url() ),
			array( 'title' => __( 'Події', 'zadzerkalya' ), 'url' => get_post_type_archive_link( 'event' ) ?: home_url( '/events/' ) ),
			array( 'title' => __( 'Блог', 'zadzerkalya' ), 'object' => $blog_id, 'type' => 'post_type' ),
			array( 'title' => __( 'Контакти', 'zadzerkalya' ), 'object' => $contacts_id, 'type' => 'post_type' ),
		);

		foreach ( $items as $item ) {
			$args = array(
				'menu-item-title'  => $item['title'],
				'menu-item-status' => 'publish',
			);

			if ( ! empty( $item['object'] ) ) {
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $item['object'];
				$args['menu-item-type']      = 'post_type';
			} else {
				$args['menu-item-type'] = 'custom';
				$args['menu-item-url']  = $item['url'] ? $item['url'] : home_url( '/' );
			}

			wp_update_nav_menu_item( $menu_id, 0, $args );
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		$locations['footer']  = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	if ( ! get_theme_mod( 'zadzerkalya_hero_title' ) ) {
		set_theme_mod( 'zadzerkalya_hero_kicker', __( 'Центр психології та логопедії «Задзеркалля»', 'zadzerkalya' ) );
		set_theme_mod( 'zadzerkalya_hero_title', __( 'Ми віримо в кожну дитину!', 'zadzerkalya' ) );
		set_theme_mod( 'zadzerkalya_hero_text', __( '«Задзеркалля» — це місце, де змінюються долі, де страх поступається надії, а неможливе стає можливим через любов, прийняття та віру у кожну дитину.', 'zadzerkalya' ) );
		set_theme_mod( 'zadzerkalya_hero_btn', __( 'Забронювати первинну консультацію', 'zadzerkalya' ) );
		set_theme_mod( 'zadzerkalya_hero_btn_url', $contacts_id ? get_permalink( $contacts_id ) : home_url( '/contacts/' ) );
	}

	update_option( 'zadzerkalya_defaults_installed', 1 );
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'zadzerkalya_setup_defaults' );

function zadzerkalya_header_nav_items() {
	$prices    = get_page_by_path( 'prices' );
	$about     = get_page_by_path( 'about' );
	$blog      = (int) get_option( 'page_for_posts' );
	$contacts  = get_page_by_path( 'contacts' );
	$law       = get_page_by_path( '309-postanova' );

	return array(
		array(
			'title' => __( 'Послуги', 'zadzerkalya' ),
			'url'   => get_post_type_archive_link( 'service' ) ?: home_url( '/services/' ),
		),
		array(
			'title'  => __( '309 постанова', 'zadzerkalya' ),
			'object' => $law ? (int) $law->ID : 0,
		),
		array(
			'title'  => __( 'Про нас', 'zadzerkalya' ),
			'object' => $about ? (int) $about->ID : 0,
		),
		array(
			'title' => __( 'Наші спеціалісти', 'zadzerkalya' ),
			'url'   => zadzerkalya_get_specialists_url(),
		),
		array(
			'title' => __( 'Події', 'zadzerkalya' ),
			'url'   => get_post_type_archive_link( 'event' ) ?: home_url( '/events/' ),
		),
		array(
			'title'  => __( 'Блог', 'zadzerkalya' ),
			'object' => $blog,
		),
		array(
			'title'  => __( 'Ціни', 'zadzerkalya' ),
			'object' => $prices ? (int) $prices->ID : 0,
		),
		array(
			'title'  => __( 'Контакти', 'zadzerkalya' ),
			'object' => $contacts ? (int) $contacts->ID : 0,
		),
	);
}

function zadzerkalya_header_menu_fallback() {
	echo '<ul class="menu">';
	foreach ( zadzerkalya_header_nav_items() as $item ) {
		$url = ! empty( $item['object'] ) ? get_permalink( $item['object'] ) : ( $item['url'] ?? home_url( '/' ) );
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $item['title'] ) );
	}
	echo '</ul>';
}

function zadzerkalya_apply_header_design() {
	if ( get_option( 'zadzerkalya_header_v2' ) ) {
		return;
	}

	zadzerkalya_create_page( '309-postanova', __( '309 постанова', 'zadzerkalya' ) );

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menu_id   = isset( $locations['primary'] ) ? (int) $locations['primary'] : 0;

	if ( ! $menu_id ) {
		$menu      = wp_get_nav_menu_object( __( 'Головне меню', 'zadzerkalya' ) );
		$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( __( 'Головне меню', 'zadzerkalya' ) );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	$existing = wp_get_nav_menu_items( $menu_id );
	if ( $existing ) {
		foreach ( $existing as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	foreach ( zadzerkalya_header_nav_items() as $item ) {
		$args = array(
			'menu-item-title'  => $item['title'],
			'menu-item-status' => 'publish',
		);

		if ( ! empty( $item['object'] ) ) {
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = (int) $item['object'];
			$args['menu-item-type']      = 'post_type';
		} elseif ( ! empty( $item['url'] ) ) {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = $item['url'];
		} else {
			continue;
		}

		wp_update_nav_menu_item( $menu_id, 0, $args );
	}

	update_option( 'zadzerkalya_header_v2', 1 );
}
add_action( 'init', 'zadzerkalya_apply_header_design', 20 );

function zadzerkalya_flush_rewrites_once() {
	if ( get_option( 'zadzerkalya_rewrite_flushed' ) ) {
		return;
	}

	flush_rewrite_rules();
	update_option( 'zadzerkalya_rewrite_flushed', 1 );
}
add_action( 'init', 'zadzerkalya_flush_rewrites_once', 99 );

function zadzerkalya_apply_hero_design_defaults() {
	if ( get_option( 'zadzerkalya_hero_design_v1' ) ) {
		return;
	}

	$defaults = array(
		'hero_kicker' => array(
			'old' => __( 'Психологічний простір', 'zadzerkalya' ),
			'new' => __( 'Центр психології та логопедії «Задзеркалля»', 'zadzerkalya' ),
		),
		'hero_title' => array(
			'old' => __( 'Задзеркалля', 'zadzerkalya' ),
			'new' => __( 'Ми віримо в кожну дитину!', 'zadzerkalya' ),
		),
		'hero_text' => array(
			'old' => __( 'Місце, де можна зупинитися, побачити себе і зробити крок до змін.', 'zadzerkalya' ),
			'new' => __( '«Задзеркалля» — це місце, де змінюються долі, де страх поступається надії, а неможливе стає можливим через любов, прийняття та віру у кожну дитину.', 'zadzerkalya' ),
		),
		'hero_btn' => array(
			'old' => __( 'Записатися', 'zadzerkalya' ),
			'new' => __( 'Забронювати первинну консультацію', 'zadzerkalya' ),
		),
	);

	foreach ( $defaults as $key => $copy ) {
		$mod = 'zadzerkalya_' . $key;
		if ( $copy['old'] === get_theme_mod( $mod ) ) {
			set_theme_mod( $mod, $copy['new'] );
		}
	}

	update_option( 'zadzerkalya_hero_design_v1', 1 );
}
add_action( 'init', 'zadzerkalya_apply_hero_design_defaults', 25 );

function zadzerkalya_apply_home_page_template() {
	if ( get_option( 'zadzerkalya_home_template_v1' ) ) {
		return;
	}

	$home_id = (int) get_option( 'page_on_front' );
	if ( ! $home_id ) {
		$home = get_page_by_path( 'home' );
		$home_id = $home ? (int) $home->ID : zadzerkalya_create_page( 'home', __( 'Головна', 'zadzerkalya' ), 'page-templates/home.php' );
		if ( $home_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_id );
		}
	}

	if ( $home_id ) {
		update_post_meta( $home_id, '_wp_page_template', 'page-templates/home.php' );
	}

	update_option( 'zadzerkalya_home_template_v1', 1 );
}
add_action( 'init', 'zadzerkalya_apply_home_page_template', 20 );
