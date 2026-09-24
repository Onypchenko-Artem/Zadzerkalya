<?php
/**
 * Форма запису на консультацію для головної сторінки.
 *
 * @package Zadzerkalya
 */

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'        => __( 'Кожен день — важливий!', 'zadzerkalya' ),
		'description'  => __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ),
		'button_label' => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
		'source'       => 'home',
	)
);
?>
<section class="form-home" aria-labelledby="form-home-title">
	<div class="form-home__background" aria-hidden="true">
		<img class="form-home__background-left" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/left-bg-img.png' ); ?>" alt="">
		<img class="form-home__background-right" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/right-bg-img.png' ); ?>" alt="">
	</div>

	<div class="form-home__content">
		<header class="form-home__heading">
			<h2 id="form-home-title"><?php echo esc_html( $args['title'] ); ?></h2>
			<p><?php echo esc_html( $args['description'] ); ?></p>
		</header>

		<form class="form-home__form" method="post" action="">
			<?php zadzerkalya_contact_notice(); ?>
			<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
			<input type="hidden" name="zadzerkalya_form_source" value="<?php echo esc_attr( $args['source'] ); ?>">

			<p class="hp-field" aria-hidden="true">
				<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
					<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
				</label>
			</p>

			<p class="form-home__field">
				<label for="home-form-name"><?php esc_html_e( 'Імʼя*', 'zadzerkalya' ); ?></label>
				<input id="home-form-name" name="zadzerkalya_name" type="text" autocomplete="name" required>
			</p>

			<p class="form-home__field">
				<label for="home-form-phone"><?php esc_html_e( 'Телефон*', 'zadzerkalya' ); ?></label>
				<input id="home-form-phone" name="zadzerkalya_phone" type="tel" autocomplete="tel" placeholder="+38 (000) 000-00-00" required>
			</p>

			<p class="form-home__field">
				<label for="home-form-email"><?php esc_html_e( 'Email', 'zadzerkalya' ); ?></label>
				<input id="home-form-email" name="zadzerkalya_email" type="email" autocomplete="email" required>
			</p>

			<p class="form-home__field form-home__field--message">
				<label for="home-form-message"><?php esc_html_e( 'Повідомлення', 'zadzerkalya' ); ?></label>
				<textarea id="home-form-message" name="zadzerkalya_message" required></textarea>
			</p>

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
</section>
