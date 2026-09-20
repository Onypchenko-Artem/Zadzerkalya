<?php
/**
 * Картка спеціаліста.
 *
 * @package Zadzerkalya
 */

$position = get_post_meta( get_the_ID(), '_zdk_position', true );
?>
<article <?php post_class( 'card card--person' ); ?>>
	<a href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'zadzerkalya-portrait' );
		}
		?>
		<h3><?php the_title(); ?></h3>
		<?php if ( $position ) : ?>
			<p><?php echo esc_html( $position ); ?></p>
		<?php endif; ?>
	</a>
</article>
