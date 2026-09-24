<?php
/**
 * Типи записів: спеціалісти, послуги, події.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_register_post_types() {
	register_post_type(
		'specialist',
		array(
			'labels'              => array(
				'name'                  => __( 'Спеціалісти', 'zadzerkalya' ),
				'singular_name'         => __( 'Спеціаліст', 'zadzerkalya' ),
				'add_new'               => __( 'Додати', 'zadzerkalya' ),
				'add_new_item'          => __( 'Додати спеціаліста', 'zadzerkalya' ),
				'edit_item'             => __( 'Редагувати спеціаліста', 'zadzerkalya' ),
				'new_item'              => __( 'Новий спеціаліст', 'zadzerkalya' ),
				'view_item'             => __( 'Переглянути', 'zadzerkalya' ),
				'search_items'          => __( 'Шукати спеціалістів', 'zadzerkalya' ),
				'not_found'             => __( 'Спеціалістів не знайдено', 'zadzerkalya' ),
				'all_items'             => __( 'Усі спеціалісти', 'zadzerkalya' ),
				'menu_name'             => __( 'Спеціалісти', 'zadzerkalya' ),
				'featured_image'        => __( 'Фото', 'zadzerkalya' ),
				'set_featured_image'    => __( 'Встановити фото', 'zadzerkalya' ),
				'remove_featured_image' => __( 'Видалити фото', 'zadzerkalya' ),
				'use_featured_image'    => __( 'Використати як фото', 'zadzerkalya' ),
			),
			'public'              => true,
			'has_archive'         => ! zadzerkalya_get_specialists_page_id(),
			'rewrite'             => array(
				'slug'       => 'specialists',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest'        => true,
			'menu_position'       => 5,
		)
	);

	register_post_type(
		'service',
		array(
			'labels'              => array(
				'name'               => __( 'Послуги', 'zadzerkalya' ),
				'singular_name'      => __( 'Послуга', 'zadzerkalya' ),
				'add_new'            => __( 'Додати', 'zadzerkalya' ),
				'add_new_item'       => __( 'Додати послугу', 'zadzerkalya' ),
				'edit_item'          => __( 'Редагувати послугу', 'zadzerkalya' ),
				'new_item'           => __( 'Нова послуга', 'zadzerkalya' ),
				'view_item'          => __( 'Переглянути', 'zadzerkalya' ),
				'search_items'       => __( 'Шукати послуги', 'zadzerkalya' ),
				'not_found'          => __( 'Послуг не знайдено', 'zadzerkalya' ),
				'all_items'          => __( 'Усі послуги', 'zadzerkalya' ),
				'menu_name'          => __( 'Послуги', 'zadzerkalya' ),
			),
			'public'              => true,
			'has_archive'         => true,
			'rewrite'             => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-heart',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'        => true,
			'menu_position'       => 6,
		)
	);

	register_post_type(
		'event',
		array(
			'labels'              => array(
				'name'               => __( 'Події', 'zadzerkalya' ),
				'singular_name'      => __( 'Подія', 'zadzerkalya' ),
				'add_new'            => __( 'Додати', 'zadzerkalya' ),
				'add_new_item'       => __( 'Додати подію', 'zadzerkalya' ),
				'edit_item'          => __( 'Редагувати подію', 'zadzerkalya' ),
				'new_item'           => __( 'Нова подія', 'zadzerkalya' ),
				'view_item'          => __( 'Переглянути', 'zadzerkalya' ),
				'search_items'       => __( 'Шукати події', 'zadzerkalya' ),
				'not_found'          => __( 'Подій не знайдено', 'zadzerkalya' ),
				'all_items'          => __( 'Усі події', 'zadzerkalya' ),
				'menu_name'          => __( 'Події', 'zadzerkalya' ),
			),
			'public'              => true,
			'has_archive'         => true,
			'rewrite'             => array(
				'slug'       => 'events',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'        => true,
			'menu_position'       => 7,
		)
	);
}
add_action( 'init', 'zadzerkalya_register_post_types' );

/**
 * Після створення сторінки «Спеціалісти» архів поступається її адресою.
 */
function zadzerkalya_sync_specialists_rewrites() {
	$route = zadzerkalya_get_specialists_page_id() ? 'page' : 'archive';

	if ( get_option( 'zadzerkalya_specialists_route' ) === $route ) {
		return;
	}

	update_option( 'zadzerkalya_specialists_route', $route );
	flush_rewrite_rules( false );
}
add_action( 'init', 'zadzerkalya_sync_specialists_rewrites', 99 );

/**
 * Назва запису — імʼя спеціаліста.
 */
function zadzerkalya_specialist_title_placeholder( $title, $post ) {
	if ( $post instanceof WP_Post && 'specialist' === $post->post_type ) {
		return __( 'Імʼя', 'zadzerkalya' );
	}

	return $title;
}
add_filter( 'enter_title_here', 'zadzerkalya_specialist_title_placeholder', 10, 2 );

function zadzerkalya_register_taxonomies() {
	register_taxonomy(
		'specialization',
		array( 'specialist' ),
		array(
			'labels'            => array(
				'name'          => __( 'Спеціалізації', 'zadzerkalya' ),
				'singular_name' => __( 'Спеціалізація', 'zadzerkalya' ),
				'search_items'  => __( 'Шукати спеціалізації', 'zadzerkalya' ),
				'all_items'     => __( 'Усі спеціалізації', 'zadzerkalya' ),
				'edit_item'     => __( 'Редагувати спеціалізацію', 'zadzerkalya' ),
				'add_new_item'  => __( 'Додати спеціалізацію', 'zadzerkalya' ),
				'menu_name'     => __( 'Спеціалізації', 'zadzerkalya' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'specialization',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'service_type',
		array( 'service' ),
		array(
			'labels'            => array(
				'name'          => __( 'Типи послуг', 'zadzerkalya' ),
				'singular_name' => __( 'Тип послуги', 'zadzerkalya' ),
				'search_items'  => __( 'Шукати типи', 'zadzerkalya' ),
				'all_items'     => __( 'Усі типи', 'zadzerkalya' ),
				'edit_item'     => __( 'Редагувати тип', 'zadzerkalya' ),
				'add_new_item'  => __( 'Додати тип послуги', 'zadzerkalya' ),
				'menu_name'     => __( 'Типи послуг', 'zadzerkalya' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'service-type',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'zadzerkalya_register_taxonomies' );

/**
 * Архів подій сортуємо за датою події.
 */
function zadzerkalya_event_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'event' ) ) {
		$query->set( 'meta_key', '_zdk_event_date' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
		$query->set( 'posts_per_page', 12 );
	}

	if ( $query->is_post_type_archive( 'specialist' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			)
		);
		$query->set( 'posts_per_page', -1 );
	}

	if ( $query->is_post_type_archive( 'service' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			)
		);
		$query->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'zadzerkalya_event_archive_query' );
