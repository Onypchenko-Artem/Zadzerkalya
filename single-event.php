<?php
/**
 * Стаття події.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main">
	<?php
	while ( have_posts() ) :
		the_post();
		$date  = get_post_meta( get_the_ID(), '_zdk_event_date', true );
		$time  = get_post_meta( get_the_ID(), '_zdk_event_time', true );
		$place = get_post_meta( get_the_ID(), '_zdk_event_place', true );
		?>
		<article <?php post_class( 'container section article' ); ?>>
			<header class="page-header">
				<p class="kicker"><?php esc_html_e( 'Подія', 'zadzerkalya' ); ?></p>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<ul class="event-meta">
					<?php if ( $date ) : ?>
						<li><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $date ) ) ); ?></li>
					<?php endif; ?>
					<?php if ( $time ) : ?>
						<li><?php echo esc_html( $time ); ?></li>
					<?php endif; ?>
					<?php if ( $place ) : ?>
						<li><?php echo esc_html( $place ); ?></li>
					<?php endif; ?>
				</ul>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="article-hero">
					<?php the_post_thumbnail( 'zadzerkalya-hero' ); ?>
				</div>
			<?php endif; ?>
			<div class="prose">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
