<?php
/**
 * Текст політики: зміст, колонка документа, дата дії.
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

	<article class="policy__body event-article__body">
		<?php echo $prepared['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</article>

	<?php if ( $effective_date ) : ?>
		<aside class="policy__aside">
			<dl class="policy__meta">
				<div class="policy__meta-row">
					<dt><?php esc_html_e( 'Діє з:', 'zadzerkalya' ); ?></dt>
					<dd><?php echo esc_html( $effective_date ); ?></dd>
				</div>
			</dl>
		</aside>
	<?php endif; ?>
</section>
