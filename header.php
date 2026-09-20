<?php
/**
 * Шапка сайту.
 *
 * @package Zadzerkalya
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$address    = zadzerkalya_theme_mod( 'address', 'м. Львів, вул. Камʼянецька 1' );
$phones     = zadzerkalya_phones();
$instagram  = zadzerkalya_theme_mod( 'instagram' );
$telegram   = zadzerkalya_theme_mod( 'telegram' );
$contacts   = zadzerkalya_get_page_url( 'contacts' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php zadzerkalya_body_open(); ?>

<header class="site-header" role="banner">
	<div class="header-bar">
		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation">
			<span class="nav-toggle__open"><?php zadzerkalya_icon( 'burger' ); ?></span>
			<span class="nav-toggle__close"><?php zadzerkalya_icon( 'close' ); ?></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Меню', 'zadzerkalya' ); ?></span>
		</button>

		<nav id="site-navigation" class="header-nav" role="navigation" aria-label="<?php esc_attr_e( 'Головне меню', 'zadzerkalya' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'menu',
					'container'      => false,
					'fallback_cb'    => 'zadzerkalya_header_menu_fallback',
					'depth'          => 1,
				)
			);
			?>
		</nav>

		<a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="header-logo__plate" aria-hidden="true"></span>
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'header-logo__image', 'alt' => get_bloginfo( 'name' ) ) ); ?>
			<?php else : ?>
				<img class="header-logo__image" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/logo.svg' ); ?>" width="119" height="128" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php endif; ?>
		</a>

		<div class="header-aside">
			<div class="header-place">
				<?php if ( $address ) : ?>
					<div class="header-address">
						<span><?php echo esc_html( $address ); ?></span>
						<span class="header-address__line" aria-hidden="true"></span>
					</div>
				<?php endif; ?>
				<?php if ( $phones ) : ?>
					<div class="header-phones">
						<?php foreach ( $phones as $phone ) : ?>
							<a href="<?php echo esc_attr( zadzerkalya_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="header-actions">
				<div class="header-socials">
					<?php if ( $instagram ) : ?>
						<a class="header-social" href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text">Instagram</span>
							<?php zadzerkalya_icon( 'instagram' ); ?>
						</a>
					<?php else : ?>
						<span class="header-social" aria-hidden="true"><?php zadzerkalya_icon( 'instagram' ); ?></span>
					<?php endif; ?>
					<?php if ( $telegram ) : ?>
						<a class="header-social" href="<?php echo esc_url( $telegram ); ?>" target="_blank" rel="noopener noreferrer">
							<span class="screen-reader-text">Telegram</span>
							<?php zadzerkalya_icon( 'telegram' ); ?>
						</a>
					<?php else : ?>
						<span class="header-social" aria-hidden="true"><?php zadzerkalya_icon( 'telegram' ); ?></span>
					<?php endif; ?>
				</div>
				<?php
				zadzerkalya_button(
					array(
						'label'   => __( 'Звʼяжіться з нами', 'zadzerkalya' ),
						'url'     => $contacts,
						'variant' => 'secondary',
					)
				);
				?>
			</div>
		</div>
	</div>
</header>
