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
 * У «Зовнішній вигляд → Меню» WordPress ховає всі типи записів і таксономії,
 * крім сторінок, записів і рубрик.
 */
function zadzerkalya_show_post_types_in_nav_menus( $hidden, $screen ) {
	if ( ! $screen instanceof WP_Screen || 'nav-menus' !== $screen->id ) {
		return $hidden;
	}

	$visible = array(
		'add-post-type-service',
		'add-post-type-specialist',
		'add-post-type-event',
		'add-service_type',
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
 * Query var для фільтра типу послуг у ЧПУ /services/{slug}/.
 *
 * @param string[] $vars Query vars.
 * @return string[]
 */
function zadzerkalya_service_type_query_vars( $vars ) {
	$vars[] = 'zdk_service_type';

	return $vars;
}
add_filter( 'query_vars', 'zadzerkalya_service_type_query_vars' );

/**
 * Базовий slug сторінки / архіву послуг.
 *
 * @return string
 */
function zadzerkalya_get_services_base_slug() {
	$page_id = zadzerkalya_get_services_page_id();
	if ( $page_id ) {
		$slug = get_post_field( 'post_name', $page_id );
		if ( is_string( $slug ) && '' !== $slug ) {
			return $slug;
		}
	}

	return 'services';
}

/**
 * Правила ЧПУ: /services/{type-slug}/ → сторінка послуг з фільтром типу.
 * Не перехоплює slug опублікованої послуги.
 */
function zadzerkalya_register_service_type_rewrites() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'service_type',
			'hide_empty' => false,
			'fields'     => 'id=>slug',
		)
	);

	if ( is_wp_error( $terms ) || ! $terms ) {
		return;
	}

	$base    = zadzerkalya_get_services_base_slug();
	$page_id = zadzerkalya_get_services_page_id();

	foreach ( $terms as $slug ) {
		$slug = sanitize_title( (string) $slug );
		if ( '' === $slug ) {
			continue;
		}

		$collision = get_posts(
			array(
				'post_type'              => 'service',
				'name'                   => $slug,
				'post_status'            => 'publish',
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		if ( $collision ) {
			continue;
		}

		$regex = '^' . preg_quote( $base, '/' ) . '/' . preg_quote( $slug, '/' ) . '/?$';

		if ( $page_id ) {
			add_rewrite_rule(
				$regex,
				'index.php?page_id=' . (int) $page_id . '&zdk_service_type=' . $slug,
				'top'
			);
		} else {
			add_rewrite_rule(
				$regex,
				'index.php?post_type=service&zdk_service_type=' . $slug,
				'top'
			);
		}
	}
}
add_action( 'init', 'zadzerkalya_register_service_type_rewrites', 20 );

/**
 * Після змін типів — перезібрати ЧПУ на наступному init (коли правила вже з новим slug).
 */
function zadzerkalya_flush_service_type_rewrites() {
	delete_option( 'zadzerkalya_service_type_rewrite_v2' );
}
add_action( 'created_service_type', 'zadzerkalya_flush_service_type_rewrites' );
add_action( 'edited_service_type', 'zadzerkalya_flush_service_type_rewrites' );
add_action( 'delete_service_type', 'zadzerkalya_flush_service_type_rewrites' );

/**
 * Одноразове підключення ЧПУ типів послуг.
 */
function zadzerkalya_sync_service_type_rewrites() {
	if ( get_option( 'zadzerkalya_service_type_rewrite_v2' ) ) {
		return;
	}

	update_option( 'zadzerkalya_service_type_rewrite_v2', 1 );
	flush_rewrite_rules( false );
}
add_action( 'init', 'zadzerkalya_sync_service_type_rewrites', 99 );

/**
 * Старі ?type=slug → /services/slug/.
 */
function zadzerkalya_redirect_service_type_query() {
	if ( is_admin() || empty( $_GET['type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$on_services = is_page_template( 'page-templates/services.php' ) || is_post_type_archive( 'service' );
	if ( ! $on_services ) {
		return;
	}

	$slug = sanitize_title( wp_unslash( $_GET['type'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$term = $slug ? get_term_by( 'slug', $slug, 'service_type' ) : null;
	if ( ! $term || is_wp_error( $term ) || ! function_exists( 'zadzerkalya_get_service_type_url' ) ) {
		return;
	}

	wp_safe_redirect( zadzerkalya_get_service_type_url( $term ), 301 );
	exit;
}
add_action( 'template_redirect', 'zadzerkalya_redirect_service_type_query', 5 );

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
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			// ЧПУ типів — /services/{slug}/, див. zadzerkalya_register_service_type_rewrites().
			'rewrite'           => false,
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
 * Стрічка блогу показує 9 карток. Головний запит має збігатися з нею,
 * інакше /blog/page/2/ віддає 404, коли в «Читанні» більше записів на сторінку.
 */
function zadzerkalya_blog_home_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
		return;
	}

	$query->set( 'posts_per_page', 9 );
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
			$next['zdk_page']  = __( 'Сторінка', 'zadzerkalya' );
		}
	}

	return $next;
}
add_filter( 'manage_service_posts_columns', 'zadzerkalya_service_admin_columns' );

function zadzerkalya_service_admin_column_content( $column, $post_id ) {
	if ( 'zdk_order' === $column ) {
		echo (int) get_post_field( 'menu_order', $post_id );
		return;
	}

	if ( 'zdk_page' !== $column ) {
		return;
	}

	$enabled = ! function_exists( 'zadzerkalya_service_has_page' ) || zadzerkalya_service_has_page( $post_id );
	echo $enabled
		? esc_html__( 'Увімкнено', 'zadzerkalya' )
		: esc_html__( 'Вимкнено', 'zadzerkalya' );
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

/**
 * Вимкнена сторінка послуги недоступна напряму (лістинг лишається).
 */
function zadzerkalya_block_disabled_service_page() {
	if ( is_admin() || ! is_singular( 'service' ) ) {
		return;
	}

	$service_id = (int) get_queried_object_id();
	if ( ! $service_id || ! function_exists( 'zadzerkalya_service_has_page' ) || zadzerkalya_service_has_page( $service_id ) ) {
		return;
	}

	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
	$template = get_query_template( '404' );
	if ( $template ) {
		include $template;
	}
	exit;
}
add_action( 'template_redirect', 'zadzerkalya_block_disabled_service_page' );
