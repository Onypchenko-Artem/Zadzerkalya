<?php
/**
 * Форма запису на консультацію на сторінці послуги.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title        Заголовок секції.
 *     @type string $text_left    Ліва колонка під заголовком.
 *     @type string $text_right   Права колонка під заголовком.
 *     @type mixed  $photo        Фото між текстами і формою.
 *     @type string $note         Текст біля форми.
 *     @type string $button_label Підпис кнопки.
 *     @type string $composition  1 — текст зліва, 2 — форма зліва.
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'        => '',
		'text_left'    => '',
		'text_right'   => '',
		'photo'        => null,
		'note'         => '',
		'button_label' => '',
		'composition'  => '1',
	)
);

$defaults = array(
	'title'        => __( 'Чому важливо звернутися вчасно?', 'zadzerkalya' ),
	'text_left'    => __( 'Сенсорна інтеграція — це той базовий рівень роботи нервової системи, який забезпечує здатність дитини сприймати, обробляти та організовувати усю інформацію від навколишнього світу. І саме ця основа робить можливим подальший розвиток навчальних, мовленнєвих, поведінкових і соціальних навичок.', 'zadzerkalya' ),
	'text_right'   => __( 'Коли процеси сенсорної інтеграції порушені — це напряму впливає не лише на поведінку та навчання, а й на фізичний стан дитини та її взаємодію з навколишнім середовищем: підвищену втому, дискомфорт у тілі, труднощі з концентрацією, гостру реакцію на звуки, світло, дотики чи рух. Саме тому так важливо не відкладати звернення.', 'zadzerkalya' ),
	'note'         => __( 'Ми поруч, аби допомогти вам визначити усі актуальні особливості сенсорного розвитку вашої дитини — та створити чіткий і послідовний план допомоги. Чекаємо вас на первинній консультації!', 'zadzerkalya' ),
	'button_label' => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
);

$has_custom = '' !== trim( (string) $args['title'] )
	|| '' !== trim( wp_strip_all_tags( (string) $args['text_left'] ) )
	|| '' !== trim( wp_strip_all_tags( (string) $args['text_right'] ) )
	|| '' !== trim( wp_strip_all_tags( (string) $args['note'] ) )
	|| ! empty( $args['photo'] );

if ( ! $has_custom ) {
	$args = array_merge( $args, $defaults );
}

if ( '' === trim( (string) $args['button_label'] ) ) {
	$args['button_label'] = $defaults['button_label'];
}

$format_text = static function ( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	if ( $value === wp_strip_all_tags( $value ) ) {
		$value = wpautop( $value );
	}

	return wp_kses_post( $value );
};

$title      = trim( (string) $args['title'] );
$text_left  = $format_text( $args['text_left'] );
$text_right = $format_text( $args['text_right'] );
$note       = $format_text( $args['note'] );
$composition = '2' === (string) $args['composition'] ? '2' : '1';
$form_id    = get_the_ID() ? (string) get_the_ID() : 'service';
?>
<section class="form-service form-service--composition-<?php echo esc_attr( $composition ); ?>"<?php echo $title ? ' aria-labelledby="form-service-title"' : ''; ?>>
	<div class="form-service__rule" aria-hidden="true">
		<img class="form-service__rule-image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/form-service-line-top.svg' ); ?>" alt="">
	</div>

	<div class="form-service__content">
		<?php if ( $title || $text_left || $text_right ) : ?>
			<header class="form-service__intro">
				<?php if ( $title ) : ?>
					<h2 id="form-service-title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $text_left || $text_right ) : ?>
					<div class="form-service__columns">
						<?php if ( $text_left ) : ?>
							<div class="form-service__column form-service__column--left"><?php echo $text_left; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
						<?php if ( $text_right ) : ?>
							<div class="form-service__column form-service__column--right"><?php echo $text_right; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $args['photo'] ) ) : ?>
			<div class="form-service__photo">
				<?php zadzerkalya_acf_image( $args['photo'], 'full', array( 'alt' => $title ) ); ?>
			</div>
		<?php endif; ?>

		<div class="form-service__bottom">
			<?php if ( $note ) : ?>
				<div class="form-service__note"><?php echo $note; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<img class="form-service__divider" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/form-service-divider.svg' ); ?>" alt="" aria-hidden="true">

			<form class="form-service__form" method="post" action="">
				<?php zadzerkalya_contact_notice(); ?>
				<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
				<input type="hidden" name="zadzerkalya_form_source" value="service">

				<p class="hp-field" aria-hidden="true">
					<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
						<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
					</label>
				</p>

				<p class="form-service__field">
					<label for="form-service-name-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Імʼя*', 'zadzerkalya' ); ?></label>
					<input id="form-service-name-<?php echo esc_attr( $form_id ); ?>" name="zadzerkalya_name" type="text" autocomplete="name" required>
				</p>

				<p class="form-service__field">
					<label for="form-service-phone-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Телефон*', 'zadzerkalya' ); ?></label>
					<input id="form-service-phone-<?php echo esc_attr( $form_id ); ?>" name="zadzerkalya_phone" type="tel" autocomplete="tel" placeholder="+38 (000) 000-00-00" required>
				</p>

				<?php zadzerkalya_form_consent(); ?>

				<?php
				zadzerkalya_button(
					array(
						'label'   => $args['button_label'],
						'variant' => 'primary',
						'type'    => 'submit',
						'name'    => 'zadzerkalya_contact_submit',
						'value'   => '1',
					)
				);
				?>
			</form>
		</div>
	</div>

	<div class="form-service__rule form-service__rule--bottom" aria-hidden="true">
		<img class="form-service__rule-image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/form-service-line-bottom.svg' ); ?>" alt="">
	</div>
</section>
