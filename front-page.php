<?php
/**
 * Головна сторінка.
 *
 * @package Zadzerkalya
 */

get_header();

$hero_title = zadzerkalya_theme_mod( 'hero_title', get_bloginfo( 'name' ) );
$hero_text  = zadzerkalya_theme_mod( 'hero_text', get_bloginfo( 'description' ) );
$hero_btn   = zadzerkalya_theme_mod( 'hero_btn', __( 'Забронювати первинну консультацію', 'zadzerkalya' ) );
$kicker     = zadzerkalya_theme_mod( 'hero_kicker' );
$hero_id    = (int) get_theme_mod( 'zadzerkalya_hero_image' );
$hero_illustration_id = (int) get_theme_mod( 'zadzerkalya_hero_illustration' );

$specialists = zadzerkalya_query_latest( 'specialist', 4 );
$events = new WP_Query(
	array(
		'post_type'      => 'event',
		'posts_per_page' => 3,
		'meta_key'       => '_zdk_event_date',
		'orderby'        => array(
			'meta_value' => 'DESC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
$blog_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<main id="content" class="site-main">
	<section class="hero">
		<div class="hero-inner">
			<div class="hero-top-lines" aria-hidden="true">
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-banner-short-line.svg' ); ?>" alt="">
				<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-banner-short-line.svg' ); ?>" alt="">
			</div>

			<div class="hero-layout">
				<div class="hero-copy">
					<?php if ( $kicker ) : ?>
						<p class="hero-kicker"><?php echo esc_html( $kicker ); ?></p>
					<?php endif; ?>
					<h1><?php echo esc_html( $hero_title ); ?></h1>

					<div class="hero-details">
						<div class="hero-description">
							<?php if ( $hero_text ) : ?>
								<p><?php echo nl2br( esc_html( $hero_text ) ); ?></p>
							<?php endif; ?>

							<div class="hero-illustration<?php echo $hero_illustration_id ? '' : ' hero-placeholder'; ?>">
								<?php if ( $hero_illustration_id ) : ?>
									<?php echo wp_get_attachment_image( $hero_illustration_id, 'medium' ); ?>
								<?php else : ?>
									<span><?php esc_html_e( 'Ілюстрація', 'zadzerkalya' ); ?></span>
								<?php endif; ?>
							</div>
						</div>

						<form class="hero-form" method="post" action="">
							<?php zadzerkalya_contact_notice(); ?>
							<?php wp_nonce_field( 'zadzerkalya_contact', 'zadzerkalya_contact_nonce' ); ?>
							<input type="hidden" name="zadzerkalya_form_source" value="hero">
							<p class="hp-field" aria-hidden="true">
								<label><?php esc_html_e( 'Сайт', 'zadzerkalya' ); ?>
									<input type="text" name="zadzerkalya_website" tabindex="-1" autocomplete="off">
								</label>
							</p>
							<p class="hero-field">
								<label for="hero-name"><?php esc_html_e( 'Імʼя*', 'zadzerkalya' ); ?></label>
								<input id="hero-name" name="zadzerkalya_name" type="text" autocomplete="name" required>
							</p>
							<p class="hero-field">
								<label for="hero-phone"><?php esc_html_e( 'Телефон*', 'zadzerkalya' ); ?></label>
								<input id="hero-phone" name="zadzerkalya_phone" type="tel" autocomplete="tel" placeholder="+38 (000) 000-00-00" required>
							</p>
							<?php
							zadzerkalya_button(
								array(
									'label'   => $hero_btn,
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

				<div class="hero-divider" aria-hidden="true"></div>

				<div class="hero-media<?php echo $hero_id ? '' : ' hero-placeholder'; ?>">
					<?php if ( $hero_id ) : ?>
						<?php echo wp_get_attachment_image( $hero_id, 'zadzerkalya-hero' ); ?>
					<?php else : ?>
						<span><?php esc_html_e( 'Фото головного банера', 'zadzerkalya' ); ?></span>
					<?php endif; ?>
				</div>
			</div>

			<img class="hero-bottom-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/hero-banner-long-line.svg' ); ?>" alt="" aria-hidden="true">
		</div>
	</section>

	<?php get_template_part( 'template-parts/section', 'service-when' ); ?>
	<?php get_template_part( 'template-parts/section', 'help' ); ?>
	<?php get_template_part( 'template-parts/section', 'services-showcase' ); ?>
	<?php get_template_part( 'template-parts/section', 'numbers' ); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		if ( get_the_content() ) :
			?>
			<section class="section">
				<div class="container prose">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;
	endwhile;
	?>

	<section class="section section--alt">
		<div class="container">
			<div class="section-head">
				<h2><?php esc_html_e( 'Спеціалісти', 'zadzerkalya' ); ?></h2>
				<a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'specialist' ) ); ?>"><?php esc_html_e( 'Усі спеціалісти', 'zadzerkalya' ); ?></a>
			</div>
			<?php if ( $specialists->have_posts() ) : ?>
				<div class="cards-grid cards-grid--4">
					<?php
					while ( $specialists->have_posts() ) :
						$specialists->the_post();
						get_template_part( 'template-parts/card', 'specialist' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<p class="muted"><?php esc_html_e( 'Додайте спеціалістів у адмінці: Спеціалісти → Додати.', 'zadzerkalya' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="section-head">
				<h2><?php esc_html_e( 'Події', 'zadzerkalya' ); ?></h2>
				<a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>"><?php esc_html_e( 'Усі події', 'zadzerkalya' ); ?></a>
			</div>
			<?php if ( $events->have_posts() ) : ?>
				<div class="cards-grid">
					<?php
					while ( $events->have_posts() ) :
						$events->the_post();
						get_template_part( 'template-parts/card', 'event' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<p class="muted"><?php esc_html_e( 'Додайте події в адмінці: Події → Додати.', 'zadzerkalya' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section section--alt">
		<div class="container">
			<div class="section-head">
				<h2><?php esc_html_e( 'Блог', 'zadzerkalya' ); ?></h2>
				<a class="text-link" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'Усі статті', 'zadzerkalya' ); ?></a>
			</div>
			<?php if ( $blog_posts->have_posts() ) : ?>
				<div class="cards-grid">
					<?php
					while ( $blog_posts->have_posts() ) :
						$blog_posts->the_post();
						get_template_part( 'template-parts/card', 'post' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<p class="muted"><?php esc_html_e( 'Додайте записи блогу: Записи → Додати.', 'zadzerkalya' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/section', 'cta' ); ?>
</main>
<?php
get_footer();
