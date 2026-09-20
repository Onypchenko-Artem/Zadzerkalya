<?php
/**
 * Коментарі.
 *
 * @package Zadzerkalya
 */

if ( post_password_required() ) {
	return;
}
?>
<div class="comments">
	<?php if ( have_comments() ) : ?>
		<h2><?php esc_html_e( 'Коментарі', 'zadzerkalya' ); ?></h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply' => __( 'Залишити коментар', 'zadzerkalya' ),
		)
	);
	?>
</div>
