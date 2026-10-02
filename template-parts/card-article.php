<?php
/**
 * Картка статті (Card/Article): заголовок, фото з лініями, дата, час читання, кнопка «читати».
 * Використовується для подій та статей блогу.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $button_label Текст кнопки.
 * }
 */

$button_label = $args['button_label'] ?? __( 'читати', 'zadzerkalya' );

$event_date = get_post_meta( get_the_ID(), '_zdk_event_date', true );
$timestamp  = $event_date ? strtotime( $event_date ) : false;
$date_text  = $timestamp ? wp_date( 'd/m/Y', $timestamp ) : get_the_date( 'd/m/Y' );
$date_attr  = $timestamp ? wp_date( 'Y-m-d', $timestamp ) : get_the_date( 'Y-m-d' );

$minutes = zadzerkalya_reading_time_minutes();
?>
<article <?php post_class( 'article-card' ); ?>>
	<div class="article-card__content">
		<div class="article-card__main">
			<h3 class="article-card__title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>

			<div class="article-card__media">
				<span class="article-card__line" aria-hidden="true"></span>
				<a class="article-card__photo" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'zadzerkalya-card' );
					} elseif ( function_exists( 'get_field' ) ) {
						// Запасне фото — з hero події / новини.
						zadzerkalya_acf_image( get_field( 'event_hero_photo' ), 'zadzerkalya-card', array( 'alt' => '' ) );
					}
					?>
				</a>
				<span class="article-card__line article-card__line--reverse" aria-hidden="true"></span>
			</div>
		</div>

		<div class="article-card__info">
			<time class="article-card__date" datetime="<?php echo esc_attr( $date_attr ); ?>"><?php echo esc_html( $date_text ); ?></time>
			<span class="article-card__time">
				<svg class="article-card__clock" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true" focusable="false">
					<circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="0.88" />
					<path d="M6 3.96V6H9" stroke="currentColor" stroke-width="0.88" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
				<?php
				/* translators: %d — хвилини читання. */
				echo esc_html( sprintf( _n( '%d хв читання', '%d хв читання', $minutes, 'zadzerkalya' ), $minutes ) );
				?>
			</span>
		</div>
	</div>

	<?php
	zadzerkalya_button(
		array(
			'label'   => $button_label,
			'url'     => get_permalink(),
			'variant' => 'primary',
		)
	);
	?>
</article>
