<?php
/**
 * Текст політики: зміст, колонка документа з назвою (h2 — h1 вже в банері) і датою дії.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $effective_date Дата у форматі d.m.Y.
 * }
 */

$effective_date = $args['effective_date'] ?? '';
$prepared       = zadzerkalya_policy_prepare_content( apply_filters( 'the_content', get_the_content() ) );
$toc            = $prepared['toc'];
$title          = '' !== $prepared['title'] ? $prepared['title'] : get_the_title();
?>
<section class="policy">
	<?php if ( $toc ) : ?>
		<nav class="policy__toc" aria-label="<?php esc_attr_e( 'Зміст', 'zadzerkalya' ); ?>">
			<p class="policy__toc-title"><?php esc_html_e( 'Зміст', 'zadzerkalya' ); ?></p>
			<span class="policy__toc-line" aria-hidden="true"></span>
			<?php foreach ( $toc as $index => $item ) : ?>
				<a class="policy__toc-link" href="<?php echo esc_url( '#' . $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
				<?php if ( $index < count( $toc ) - 1 ) : ?>
					<span class="policy__toc-line" aria-hidden="true"></span>
				<?php endif; ?>
			<?php endforeach; ?>
			<span class="policy__toc-line" aria-hidden="true"></span>
		</nav>
	<?php endif; ?>

	<div class="policy__main">
		<header class="policy__head">
			<h2 class="policy__title"><?php echo esc_html( $title ); ?></h2>
			<?php if ( $effective_date ) : ?>
				<p class="policy__date">
					<?php esc_html_e( 'Діє з:', 'zadzerkalya' ); ?>
					<time><?php echo esc_html( $effective_date ); ?></time>
				</p>
			<?php endif; ?>
		</header>

		<article class="policy__body event-article__body">
			<?php echo $prepared['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</article>
	</div>
</section>
