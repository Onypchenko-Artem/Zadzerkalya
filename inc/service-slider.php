<?php
/**
 * Слайдер послуг у тексті новини: шорткод і метабокс.
 *
 * У тексті: [service_slider]
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Типи записів, у текст яких можна вставити слайдер.
 *
 * @return string[]
 */
function zadzerkalya_service_slider_post_types() {
	return array( 'post', 'event' );
}

/**
 * Заголовок зліва, якщо в адмінці його не змінили.
 *
 * @return string
 */
function zadzerkalya_service_slider_default_title() {
	return __( 'Запишіться на первинну консультацію та зробіть перший крок до', 'zadzerkalya' );
}

/**
 * Текст кнопки на картці, якщо в адмінці його не змінили.
 *
 * @return string
 */
function zadzerkalya_service_slider_default_button() {
	return __( 'забронювати первинну консультацію', 'zadzerkalya' );
}

/**
 * ID послуг у збереженому порядку.
 *
 * @param int $post_id Запис.
 * @return int[]
 */
function zadzerkalya_service_slider_ids( $post_id ) {
	$stored = get_post_meta( $post_id, '_zdk_slider_services', true );
	if ( ! is_array( $stored ) ) {
		return array();
	}

	$ids = array();
	foreach ( $stored as $id ) {
		$id = absint( $id );
		if ( $id && 'service' === get_post_type( $id ) && 'publish' === get_post_status( $id ) ) {
			$ids[] = $id;
		}
	}

	return array_values( array_unique( $ids ) );
}

/**
 * Метабокс під редактором новини або події.
 */
