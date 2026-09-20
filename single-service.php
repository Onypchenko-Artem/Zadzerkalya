<?php
/**
 * Сторінка послуги.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$price    = zadzerkalya_service_price_html();
		$duration = get_post_meta( get_the_ID(), '_zdk_duration', true );
		$note     = get_post_meta( get_the_ID(), '_zdk_price_note', true );
		?>
		<article <?php post_class( 'container section profile' ); ?>>
			<div class="profile-media">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'zadzerkalya-card' );
				}
				?>
			</div>
			<div class="profile-body">
				<p class="kicker"><?php esc_html_e( 'Послуга', 'zadzerkalya' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php echo get_the_term_list( get_the_ID(), 'service_type', '<p class="tags">', ', ', '</p>' ); ?>
				<div class="price-box">
					<?php if ( $price ) : ?>
						<p class="price"><?php echo esc_html( $price ); ?></p>
					<?php endif; ?>
					<?php if ( $duration ) : ?>
						<p><?php echo esc_html( $duration ); ?></p>
					<?php endif; ?>
					<?php if ( $note ) : ?>
						<p class="muted"><?php echo esc_html( $note ); ?></p>
					<?php endif; ?>
				</div>
				<div class="prose">
					<?php the_content(); ?>
				</div>
				<p>
					<?php
					zadzerkalya_button(
						array(
							'label'   => __( 'Записатися', 'zadzerkalya' ),
							'url'     => zadzerkalya_get_page_url( 'contacts' ),
							'variant' => 'primary',
						)
					);
					?>
					<a class="text-link" href="<?php echo esc_url( zadzerkalya_get_page_url( 'prices' ) ); ?>"><?php esc_html_e( 'Дивитися вартість послуг', 'zadzerkalya' ); ?></a>
				</p>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
