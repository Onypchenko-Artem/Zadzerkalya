<?php
/**
 * Відгуки батьків.
 *
 * @package Zadzerkalya
 */

$reviews = array(
	array(
		'name' => 'Lena Kruhlikova',
		'text' => __( 'Хочу висловити щиру подяку логопеду-дефектологу Марії центру «Задзеркалля» за професійну роботу та турботливе ставлення. Ми звернулися, коли моєму синові було 5 років 10 місяців із труднощами у вимові звука «Р». Завдяки знанням, терпінню та індивідуальному підходу Марії вже за короткий час вдалося отримати чудовий результат.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Юля Борисевич', 'zadzerkalya' ),
		'text' => __( 'Центр «Задзеркалля» — це місце, де дітей не просто навчають, а з любов’ю допомагають розкривати потенціал. Ось уже рік, як наша донечка відвідує ЛФК, і ми безмежно вдячні спеціалісту Денису — з першого заняття він знайшов підхід, підтримує, мотивує та робить кожну зустріч корисною і комфортною.', 'zadzerkalya' ),
	),
	array(
		'name' => 'Oksana Kolesnyk',
		'text' => __( 'Доброго дня, хочу залишити свої враження від нашого перебування у вашому чудовому закладі. Це справді неймовірне місце для діток, мій синочок із радістю відвідував усі заняття, які давали йому ваші спеціалісти. Тому хочу щиро подякувати вам, а особливо Олесі — дефектологу.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Наталія Коваль', 'zadzerkalya' ),
		'text' => __( 'Дякуємо команді центру за уважність, людяність і професіоналізм. Дитина із задоволенням іде на заняття, а ми бачимо впевнені зміни та завжди отримуємо зрозумілі рекомендації для домашньої роботи.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Анна Мельник', 'zadzerkalya' ),
		'text' => __( 'Дуже вдячні за індивідуальний підхід і теплу атмосферу. Спеціалісти вміють зацікавити дитину, підтримати батьків і перетворити складну роботу на захопливий процес із помітним результатом.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Ірина Савчук', 'zadzerkalya' ),
		'text' => __( 'У центрі ми відчули справжню командну роботу. Фахівці різних напрямів спілкуються між собою, пояснюють кожен крок і разом допомагають дитині впевнено рухатися вперед.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Марина Левченко', 'zadzerkalya' ),
		'text' => __( 'Щиро рекомендуємо «Задзеркалля». Тут уважно ставляться до потреб дитини, не поспішають і водночас послідовно працюють над цілями. Дякуємо за терпіння та підтримку.', 'zadzerkalya' ),
	),
	array(
		'name' => __( 'Олена Романюк', 'zadzerkalya' ),
		'text' => __( 'З першої консультації стало зрозуміло, що ми потрапили до професіоналів. Отримали чіткий план, відповіді на всі запитання і найважливіше — віру в можливості нашої дитини.', 'zadzerkalya' ),
	),
);
$total = count( $reviews );
?>
<section class="reviews" aria-labelledby="reviews-title" data-reviews>
	<div class="reviews__heading">
		<h2 id="reviews-title"><?php esc_html_e( 'Історії батьків, які надихають', 'zadzerkalya' ); ?></h2>
		<p><?php esc_html_e( 'Слова тих, чиї діти вже отримали результат разом з нами — реальні відгуки, що дарують надію та підтримку.', 'zadzerkalya' ); ?></p>
	</div>

	<div class="reviews__content">
		<div class="reviews__controls">
			<button type="button" class="reviews__arrow reviews__arrow--prev" aria-label="<?php esc_attr_e( 'Попередні відгуки', 'zadzerkalya' ); ?>"></button>
			<button type="button" class="reviews__arrow reviews__arrow--next" aria-label="<?php esc_attr_e( 'Наступні відгуки', 'zadzerkalya' ); ?>"></button>
		</div>

		<div class="reviews__viewport">
			<div class="reviews__track">
				<?php foreach ( $reviews as $index => $review ) : ?>
					<article class="reviews__card">
						<header class="reviews__card-header reviews__card-header--<?php echo esc_attr( array( 'green', 'orange', 'pink' )[ $index % 3 ] ); ?>">
							<span class="reviews__counter"><strong><?php echo esc_html( $index + 1 ); ?>/</strong><?php echo esc_html( $total ); ?></span>
							<h3><?php echo esc_html( $review['name'] ); ?></h3>
						</header>
						<span class="reviews__line" aria-hidden="true"></span>
						<p class="reviews__text"><?php echo esc_html( $review['text'] ); ?></p>
						<span class="reviews__line reviews__line--reverse" aria-hidden="true"></span>
						<button
							type="button"
							class="reviews__more"
							aria-expanded="false"
							data-collapsed-label="<?php esc_attr_e( 'читати далі', 'zadzerkalya' ); ?>"
							data-expanded-label="<?php esc_attr_e( 'згорнути', 'zadzerkalya' ); ?>"
						>
							<span><?php esc_html_e( 'читати далі', 'zadzerkalya' ); ?></span>
							<i aria-hidden="true"></i>
						</button>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
