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
		$position   = get_post_meta( get_the_ID(), '_zdk_position', true );
		$experience = get_post_meta( get_the_ID(), '_zdk_experience', true );
		$education  = get_post_meta( get_the_ID(), '_zdk_education', true );
		$phone      = get_post_meta( get_the_ID(), '_zdk_phone', true );
		$email      = get_post_meta( get_the_ID(), '_zdk_email', true );
		?>
		<article <?php post_class( 'container section profile' ); ?>>
			<div class="profile-media">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'zadzerkalya-portrait' );
				}
				?>
			</div>
			<div class="profile-body">
				<p class="kicker"><?php esc_html_e( 'Спеціаліст', 'zadzerkalya' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( $position ) : ?>
					<p class="lead"><?php echo esc_html( $position ); ?></p>
				<?php endif; ?>
				<?php echo get_the_term_list( get_the_ID(), 'specialization', '<p class="tags">', ', ', '</p>' ); ?>
				<div class="meta-list">
					<?php if ( $experience ) : ?>
						<p><strong><?php esc_html_e( 'Досвід:', 'zadzerkalya' ); ?></strong> <?php echo esc_html( $experience ); ?></p>
					<?php endif; ?>
					<?php if ( $education ) : ?>
						<p><strong><?php esc_html_e( 'Освіта:', 'zadzerkalya' ); ?></strong> <?php echo esc_html( $education ); ?></p>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
				</div>
				<div class="prose">
					<?php the_content(); ?>
				</div>
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
