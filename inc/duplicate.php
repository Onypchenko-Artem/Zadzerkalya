<?php
/**
 * Дублювання записів в адмінці (події, блог).
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Типи записів, для яких доступне дублювання.
 *
 * @return string[]
 */
function zadzerkalya_duplicate_post_types() {
	return apply_filters( 'zadzerkalya_duplicate_post_types', array( 'event', 'post' ) );
}

/**
 * URL дії дублювання з nonce.
 *
 * @param int $post_id ID запису.
 * @return string
 */
function zadzerkalya_duplicate_url( $post_id ) {
	return wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'zadzerkalya_duplicate',
				'post'   => (int) $post_id,
			),
			admin_url( 'admin.php' )
		),
		'zadzerkalya_duplicate_' . (int) $post_id
	);
}

/**
 * Посилання «Дублювати» у списку записів.
 *
 * @param string[] $actions Дії рядка.
 * @param WP_Post  $post    Запис.
 * @return string[]
 */
function zadzerkalya_duplicate_row_action( $actions, $post ) {
	if ( ! in_array( $post->post_type, zadzerkalya_duplicate_post_types(), true ) ) {
		return $actions;
	}

	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $actions;
	}

	$actions['zadzerkalya_duplicate'] = sprintf(
		'<a href="%s" aria-label="%s">%s</a>',
		esc_url( zadzerkalya_duplicate_url( $post->ID ) ),
		/* translators: %s — назва запису. */
		esc_attr( sprintf( __( 'Дублювати «%s»', 'zadzerkalya' ), get_the_title( $post ) ) ),
		esc_html__( 'Дублювати', 'zadzerkalya' )
	);

	return $actions;
}
add_filter( 'post_row_actions', 'zadzerkalya_duplicate_row_action', 10, 2 );

/**
 * Кнопка «Дублювати» на екрані редагування (блок «Опублікувати»).
 *
 * @param WP_Post $post Запис.
 */
function zadzerkalya_duplicate_submitbox_button( $post ) {
	if ( ! in_array( $post->post_type, zadzerkalya_duplicate_post_types(), true ) ) {
		return;
	}

	if ( 'auto-draft' === $post->post_status || ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	$label = 'post' === $post->post_type
		? __( 'Дублювати запис', 'zadzerkalya' )
		: __( 'Дублювати подію', 'zadzerkalya' );

	printf(
		'<div class="misc-pub-section"><a class="button" href="%s">%s</a></div>',
		esc_url( zadzerkalya_duplicate_url( $post->ID ) ),
		esc_html( $label )
	);
}
add_action( 'post_submitbox_misc_actions', 'zadzerkalya_duplicate_submitbox_button' );

/**
 * Обробник дії: створює чернетку-копію та переходить у її редагування.
 */
function zadzerkalya_handle_duplicate() {
	if ( ! isset( $_GET['action'], $_GET['post'] ) || 'zadzerkalya_duplicate' !== $_GET['action'] ) {
		return;
	}

	$post_id = (int) $_GET['post'];
	check_admin_referer( 'zadzerkalya_duplicate_' . $post_id );

	$post = get_post( $post_id );

	if ( ! $post || ! in_array( $post->post_type, zadzerkalya_duplicate_post_types(), true ) ) {
		wp_die( esc_html__( 'Запис для дублювання не знайдено.', 'zadzerkalya' ) );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'Недостатньо прав для дублювання цього запису.', 'zadzerkalya' ) );
	}

	$new_id = zadzerkalya_duplicate_post( $post );

	if ( is_wp_error( $new_id ) ) {
		wp_die( esc_html( $new_id->get_error_message() ) );
	}

	wp_safe_redirect(
		add_query_arg(
			array(
				'post'                  => $new_id,
				'action'                => 'edit',
				'zadzerkalya_duplicated' => 1,
			),
			admin_url( 'post.php' )
		)
	);
	exit;
}
add_action( 'admin_init', 'zadzerkalya_handle_duplicate' );

/**
 * Копіює запис разом із мета (включно з ACF), таксономіями та мініатюрою.
 *
 * @param WP_Post $post Оригінал.
 * @return int|WP_Error ID копії.
 */
function zadzerkalya_duplicate_post( $post ) {
	$new_id = wp_insert_post(
		array(
			'post_type'      => $post->post_type,
			'post_status'    => 'draft',
			/* translators: %s — назва оригінального запису. */
			'post_title'     => sprintf( __( '%s (копія)', 'zadzerkalya' ), $post->post_title ),
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_author'    => get_current_user_id(),
			'post_parent'    => $post->post_parent,
			'menu_order'     => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_password'  => $post->post_password,
		),
		true
	);

	if ( is_wp_error( $new_id ) ) {
		return $new_id;
	}

	// Таксономії.
	foreach ( get_object_taxonomies( $post->post_type ) as $taxonomy ) {
		$terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
		if ( ! is_wp_error( $terms ) && $terms ) {
			wp_set_object_terms( $new_id, $terms, $taxonomy );
		}
	}

	// Мета: власні поля, ACF (значення та _field-ключі), мініатюра.
	$skip = array( '_edit_lock', '_edit_last', '_wp_old_slug', '_wp_old_date' );
	$meta = get_post_meta( $post->ID );

	foreach ( $meta as $key => $values ) {
		if ( in_array( $key, $skip, true ) ) {
			continue;
		}

		foreach ( $values as $value ) {
			add_post_meta( $new_id, $key, maybe_unserialize( $value ) );
		}
	}

	/**
	 * Після створення копії.
	 *
	 * @param int     $new_id ID копії.
	 * @param WP_Post $post   Оригінал.
	 */
	do_action( 'zadzerkalya_post_duplicated', $new_id, $post );

	return $new_id;
}

/**
 * Повідомлення після успішного дублювання.
 */
function zadzerkalya_duplicate_notice() {
	if ( empty( $_GET['zadzerkalya_duplicated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	$screen   = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$is_post  = $screen && 'post' === $screen->post_type;
	$message  = $is_post
		? __( 'Запис продубльовано. Це чернетка — перевірте назву та опублікуйте.', 'zadzerkalya' )
		: __( 'Подію продубльовано. Це чернетка — перевірте дату, назву та опублікуйте.', 'zadzerkalya' );

	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html( $message )
	);
}
add_action( 'admin_notices', 'zadzerkalya_duplicate_notice' );
