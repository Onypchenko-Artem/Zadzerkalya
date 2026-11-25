<?php
/**
 * Попап згоди на cookies. Перший візит, поки немає куки вибору.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$art = ZADZERKALYA_URI . '/assets/images/cookies.png?ver=' . ZADZERKALYA_VERSION;
?>
<div class="cookies-popup" id="cookies-popup" data-cookies-popup>
	<div class="cookies-popup__overlay"></div>

	<div class="cookies-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="cookies-popup-title" aria-describedby="cookies-popup-text" tabindex="-1">
		<div class="cookies-popup__main">
			<header class="cookies-popup__header">
				<div class="cookies-popup__title-row">
					<h2 class="cookies-popup__title" id="cookies-popup-title"><?php esc_html_e( 'Ми використовуємо файли cookies', 'zadzerkalya' ); ?></h2>
					<button class="cookies-popup__close" type="button" data-cookies-necessary>
						<span class="screen-reader-text"><?php esc_html_e( 'Закрити', 'zadzerkalya' ); ?></span>
						<?php zadzerkalya_icon( 'close-mobile' ); ?>
					</button>
				</div>
				<img class="cookies-popup__art" src="<?php echo esc_url( $art ); ?>" alt="" width="120" height="68">
			</header>

			<div class="cookies-popup__text" id="cookies-popup-text">
				<p><?php esc_html_e( 'Ми використовуємо cookies, щоб зробити вашу взаємодію з сайтом зручнішою та приємнішою. Вони допомагають нам запамʼятовувати ваші налаштування, розуміти, як ви користуєтеся сайтом, і поступово робити його ще кращим.', 'zadzerkalya' ); ?></p>
				<p><?php esc_html_e( 'Ви можете дозволити всі cookies, залишити лише необхідні або обрати власні налаштування — як вам зручніше.', 'zadzerkalya' ); ?></p>
			</div>
		</div>

		<div class="cookies-popup__actions">
			<button class="lottie-button lottie-button--primary" data-variant="primary" data-mobile-rows="1" data-anim-desktop="button-primary-tablet" type="button" data-cookies-all="true">
				<span class="lottie-button__anim" aria-hidden="true"></span>
				<span class="lottie-button__label"><?php esc_html_e( 'Прийняти всі cookies', 'zadzerkalya' ); ?></span>
			</button>
			<button class="cookies-popup__necessary" type="button" data-cookies-necessary>
				<span class="cookies-popup__necessary-label"><?php esc_html_e( 'Прийняти тільки необхідні cookies', 'zadzerkalya' ); ?></span>
				<span class="cookies-popup__line" aria-hidden="true"></span>
			</button>
		</div>
	</div>
</div>
