<?php
/**
 * Картка спеціаліста в секції «Наша команда».
 *
 * @package Zadzerkalya
 */

$position  = get_post_meta( get_the_ID(), '_zdk_position', true );
$education = get_post_meta( get_the_ID(), '_zdk_education', true );
?>
<article <?php post_class( 'specialist-slot' ); ?>>
	<div class="specialist-slot__link">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php
			the_post_thumbnail(
				'zadzerkalya-portrait',
				array(
					'class' => 'specialist-slot__photo',
					'alt'   => get_the_title(),
				)
			);
			?>
		<?php else : ?>
			<span class="specialist-slot__photo specialist-slot__photo--empty" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></span>
		<?php endif; ?>

		<div class="specialist-slot__text">
			<div class="specialist-slot__name">
				<h3><?php the_title(); ?></h3>
				<svg class="specialist-slot__line specialist-slot__line--desktop" viewBox="0 0 371 6" fill="none" aria-hidden="true">
					<path d="M0.5 5.21957C0.790866 5.21957 1.08173 5.21957 26.725 5.32974C52.3683 5.43991 103.355 5.66026 133.706 5.30995C164.057 4.95964 172.227 4.032 181.215 3.44104C199.673 2.22749 213.497 2.60439 220.702 3.0745C235.879 4.06486 255.629 5.04658 267.391 3.56722C291.955 0.477699 287.941 2.98032 293.904 3.34218C302.334 3.85383 314.947 0.248897 331.806 0.513956C339.585 0.636266 352.89 3.2751 370.5 5.0629" stroke="currentColor" stroke-linecap="round" />
				</svg>
				<svg class="specialist-slot__line specialist-slot__line--tablet" viewBox="0 0 296 5" fill="none" aria-hidden="true">
					<path d="M0.5 4.27565C0.731906 4.27565 0.963812 4.27565 21.4091 4.36379L21.5725 4.3645C42.0703 4.45286 82.5704 4.62746 106.705 4.34796C130.904 4.06771 137.417 3.3256 144.584 2.85283C159.3 1.88199 170.322 2.18351 176.066 2.5596C188.167 3.35189 203.914 4.13726 213.292 2.95378C232.877 0.482159 229.676 2.48426 234.43 2.77374C241.151 3.18306 251.208 0.299118 264.649 0.511165C270.852 0.609013 281.459 2.72008 295.5 4.15032" stroke="currentColor" stroke-linecap="round" />
				</svg>
			</div>
			<div class="specialist-slot__info">
				<?php if ( $position ) : ?>
					<p><?php echo esc_html( $position ); ?></p>
				<?php endif; ?>
				<?php if ( $education ) : ?>
					<p><?php echo esc_html( sprintf( __( 'Освіта: %s', 'zadzerkalya' ), $education ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
