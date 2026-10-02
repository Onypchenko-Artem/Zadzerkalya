<?php
/**
 * Переваги звернення до центру.
 *
 * @package Zadzerkalya
 */

$default_benefits = array(
	__( 'Відсутність вікових обмежень для дітей.', 'zadzerkalya' ),
	__( 'Сучасні діагностичні методики.', 'zadzerkalya' ),
	__( 'Залучення батьків і родини до процесу.', 'zadzerkalya' ),
	__( 'Співпраця з благодійними фондами, робота по 309 постанові.', 'zadzerkalya' ),
	__( 'Комплексний підхід до розвитку та реабілітації.', 'zadzerkalya' ),
	__( 'Команда фахівців різних напрямів в одному центрі.', 'zadzerkalya' ),
	__( 'Індивідуальна програма роботи для кожної дитини.', 'zadzerkalya' ),
	__( 'Обладнані кабінети та комфортний простір для занять.', 'zadzerkalya' ),
	__( 'Регулярний зворотний зв’язок щодо результатів роботи.', 'zadzerkalya' ),
	__( 'Підтримка родини на кожному етапі розвитку дитини.', 'zadzerkalya' ),
);

$title   = zadzerkalya_field( 'benefits_title', __( 'Переваги звернення до центру «Задзеркалля»', 'zadzerkalya' ) );
$text    = zadzerkalya_field( 'benefits_text', __( 'Ми створюємо простір, де є все для розвитку дитини — від унікальних методик до турботливих сердець фахівців!', 'zadzerkalya' ) );
$image   = zadzerkalya_field( 'benefits_image' );
$button  = zadzerkalya_field( 'benefits_button', __( 'забронювати первинну консультацію', 'zadzerkalya' ) );
$url     = zadzerkalya_field( 'benefits_button_url', zadzerkalya_get_page_url( 'contacts' ) );
$benefits = zadzerkalya_field_lines( 'benefits_items' ) ?: $default_benefits;
?>
<div class="benefits-scroll" data-pinned-list>
<section class="benefits" aria-labelledby="benefits-title" data-scroll-section>
	<h2 id="benefits-title"><?php echo esc_html( $title ); ?></h2>

	<div class="benefits__content">
		<div class="benefits__intro">
			<p><?php echo esc_html( $text ); ?></p>

			<div class="benefits__image-placeholder">
				<?php if ( ! zadzerkalya_acf_image( $image, 'medium', array( 'alt' => '' ) ) ) : ?>
					<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/rabbit.png' ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
			</div>

			<?php
			zadzerkalya_button(
				array(
					'label'   => $button,
					'url'     => $url,
					'variant' => 'primary',
				)
			);
			?>
		</div>

		<div class="benefits__list" data-scroll-list>
			<ol class="benefits__track" data-scroll-track>
				<?php foreach ( $benefits as $index => $benefit ) : ?>
					<li class="benefits__item">
						<div class="benefits__number" aria-hidden="true">
							<span class="benefits__number-line benefits__number-line--left"></span>
							<span><?php echo esc_html( $index + 1 ); ?></span>
							<span class="benefits__number-line"></span>
						</div>
						<p><?php echo esc_html( $benefit ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
</div>
