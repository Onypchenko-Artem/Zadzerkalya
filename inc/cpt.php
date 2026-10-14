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
			'show_in_nav_menus'   => true,
			'has_archive'         => ! zadzerkalya_get_specialists_page_id(),
			'rewrite'             => array(
				'slug'       => 'specialists',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-groups',
			'supports'            => array( 'title', 'thumbnail' ),
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
			'show_in_nav_menus'   => true,
			'has_archive'         => ! zadzerkalya_get_services_page_id(),
			'rewrite'             => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'menu_icon'           => 'dashicons-heart',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
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
			'show_in_nav_menus'   => true,
			'has_archive'         => ! zadzerkalya_get_events_page_id(),
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
 * У «Зовнішній вигляд → Меню» WordPress ховає всі типи записів, крім сторінок і записів.
 */
function zadzerkalya_show_post_types_in_nav_menus( $hidden, $screen ) {
	if ( ! $screen instanceof WP_Screen || 'nav-menus' !== $screen->id ) {
		return $hidden;
	}

	$visible = array(
		'add-post-type-service',
		'add-post-type-specialist',
		'add-post-type-event',
	);

	return array_values( array_diff( (array) $hidden, $visible ) );
}
add_filter( 'hidden_meta_boxes', 'zadzerkalya_show_post_types_in_nav_menus', 10, 2 );

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
 * Після створення сторінки «Події» архів поступається її адресою.
 */
function zadzerkalya_sync_events_rewrites() {
	$route = zadzerkalya_get_events_page_id() ? 'page' : 'archive';

	if ( get_option( 'zadzerkalya_events_route' ) === $route ) {
		return;
	}

	update_option( 'zadzerkalya_events_route', $route );
	flush_rewrite_rules( false );
}
add_action( 'init', 'zadzerkalya_sync_events_rewrites', 99 );

/**
 * Після створення сторінки «Послуги» архів поступається її адресою.
 */
function zadzerkalya_sync_services_rewrites() {
	$route = zadzerkalya_get_services_page_id() ? 'page' : 'archive';

	if ( get_option( 'zadzerkalya_services_route' ) === $route ) {
		return;
	}

	update_option( 'zadzerkalya_services_route', $route );
	flush_rewrite_rules( false );
}
add_action( 'init', 'zadzerkalya_sync_services_rewrites', 99 );

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

/**
 * Окрема сторінка спеціаліста недоступна: пряме посилання веде на список.
 */
function zadzerkalya_disable_specialist_single() {
	if ( ! is_singular( 'specialist' ) ) {
		return;
	}

	wp_safe_redirect( zadzerkalya_get_specialists_url(), 301 );
	exit;
}
add_action( 'template_redirect', 'zadzerkalya_disable_specialist_single' );

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

	register_taxonomy(
		'event_topic',
		array( 'event' ),
		array(
			'labels'            => array(
				'name'          => __( 'Теми подій', 'zadzerkalya' ),
				'singular_name' => __( 'Тема події', 'zadzerkalya' ),
				'search_items'  => __( 'Шукати теми', 'zadzerkalya' ),
				'all_items'     => __( 'Усі теми', 'zadzerkalya' ),
				'edit_item'     => __( 'Редагувати тему', 'zadzerkalya' ),
				'add_new_item'  => __( 'Додати тему', 'zadzerkalya' ),
				'menu_name'     => __( 'Теми', 'zadzerkalya' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array(
				'slug'       => 'event-topic',
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
				'date'       => 'DESC',
			)
		);
		$query->set( 'posts_per_page', -1 );
	}
}

/**
 * Стрічка блогу показує 3 картки. Головний запит має збігатися з нею,
 * інакше /blog/page/2/ віддає 404, коли в «Читанні» більше записів на сторінку.
 */
function zadzerkalya_blog_home_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}

	$query->set( 'posts_per_page', 3 );
}

/**
 * У списку послуг в адмінці порядок видно і за ним можна сортувати.
 */
function zadzerkalya_service_admin_columns( $columns ) {
	$next = array();

	foreach ( $columns as $key => $label ) {
		$next[ $key ] = $label;

		if ( 'title' === $key ) {
			$next['zdk_order'] = __( 'Порядок', 'zadzerkalya' );
		}
	}

	return $next;
}
add_filter( 'manage_service_posts_columns', 'zadzerkalya_service_admin_columns' );

function zadzerkalya_service_admin_column_content( $column, $post_id ) {
	if ( 'zdk_order' !== $column ) {
		return;
	}

	echo (int) get_post_field( 'menu_order', $post_id );
}
add_action( 'manage_service_posts_custom_column', 'zadzerkalya_service_admin_column_content', 10, 2 );

function zadzerkalya_service_admin_sortable_columns( $columns ) {
	$columns['zdk_order'] = 'menu_order';

	return $columns;
}
add_filter( 'manage_edit-service_sortable_columns', 'zadzerkalya_service_admin_sortable_columns' );

function zadzerkalya_service_admin_list_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'service' !== $query->get( 'post_type' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['orderby'] ) ) {
		return;
	}

	$query->set(
		'orderby',
		array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		)
	);
}
add_action( 'pre_get_posts', 'zadzerkalya_service_admin_list_order' );
add_action( 'pre_get_posts', 'zadzerkalya_event_archive_query' );
add_action( 'pre_get_posts', 'zadzerkalya_blog_home_query' );
