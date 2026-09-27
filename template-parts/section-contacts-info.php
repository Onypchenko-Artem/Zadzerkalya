<?php
/**
 * Контактна інформація.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title Заголовок секції.
 * }
 */

$title   = $args['title'] ?? __( 'Чекаємо на вас у нашому Задзеркальному просторі!', 'zadzerkalya' );
$address = zadzerkalya_theme_mod( 'address', 'м. Львів, вул. Камʼянецька 1' );
$email   = zadzerkalya_theme_mod( 'email', 'zadzerkallya@gmail.com' );
$hours   = zadzerkalya_theme_mod( 'hours', 'Пн-Сб | 09:00-19:00' );
$phones  = zadzerkalya_phones();
$socials = array(
	'instagram' => zadzerkalya_theme_mod( 'instagram' ),
	'facebook'  => zadzerkalya_theme_mod( 'facebook' ),
);
?>
<section class="contacts-info" aria-labelledby="contacts-info-title">
	<h2 id="contacts-info-title"><?php echo wp_kses( nl2br( esc_html( $title ) ), array( 'br' => array() ) ); ?></h2>

	<div class="contacts-info__details">
		<div class="contacts-info__row">
			<h3><?php esc_html_e( 'Наша адреса', 'zadzerkalya' ); ?></h3>
			<p><?php echo esc_html( $address ); ?></p>
		</div>

		<div class="contacts-info__row contacts-info__row--contacts">
			<h3><?php esc_html_e( 'Контакти', 'zadzerkalya' ); ?></h3>
			<div class="contacts-info__values">
				<?php foreach ( $phones as $phone ) : ?>
					<a href="<?php echo esc_attr( zadzerkalya_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				<?php endforeach; ?>
				<?php if ( $email ) : ?>
					<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="contacts-info__row">
			<h3><?php esc_html_e( 'Графік роботи', 'zadzerkalya' ); ?></h3>
			<p><?php echo esc_html( $hours ); ?></p>
		</div>

		<div class="contacts-info__row contacts-info__row--socials">
			<h3><?php esc_html_e( 'Ми в соцмережах', 'zadzerkalya' ); ?></h3>
			<div class="contacts-info__socials">
				<?php foreach ( $socials as $network => $url ) : ?>
					<?php if ( $url ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text"><?php echo esc_html( ucfirst( $network ) ); ?></span>
							<?php zadzerkalya_icon( $network ); ?>
						</a>
					<?php else : ?>
						<span aria-hidden="true"><?php zadzerkalya_icon( $network ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
