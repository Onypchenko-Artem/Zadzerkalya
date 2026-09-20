<?php
/**
 * Переваги звернення до центру.
 *
 * @package Zadzerkalya
 */

$benefits = array(
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
?>
<section class="benefits" aria-labelledby="benefits-title">
	<h2 id="benefits-title"><?php esc_html_e( 'Переваги звернення до центру «Задзеркалля»', 'zadzerkalya' ); ?></h2>

	<div class="benefits__content">
		<div class="benefits__intro">
			<p><?php esc_html_e( 'Ми створюємо простір, де є все для розвитку дитини — від унікальних методик до турботливих сердець фахівців!', 'zadzerkalya' ); ?></p>

			<div class="benefits__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Місце для ілюстрації', 'zadzerkalya' ); ?>">
				<span><?php esc_html_e( 'Ілюстрація', 'zadzerkalya' ); ?></span>
			</div>

			<?php
			zadzerkalya_button(
				array(
					'label'   => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
					'url'     => zadzerkalya_get_page_url( 'contacts' ),
					'variant' => 'primary',
				)
			);
			?>
		</div>

		<ol class="benefits__list">
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
</section>
