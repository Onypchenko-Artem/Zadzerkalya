<?php
/**
 * Картка спеціаліста в секції «Наша команда».
 *
 * @package Zadzerkalya
 */

$position  = get_post_meta( get_the_ID(), '_zdk_position', true );
$education = get_post_meta( get_the_ID(), '_zdk_education', true );
?>
<article <?php post_class( 'specialist-slot' ); ?>>
	<a class="specialist-slot__link" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php
			the_post_thumbnail(
				'zadzerkalya-portrait',
				array(
					'class' => 'specialist-slot__photo',
					'alt'   => get_the_title(),
				)
			);
			?>
		<?php else : ?>
			<span class="specialist-slot__photo specialist-slot__photo--empty" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></span>
		<?php endif; ?>

		<div class="specialist-slot__text">
			<h3><?php the_title(); ?></h3>
			<div class="specialist-slot__info">
				<?php if ( $position ) : ?>
					<p><?php echo esc_html( $position ); ?></p>
				<?php endif; ?>
				<?php if ( $education ) : ?>
					<p><?php echo esc_html( sprintf( __( 'Освіта: %s', 'zadzerkalya' ), $education ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</a>
</article>
