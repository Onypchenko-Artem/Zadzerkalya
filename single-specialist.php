<?php
/**
 * Сторінка спеціаліста.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$position  = get_post_meta( get_the_ID(), '_zdk_position', true );
		$education = get_post_meta( get_the_ID(), '_zdk_education', true );
		?>
		<article <?php post_class( 'container section profile' ); ?>>
			<div class="profile-media">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail(
						'zadzerkalya-portrait',
						array(
							'alt' => get_the_title(),
						)
					);
				}
				?>
			</div>
			<div class="profile-body">
				<p class="kicker"><?php esc_html_e( 'Спеціаліст', 'zadzerkalya' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( $position ) : ?>
					<p class="lead"><?php echo esc_html( $position ); ?></p>
				<?php endif; ?>
				<?php if ( $education ) : ?>
					<div class="meta-list">
						<p><strong><?php esc_html_e( 'Освіта:', 'zadzerkalya' ); ?></strong> <?php echo nl2br( esc_html( $education ) ); ?></p>
					</div>
				<?php endif; ?>
				<?php
				zadzerkalya_button(
					array(
						'label'   => __( 'Записатися', 'zadzerkalya' ),
						'url'     => zadzerkalya_get_page_url( 'contacts' ),
						'variant' => 'primary',
					)
				);
				?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
