<?php
/**
 * Template Name: Контакти
 *
 * @package Zadzerkalya
 */

get_header();

$phone   = zadzerkalya_theme_mod( 'phone' );
$email   = zadzerkalya_theme_mod( 'email' );
$address = zadzerkalya_theme_mod( 'address' );
$hours   = zadzerkalya_theme_mod( 'hours' );
$map     = get_theme_mod( 'zadzerkalya_map_embed' );
?>
<main id="content" class="site-main">
	<div class="container section contact-layout">
		<article>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>
				<div class="prose">
					<?php the_content(); ?>
				</div>
				<?php
			endwhile;
			?>
			<ul class="contact-list">
				<?php if ( $address ) : ?>
					<li><strong><?php esc_html_e( 'Адреса', 'zadzerkalya' ); ?></strong><span><?php echo esc_html( $address ); ?></span></li>
				<?php endif; ?>
				<?php if ( $phone ) : ?>
					<li><strong><?php esc_html_e( 'Телефон', 'zadzerkalya' ); ?></strong><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<li><strong><?php esc_html_e( 'Email', 'zadzerkalya' ); ?></strong><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
				<?php endif; ?>
				<?php if ( $hours ) : ?>
					<li><strong><?php esc_html_e( 'Години роботи', 'zadzerkalya' ); ?></strong><span><?php echo esc_html( $hours ); ?></span></li>
				<?php endif; ?>
			</ul>
			<?php if ( $map ) : ?>
				<div class="map-embed">
					<?php echo zadzerkalya_sanitize_iframe( $map ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</article>

		<aside class="contact-form-wrap">
			<h2><?php esc_html_e( 'Написати нам', 'zadzerkalya' ); ?></h2>
			<?php zadzerkalya_contact_notice(); ?>
			<form class="contact-form" method="post" action="">
				<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
				<p class="hp-field" aria-hidden="true">
					<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
						<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
					</label>
				</p>
				<p>
					<label for="zadzerkalya_name"><?php esc_html_e( 'Імʼя', 'zadzerkalya' ); ?></label>
					<input id="zadzerkalya_name" name="zadzerkalya_name" type="text" required>
				</p>
				<p>
					<label for="zadzerkalya_email"><?php esc_html_e( 'Email', 'zadzerkalya' ); ?></label>
					<input id="zadzerkalya_email" name="zadzerkalya_email" type="email" required>
				</p>
				<p>
					<label for="zadzerkalya_phone"><?php esc_html_e( 'Телефон', 'zadzerkalya' ); ?></label>
					<input id="zadzerkalya_phone" name="zadzerkalya_phone" type="tel">
				</p>
				<p>
					<label for="zadzerkalya_message"><?php esc_html_e( 'Повідомлення', 'zadzerkalya' ); ?></label>
					<textarea id="zadzerkalya_message" name="zadzerkalya_message" rows="5" required></textarea>
				</p>
				<p>
					<?php
					zadzerkalya_button(
						array(
							'label'   => __( 'Надіслати', 'zadzerkalya' ),
							'variant' => 'primary',
							'type'    => 'submit',
							'name'    => 'zadzerkalya_contact_submit',
							'value'   => '1',
						)
					);
					?>
				</p>
			</form>
		</aside>
	</div>
</main>
<?php
get_footer();
