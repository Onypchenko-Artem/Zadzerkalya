<?php
/**
 * Попап-форма контакту: компактна версія форми «Кожен день — важливий!».
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $event_title Назва події для листа.
 *     @type string $source      Джерело заявки для обробника форми.
 *     @type bool   $open        Відкрити одразу (після відправки форми).
 * }
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'event_title' => '',
		'source'      => 'event-next',
		'open'        => false,
	)
);

$open = ! empty( $args['open'] );
?>
<div class="contact-popup" id="contact-popup" data-contact-popup<?php echo $open ? ' data-open' : ' hidden'; ?>>
	<div class="contact-popup__overlay" data-contact-popup-close></div>

	<div class="contact-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="contact-popup-title">
		<div class="contact-popup__clip" aria-hidden="true">
			<img class="contact-popup__bg" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/Popup-background.jpg' ); ?>" alt="">
		</div>

		<img class="contact-popup__rabbit" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/running rabbit.png' ); ?>" alt="" aria-hidden="true">

		<button class="contact-popup__close" type="button" data-contact-popup-close>
			<span class="screen-reader-text"><?php esc_html_e( 'Закрити', 'zadzerkalya' ); ?></span>
			<svg class="icon icon--close" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<circle cx="20" cy="20" r="20" fill="#88BBF2"/>
				<g transform="translate(1 2.5)">
					<path class="icon-social__stroke" d="M2.9221 11.4193C1.92896 15.5413 1.14821 18.6693 0.719609 21.0625C0.16673 24.1498 0.758908 26.7885 1.37973 28.2253C2.41298 30.6166 6.51091 32.4854 10.6299 34.0148C12.0908 34.5572 14.528 34.5272 16.8233 34.4708C21.2901 34.3609 24.8567 33.1297 27.9691 31.3954C32.2228 29.0252 34.644 25.2846 35.8562 22.9885C38.0041 18.92 37.6303 14.5063 37.0482 11.5224C36.6012 9.23121 34.4825 7.25335 32.6979 5.39119C30.9973 3.61684 28.8785 2.51665 26.8198 1.57157C24.7225 0.608749 21.7843 0.493661 18.8244 0.500255C16.7412 0.504896 14.2218 1.66904 10.9153 3.20869C5.48858 5.73566 4.21053 8.00253 3.56711 9.15828C3.41023 9.52586 3.23928 9.98425 3.06192 10.5288C2.88456 11.0734 2.70598 11.6904 2.52199 12.326" stroke="#0F1A2D" stroke-linecap="round"/>
				</g>
				<path stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" d="M14.2 14.2 25.8 25.8M25.8 14.2 14.2 25.8"/>
			</svg>
		</button>

		<header class="contact-popup__heading">
			<h2 id="contact-popup-title"><?php esc_html_e( 'Кожен день — важливий!', 'zadzerkalya' ); ?></h2>
			<p><?php esc_html_e( 'Залиште свої контакти — і ми звʼяжемося з вами для запису на консультацію', 'zadzerkalya' ); ?></p>
		</header>

		<form class="contact-popup__form" method="post" action="">
			<?php zadzerkalya_contact_notice( 'popup' ); ?>
			<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
			<input type="hidden" name="zadzerkalya_form_source" value="<?php echo esc_attr( $args['source'] ); ?>">
			<input type="hidden" name="zadzerkalya_event_title" value="<?php echo esc_attr( $args['event_title'] ); ?>">

			<p class="hp-field" aria-hidden="true">
				<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
					<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
				</label>
			</p>

			<p class="contact-popup__field">
				<label for="contact-popup-name"><?php esc_html_e( 'Імʼя*', 'zadzerkalya' ); ?></label>
				<input id="contact-popup-name" name="zadzerkalya_name" type="text" autocomplete="name" required>
			</p>

			<div class="contact-popup__phone">
				<p class="contact-popup__field">
					<label for="contact-popup-phone"><?php esc_html_e( 'Телефон*', 'zadzerkalya' ); ?></label>
					<input id="contact-popup-phone" name="zadzerkalya_phone" type="tel" autocomplete="tel" placeholder="+38 (000) 000-00-00" pattern="\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}" title="<?php esc_attr_e( 'Введіть номер повністю: +38 (000) 000-00-00', 'zadzerkalya' ); ?>" required>
				</p>
				<?php zadzerkalya_form_consent(); ?>
			</div>

			<?php
			zadzerkalya_button(
				array(
					'label'   => __( 'забронювати участь', 'zadzerkalya' ),
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
