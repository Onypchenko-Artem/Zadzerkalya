<?php
/**
 * Картка події.
 *
 * @package Zadzerkalya
 */

$date = get_post_meta( get_the_ID(), '_zdk_event_date', true );
?>
<article <?php post_class( 'card' ); ?>>
	<a href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'zadzerkalya-card' );
		}
		?>
		<?php if ( $date ) : ?>
			<p class="kicker"><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $date ) ) ); ?></p>
		<?php endif; ?>
		<h3><?php the_title(); ?></h3>
		<?php if ( has_excerpt() ) : ?>
			<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
	</a>
</article>
