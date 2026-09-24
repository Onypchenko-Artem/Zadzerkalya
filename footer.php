<?php
/**
 * Підвал сайту.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phones      = zadzerkalya_phones();
$address     = zadzerkalya_theme_mod( 'address', 'м. Львів, вул. Камʼянецька 1' );
$hours       = zadzerkalya_theme_mod( 'hours', 'ПН-СБ | 09:00-19:00' );
$hours_lines = array_values( array_filter( preg_split( '/\r\n|\r|\n/', $hours ) ) );

if ( count( $hours_lines ) < 2 ) {
	$hours_lines[] = __( 'НД | ЗАЧИНЕНО', 'zadzerkalya' );
}

$socials = array(
	'instagram' => zadzerkalya_theme_mod( 'instagram' ),
	'telegram'  => zadzerkalya_theme_mod( 'telegram' ),
	'tiktok'    => zadzerkalya_theme_mod( 'tiktok' ),
);

$privacy_url = get_privacy_policy_url();
if ( ! $privacy_url ) {
	$privacy_url = home_url( '/privacy-policy/' );
}
?>
<footer class="site-footer" role="contentinfo">
	<span class="site-footer__line site-footer__line--left" aria-hidden="true"></span>
	<span class="site-footer__line site-footer__line--right" aria-hidden="true"></span>
	<span class="site-footer__divider" aria-hidden="true"></span>

	<div class="site-footer__brand">
		<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		</a>
		<p><?php esc_html_e( 'Ми віримо в кожну дитину', 'zadzerkalya' ); ?></p>
	</div>

	<div class="site-footer__details">
		<section class="site-footer__section site-footer__section--contacts">
			<h2><?php esc_html_e( 'Контакти', 'zadzerkalya' ); ?></h2>
			<div class="site-footer__section-content">
				<?php if ( $address ) : ?>
					<p class="site-footer__address">
						<span><?php echo esc_html( $address ); ?></span>
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/footer-address-line.svg' ); ?>" alt="" aria-hidden="true">
					</p>
				<?php endif; ?>

				<div class="site-footer__phones">
					<?php foreach ( $phones as $phone ) : ?>
						<a href="<?php echo esc_attr( zadzerkalya_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
					<?php endforeach; ?>
				</div>

				<div class="site-footer__socials">
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
		</section>

		<section class="site-footer__section site-footer__section--hours">
			<h2><?php esc_html_e( 'Графік роботи', 'zadzerkalya' ); ?></h2>
			<div class="site-footer__section-content">
				<?php foreach ( $hours_lines as $line ) : ?>
					<p><?php echo esc_html( $line ); ?></p>
				<?php endforeach; ?>
			</div>
		</section>

		<section class="site-footer__section site-footer__section--menu">
			<h2><?php esc_html_e( 'Меню', 'zadzerkalya' ); ?></h2>
			<?php
			if ( has_nav_menu( 'footer' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
			} else {
				echo '<ul class="footer-menu">';
				foreach ( zadzerkalya_header_nav_items() as $item ) {
					$url = ! empty( $item['object'] ) ? get_permalink( $item['object'] ) : ( $item['url'] ?? home_url( '/' ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $item['title'] ) );
				}
				echo '</ul>';
			}
			?>
		</section>
	</div>

	<div class="site-footer__bottom">
		<p class="site-footer__credits">
			<span><?php esc_html_e( 'Хто зробив цей сайт?', 'zadzerkalya' ); ?></span>
			<strong>KSANTY WEB</strong>
		</p>
		<p class="site-footer__copyright">
			<span>&copy;<?php echo esc_html( gmdate( 'Y' ) ); ?>. <?php bloginfo( 'name' ); ?></span>
			<span><?php esc_html_e( 'Всі права захищено', 'zadzerkalya' ); ?></span>
		</p>
		<p class="site-footer__privacy">
			<a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Політика конфіденційності та cookies', 'zadzerkalya' ); ?></a>
		</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
