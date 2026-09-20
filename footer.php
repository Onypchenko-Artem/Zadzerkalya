<?php
/**
 * Підвал сайту.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phones  = zadzerkalya_phones();
$email   = zadzerkalya_theme_mod( 'email' );
$address = zadzerkalya_theme_mod( 'address', 'м. Львів, вул. Камʼянецька 1' );
$hours   = zadzerkalya_theme_mod( 'hours' );
?>
<footer class="site-footer" role="contentinfo">
	<div class="container footer-grid">
		<div class="footer-brand">
			<p class="footer-logo"><?php bloginfo( 'name' ); ?></p>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>

		<div>
			<h2 class="footer-title"><?php esc_html_e( 'Навігація', 'zadzerkalya' ); ?></h2>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</div>

		<div>
			<h2 class="footer-title"><?php esc_html_e( 'Контакти', 'zadzerkalya' ); ?></h2>
			<ul class="footer-contacts">
				<?php if ( $address ) : ?>
					<li><?php echo esc_html( $address ); ?></li>
				<?php endif; ?>
				<?php foreach ( $phones as $phone ) : ?>
					<li><a href="<?php echo esc_attr( zadzerkalya_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
				<?php endforeach; ?>
				<?php if ( $email ) : ?>
					<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
				<?php endif; ?>
				<?php if ( $hours ) : ?>
					<li><?php echo esc_html( $hours ); ?></li>
				<?php endif; ?>
			</ul>
			<?php
			$socials = array(
				'instagram' => zadzerkalya_theme_mod( 'instagram' ),
				'facebook'  => zadzerkalya_theme_mod( 'facebook' ),
				'telegram'  => zadzerkalya_theme_mod( 'telegram' ),
			);
			$socials = array_filter( $socials );
			if ( $socials ) :
				?>
				<ul class="footer-contacts">
					<?php foreach ( $socials as $network => $url ) : ?>
						<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( ucfirst( $network ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
			<div class="footer-widgets">
				<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php dynamic_sidebar( 'footer-3' ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
