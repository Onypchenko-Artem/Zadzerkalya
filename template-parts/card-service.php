<?php
/**
 * Картка послуги.
 *
 * @package Zadzerkalya
 */

$price = zadzerkalya_service_price_html();
?>
<article <?php post_class( 'card' ); ?>>
	<a href="<?php the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'zadzerkalya-card' );
		}
		?>
		<h3><?php the_title(); ?></h3>
		<?php if ( has_excerpt() ) : ?>
			<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		<?php if ( $price ) : ?>
			<p class="price"><?php echo esc_html( $price ); ?></p>
		<?php endif; ?>
	</a>
</article>
