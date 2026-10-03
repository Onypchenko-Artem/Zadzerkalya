<?php
/**
 * Стаття окремої події / новини: автор, текст, метадані.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type mixed  $author        Запис спеціаліста (WP_Post, id) або порожньо.
 *     @type string $date          Дата (Y-m-d).
 *     @type string $time          Час події.
 *     @type string $place         Місце.
 *     @type string $price         Вартість.
 *     @type string $reading_time  Час прочитання (текст для виводу).
 *     @type bool   $share         Показати блок «Поширити статтю».
 *     @type string $button_label  Текст кнопки (порожньо — без кнопки).
 *     @type string $button_url    Посилання кнопки.
 * }
 */

$author_raw    = $args['author'] ?? null;
$date          = $args['date'] ?? '';
$time          = $args['time'] ?? '';
$place         = $args['place'] ?? '';
$price         = $args['price'] ?? '';
$reading_time  = $args['reading_time'] ?? '';
$show_share    = ! empty( $args['share'] );
$button_label  = $args['button_label'] ?? '';
$button_url    = $args['button_url'] ?? '';

$share_url   = $show_share ? get_permalink() : '';
$share_title = $show_share ? get_the_title() : '';
$share_telegram = $share_url ? 'https://t.me/share/url?url=' . rawurlencode( $share_url ) . '&text=' . rawurlencode( $share_title ) : '';
$share_facebook = $share_url ? 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $share_url ) : '';

$author = null;
if ( $author_raw instanceof WP_Post ) {
	$author = $author_raw;
} elseif ( is_numeric( $author_raw ) ) {
	$author = get_post( (int) $author_raw );
} elseif ( is_array( $author_raw ) && ! empty( $author_raw['ID'] ) ) {
	$author = get_post( (int) $author_raw['ID'] );
}

$author_name     = $author ? get_the_title( $author ) : '';
$author_position = $author ? get_post_meta( $author->ID, '_zdk_position', true ) : '';
$author_url      = $author ? get_permalink( $author ) : '';

$date_display = '';
if ( $date ) {
	$timestamp = strtotime( $date );
	$date_display = $timestamp ? wp_date( 'd/m/Y', $timestamp ) : $date;
} else {
	$date_display = get_the_date( 'd/m/Y' );
}

$meta_rows = array();
if ( $date_display ) {
	$meta_rows[] = array(
		'label' => __( 'Дата публікації:', 'zadzerkalya' ),
		'value' => $date_display,
	);
}
if ( $reading_time ) {
	$meta_rows[] = array(
		'label' => __( 'Час прочитання:', 'zadzerkalya' ),
		'value' => $reading_time,
	);
}
if ( $time ) {
	$meta_rows[] = array(
		'label' => __( 'Час події:', 'zadzerkalya' ),
		'value' => $time,
	);
}
if ( $place ) {
	$meta_rows[] = array(
		'label' => __( 'Місце:', 'zadzerkalya' ),
		'value' => $place,
	);
}
if ( $price ) {
	$meta_rows[] = array(
		'label' => __( 'Вартість:', 'zadzerkalya' ),
		'value' => $price,
	);
}
?>
<section class="event-article">
	<?php if ( $author_name ) : ?>
		<aside class="event-article__author">
			<?php
			$author_photo = $author ? get_the_post_thumbnail( $author, 'zadzerkalya-portrait', array( 'alt' => $author_name ) ) : '';
			?>
			<?php if ( $author_url ) : ?>
				<a class="event-article__author-photo" href="<?php echo esc_url( $author_url ); ?>">
					<?php
					if ( $author_photo ) {
						echo $author_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '<span class="event-article__author-photo-empty" aria-hidden="true"></span>';
					}
					?>
				</a>
			<?php else : ?>
				<div class="event-article__author-photo">
					<?php
					if ( $author_photo ) {
						echo $author_photo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '<span class="event-article__author-photo-empty" aria-hidden="true"></span>';
					}
					?>
				</div>
			<?php endif; ?>

			<div class="event-article__author-info">
				<?php if ( $author_url ) : ?>
					<a class="event-article__author-name" href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
				<?php else : ?>
					<p class="event-article__author-name"><?php echo esc_html( $author_name ); ?></p>
				<?php endif; ?>

				<div class="event-article__author-text">
					<span class="event-article__rule" aria-hidden="true"></span>
					<?php if ( $author_position ) : ?>
						<p class="event-article__author-position"><?php echo esc_html( $author_position ); ?></p>
						<span class="event-article__rule" aria-hidden="true"></span>
					<?php endif; ?>
					<p class="event-article__author-role"><?php esc_html_e( 'автор статті', 'zadzerkalya' ); ?></p>
					<span class="event-article__rule" aria-hidden="true"></span>
				</div>
			</div>
		</aside>
	<?php endif; ?>

	<article class="event-article__body">
		<?php the_content(); ?>

		<?php
		$excerpt = has_excerpt() ? get_post_field( 'post_excerpt', get_the_ID() ) : '';
		if ( $excerpt ) :
			?>
			<div class="event-article__excerpt">
				<?php echo wp_kses_post( wpautop( $excerpt ) ); ?>
			</div>
			<?php
		endif;
		?>
	</article>

	<aside class="event-article__aside">
		<?php if ( $show_share ) : ?>
			<div class="event-article__info">
		<?php endif; ?>

		<?php if ( $meta_rows ) : ?>
			<dl class="event-article__meta">
				<?php foreach ( $meta_rows as $row ) : ?>
					<div class="event-article__meta-row">
						<dt><?php echo esc_html( $row['label'] ); ?></dt>
						<dd><?php echo esc_html( $row['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>

		<?php if ( $show_share && $share_url ) : ?>
			<div class="event-article__share">
				<p class="event-article__share-label"><?php esc_html_e( 'Поширити статтю:', 'zadzerkalya' ); ?></p>
				<div class="event-article__share-links">
					<div class="event-article__share-copy">
						<button type="button" class="event-article__share-link" data-copy-url="<?php echo esc_url( $share_url ); ?>" data-copied="<?php esc_attr_e( 'Посилання скопійовано', 'zadzerkalya' ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Скопіювати посилання', 'zadzerkalya' ); ?></span>
							<?php zadzerkalya_icon( 'link' ); ?>
						</button>
						<span class="event-article__share-toast" role="status" aria-live="polite"></span>
					</div>
					<a class="event-article__share-link" href="<?php echo esc_url( $share_telegram ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="screen-reader-text"><?php esc_html_e( 'Поділитися в Telegram', 'zadzerkalya' ); ?></span>
						<?php zadzerkalya_icon( 'telegram-header' ); ?>
					</a>
					<a class="event-article__share-link" href="<?php echo esc_url( $share_facebook ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="screen-reader-text"><?php esc_html_e( 'Поділитися у Facebook', 'zadzerkalya' ); ?></span>
						<?php zadzerkalya_icon( 'facebook' ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $show_share ) : ?>
			</div>
		<?php endif; ?>

		<?php
		if ( $button_label && $button_url ) {
			zadzerkalya_button(
				array(
					'label'   => $button_label,
					'url'     => $button_url,
					'variant' => 'primary',
				)
			);
		}
		?>
	</aside>
</section>
