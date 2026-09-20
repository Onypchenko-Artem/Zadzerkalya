<?php
/**
 * Налаштування зовнішнього вигляду: контакти, герой, соцмережі.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function zadzerkalya_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'zadzerkalya_theme',
		array(
			'title'    => __( 'Задзеркалля', 'zadzerkalya' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_section(
		'zadzerkalya_contacts',
		array(
			'title' => __( 'Контакти', 'zadzerkalya' ),
			'panel' => 'zadzerkalya_theme',
		)
	);

	$contact_fields = array(
		'phone'     => __( 'Телефон 1', 'zadzerkalya' ),
		'phone_2'   => __( 'Телефон 2', 'zadzerkalya' ),
		'email'     => __( 'Email', 'zadzerkalya' ),
		'address'   => __( 'Адреса', 'zadzerkalya' ),
		'hours'     => __( 'Години роботи', 'zadzerkalya' ),
		'map_embed' => __( 'Код карти (iframe)', 'zadzerkalya' ),
		'instagram' => __( 'Instagram URL', 'zadzerkalya' ),
		'facebook'  => __( 'Facebook URL', 'zadzerkalya' ),
		'telegram'  => __( 'Telegram URL', 'zadzerkalya' ),
	);

	foreach ( $contact_fields as $key => $label ) {
		$wp_customize->add_setting(
			'zadzerkalya_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'map_embed' === $key ? 'zadzerkalya_sanitize_iframe' : 'sanitize_text_field',
			)
		);

		$type = 'map_embed' === $key ? 'textarea' : 'text';

		$wp_customize->add_control(
			'zadzerkalya_' . $key,
			array(
				'label'   => $label,
				'section' => 'zadzerkalya_contacts',
				'type'    => $type,
			)
		);
	}

	$wp_customize->add_section(
		'zadzerkalya_hero',
		array(
			'title' => __( 'Головна: герой', 'zadzerkalya' ),
			'panel' => 'zadzerkalya_theme',
		)
	);

	$hero_fields = array(
		'hero_kicker'  => __( 'Надзаголовок', 'zadzerkalya' ),
		'hero_title'   => __( 'Заголовок', 'zadzerkalya' ),
		'hero_text'    => __( 'Текст', 'zadzerkalya' ),
		'hero_btn'     => __( 'Текст кнопки', 'zadzerkalya' ),
		'hero_btn_url' => __( 'Посилання кнопки', 'zadzerkalya' ),
	);

	foreach ( $hero_fields as $key => $label ) {
		$sanitize = 'hero_text' === $key ? 'sanitize_textarea_field' : ( 'hero_btn_url' === $key ? 'esc_url_raw' : 'sanitize_text_field' );

		$wp_customize->add_setting(
			'zadzerkalya_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => $sanitize,
			)
		);

		$wp_customize->add_control(
			'zadzerkalya_' . $key,
			array(
				'label'   => $label,
				'section' => 'zadzerkalya_hero',
				'type'    => 'hero_text' === $key ? 'textarea' : 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'zadzerkalya_hero_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'zadzerkalya_hero_image',
			array(
				'label'     => __( 'Зображення героя', 'zadzerkalya' ),
				'section'   => 'zadzerkalya_hero',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'zadzerkalya_hero_illustration',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'zadzerkalya_hero_illustration',
			array(
				'label'     => __( 'Ілюстрація героя', 'zadzerkalya' ),
				'section'   => 'zadzerkalya_hero',
				'mime_type' => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'zadzerkalya_customize_register' );

function zadzerkalya_sanitize_iframe( $value ) {
	return wp_kses(
		$value,
		array(
			'iframe' => array(
				'src'             => true,
				'width'           => true,
				'height'          => true,
				'style'           => true,
				'allow'           => true,
				'allowfullscreen' => true,
				'loading'         => true,
				'referrerpolicy'  => true,
				'frameborder'     => true,
			),
		)
	);
}

function zadzerkalya_theme_mod( $key, $default = '' ) {
	$value = get_theme_mod( 'zadzerkalya_' . $key, $default );
	return is_string( $value ) ? $value : $default;
}