function zadzerkalya_service_slider_meta_box() {
	foreach ( zadzerkalya_service_slider_post_types() as $post_type ) {
		add_meta_box(
			'zdk_service_slider',
			__( 'Слайдер послуг', 'zadzerkalya' ),
			'zadzerkalya_render_service_slider_meta_box',
			$post_type,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'zadzerkalya_service_slider_meta_box' );

/**
 * Розмітка метабокса.
 *
 * @param WP_Post $post Поточний запис.
 */
function zadzerkalya_render_service_slider_meta_box( $post ) {
	wp_nonce_field( 'zadzerkalya_save_service_slider', 'zadzerkalya_slider_nonce' );

	$title  = get_post_meta( $post->ID, '_zdk_slider_title', true );
	$button = get_post_meta( $post->ID, '_zdk_slider_button', true );
	$ids    = zadzerkalya_service_slider_ids( $post->ID );

	if ( '' === $title ) {
		$title = zadzerkalya_service_slider_default_title();
	}
	if ( '' === $button ) {
		$button = zadzerkalya_service_slider_default_button();
	}

	$services = get_posts(
		array(
			'post_type'      => 'service',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
	?>
	<div class="zdk-slider-admin" data-zdk-slider-admin>
		<p class="description">
			<?php
			echo wp_kses(
				sprintf(
					/* translators: %s — шорткод. */
					__( 'Вставте %s у будь-яке місце тексту. На сайті з’явиться блок із заголовком, стрілками та картками обраних послуг.', 'zadzerkalya' ),
					'<code class="zdk-slider-admin__code">[service_slider]</code>'
				),
				array( 'code' => array( 'class' => true ) )
			);
			?>
		</p>

		<p>
			<label for="zdk_slider_title"><strong><?php esc_html_e( 'Заголовок зліва', 'zadzerkalya' ); ?></strong></label><br />
			<textarea class="widefat" rows="3" id="zdk_slider_title" name="zdk_slider_title"><?php echo esc_textarea( $title ); ?></textarea>
		</p>

		<p>
			<label for="zdk_slider_button"><strong><?php esc_html_e( 'Текст кнопки на картці', 'zadzerkalya' ); ?></strong></label><br />
			<input type="text" class="widefat" id="zdk_slider_button" name="zdk_slider_button" value="<?php echo esc_attr( $button ); ?>" />
		</p>

		<p><strong><?php esc_html_e( 'Послуги в слайдері', 'zadzerkalya' ); ?></strong></p>
		<ul class="zdk-slider-admin__list" data-zdk-slider-list>
			<?php foreach ( $ids as $service_id ) : ?>
				<li class="zdk-slider-admin__item">
					<input type="hidden" name="zdk_slider_services[]" value="<?php echo esc_attr( (string) $service_id ); ?>" />
					<span class="zdk-slider-admin__name"><?php echo esc_html( get_the_title( $service_id ) ); ?></span>
					<span class="zdk-slider-admin__actions">
						<button type="button" class="button button-small" data-move="up" aria-label="<?php esc_attr_e( 'Вище', 'zadzerkalya' ); ?>">↑</button>
						<button type="button" class="button button-small" data-move="down" aria-label="<?php esc_attr_e( 'Нижче', 'zadzerkalya' ); ?>">↓</button>
						<button type="button" class="button button-small" data-remove><?php esc_html_e( 'Прибрати', 'zadzerkalya' ); ?></button>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<p class="description" data-zdk-slider-empty <?php echo $ids ? 'hidden' : ''; ?>><?php esc_html_e( 'Поки не обрано жодної послуги — блок на сайті не з’явиться.', 'zadzerkalya' ); ?></p>

		<div class="zdk-slider-admin__add">
			<select data-zdk-slider-select>
				<option value=""><?php esc_html_e( 'Оберіть послугу', 'zadzerkalya' ); ?></option>
				<?php foreach ( $services as $service ) : ?>
					<option value="<?php echo esc_attr( (string) $service->ID ); ?>" <?php disabled( in_array( $service->ID, $ids, true ) ); ?>><?php echo esc_html( get_the_title( $service ) ); ?></option>
				<?php endforeach; ?>
			</select>
			<button type="button" class="button" data-zdk-slider-add><?php esc_html_e( 'Додати', 'zadzerkalya' ); ?></button>
		</div>
		<input type="hidden" name="zdk_slider_present" value="1" />
	</div>
	<?php
}

/**
 * Збереження заголовка, кнопки й порядку послуг.
 *
 * @param int $post_id Запис.
 */
function zadzerkalya_save_service_slider( $post_id ) {
	if ( ! isset( $_POST['zadzerkalya_slider_nonce'], $_POST['zdk_slider_present'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['zadzerkalya_slider_nonce'] ) ), 'zadzerkalya_save_service_slider' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! in_array( get_post_type( $post_id ), zadzerkalya_service_slider_post_types(), true ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$title = isset( $_POST['zdk_slider_title'] ) ? sanitize_textarea_field( wp_unslash( $_POST['zdk_slider_title'] ) ) : '';
	$button = isset( $_POST['zdk_slider_button'] ) ? sanitize_text_field( wp_unslash( $_POST['zdk_slider_button'] ) ) : '';

	update_post_meta( $post_id, '_zdk_slider_title', $title );
	update_post_meta( $post_id, '_zdk_slider_button', $button );

	$ids = array();
	if ( isset( $_POST['zdk_slider_services'] ) && is_array( $_POST['zdk_slider_services'] ) ) {
		foreach ( wp_unslash( $_POST['zdk_slider_services'] ) as $raw_id ) {
			$id = absint( $raw_id );
			if ( $id && 'service' === get_post_type( $id ) ) {
				$ids[] = $id;
			}
		}
	}

	update_post_meta( $post_id, '_zdk_slider_services', array_values( array_unique( $ids ) ) );
}
add_action( 'save_post', 'zadzerkalya_save_service_slider' );

/**
 * Скрипт і стилі метабокса.
 *
 * @param string $hook Поточний екран адмінки.
 */
function zadzerkalya_service_slider_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, zadzerkalya_service_slider_post_types(), true ) ) {
		return;
	}

	wp_enqueue_script(
		'zadzerkalya-service-slider-admin',
		ZADZERKALYA_URI . '/assets/js/service-slider-admin.js',
		array(),
		ZADZERKALYA_VERSION,
		true
	);

	$css = '
		.zdk-slider-admin__code { font-size: 13px; }
		.zdk-slider-admin__list { margin: 8px 0 12px; max-width: 640px; }
		.zdk-slider-admin__item { display: flex; align-items: center; gap: 12px; margin: 0 0 8px; padding: 8px 10px; background: #fff; border: 1px solid #c3c4c7; border-radius: 4px; }
		.zdk-slider-admin__name { flex: 1 1 auto; }
		.zdk-slider-admin__actions { display: flex; flex: 0 0 auto; gap: 4px; }
		.zdk-slider-admin__add { display: flex; align-items: center; gap: 8px; max-width: 640px; }
		.zdk-slider-admin__add select { flex: 1 1 auto; max-width: none; }
	';

	wp_register_style( 'zadzerkalya-service-slider-admin', false, array(), ZADZERKALYA_VERSION );
	wp_enqueue_style( 'zadzerkalya-service-slider-admin' );
	wp_add_inline_style( 'zadzerkalya-service-slider-admin', $css );
}
add_action( 'admin_enqueue_scripts', 'zadzerkalya_service_slider_admin_assets' );

/**
 * Прибирає абзац навколо шорткода, щоб блок не ламався всередині <p>.
 *
 * @param string $content Текст запису.
 * @return string
 */
function zadzerkalya_unwrap_service_slider_shortcode( $content ) {
	return preg_replace( '#<p(?:\s[^>]*)?>\s*(\[service_slider\b[^\]]*\])\s*</p>#', '$1', $content );
}
add_filter( 'the_content', 'zadzerkalya_unwrap_service_slider_shortcode', 10 );

/**
 * Шорткод [service_slider] — слайдер послуг поточного запису.
 *
 * @param array<string, string>|string $atts Атрибути. title і button перекривають значення з адмінки.
 * @return string
 */
function zadzerkalya_service_slider_shortcode( $atts ) {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	$atts = shortcode_atts(
		array(
			'title'  => '',
			'button' => '',
		),
		$atts,
		'service_slider'
	);

	$ids = zadzerkalya_service_slider_ids( $post_id );
	if ( ! $ids ) {
		return '';
	}

	$title = '' !== $atts['title'] ? $atts['title'] : (string) get_post_meta( $post_id, '_zdk_slider_title', true );
	$button = '' !== $atts['button'] ? $atts['button'] : (string) get_post_meta( $post_id, '_zdk_slider_button', true );

	if ( '' === $title ) {
		$title = zadzerkalya_service_slider_default_title();
	}
	if ( '' === $button ) {
		$button = zadzerkalya_service_slider_default_button();
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'service',
			'post_status'    => 'publish',
			'post__in'       => $ids,
			'orderby'        => 'post__in',
			'posts_per_page' => count( $ids ),
		)
	);

	static $instance = 0;
	++$instance;

	ob_start();
	get_template_part(
		'template-parts/section',
		'service-slider',
		array(
			'title'      => $title,
			'button'     => $button,
			'query'      => $query,
			'heading_id' => 'service-slider-title-' . $instance,
		)
	);
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_shortcode( 'service_slider', 'zadzerkalya_service_slider_shortcode' );
