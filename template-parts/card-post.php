<?php
/**
 * Картка статті блогу.
 *
 * @package Zadzerkalya
 */
?>
<article <?php post_class( 'card' ); ?>>
	<a href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'zadzerkalya-card' );
		}
		?>
		<p class="kicker"><?php echo esc_html( get_the_date() ); ?></p>
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	</a>
</article>
