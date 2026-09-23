<?php
/**
 * Template Name: Про нас
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main about-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="about-hero" aria-labelledby="about-hero-title">
			<div class="about-hero__inner">
				<div class="about-hero__copy">
					<div class="about-hero__heading">
						<nav class="about-hero__breadcrumbs" aria-label="<?php esc_attr_e( 'Навігаційний ланцюжок', 'zadzerkalya' ); ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Головна', 'zadzerkalya' ); ?></a>
							<svg viewBox="0 0 20 12" aria-hidden="true">
								<path d="M1 6h17M13 1l5 5-5 5" />
							</svg>
							<span aria-current="page"><?php esc_html_e( 'Про нас', 'zadzerkalya' ); ?></span>
						</nav>

						<h1 id="about-hero-title">
							<?php
							echo wp_kses(
								__( 'Дізнайтеся більше про центр<br>психології та логопедії<br>«Задзеркалля»', 'zadzerkalya' ),
								array( 'br' => array() )
							);
							?>
						</h1>
					</div>

					<div class="about-hero__footer">
						<p><?php esc_html_e( 'Кожна велика історія починається з рішення і волі однієї людини', 'zadzerkalya' ); ?></p>
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-left-line.svg' ); ?>" alt="" aria-hidden="true">
					</div>
				</div>

				<img class="about-hero__vertical-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-vertical-line.svg' ); ?>" alt="" aria-hidden="true">

				<div class="about-hero__visual" aria-hidden="true">
					<div class="about-hero__scene">
						<img class="about-hero__alice" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/alice.png' ); ?>" alt="">
						<img class="about-hero__rabbit" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/rabbit.png' ); ?>" alt="">
					</div>
					<img class="about-hero__right-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-right-line.svg' ); ?>" alt="">
				</div>
			</div>
		</section>

		<section class="about-story" aria-labelledby="about-story-title">
			<div class="about-story__inner">
				<figure class="about-story__person">
					<div class="about-story__photo-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото засновниці буде додано', 'zadzerkalya' ); ?>">
						<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
					</div>
					<figcaption>
						<strong><?php esc_html_e( 'Плєхова Софія Володимирівна', 'zadzerkalya' ); ?></strong>
						<span><?php esc_html_e( 'Засновниця центру, психолог, АВА-терапевт, спеціаліст з терапії TOMATIS.', 'zadzerkalya' ); ?></span>
					</figcaption>
				</figure>

				<div class="about-story__body">
					<div class="about-story__illustration-placeholder" role="img" aria-label="<?php esc_attr_e( 'Ілюстрацію буде додано', 'zadzerkalya' ); ?>">
						<span><?php esc_html_e( 'Ілюстрація', 'zadzerkalya' ); ?></span>
					</div>

					<div class="about-story__text">
						<div class="about-story__intro">
							<h2 id="about-story-title"><?php esc_html_e( 'Історія, що почалася з любові — і стала місією. Моя дорога до «Задзеркалля»', 'zadzerkalya' ); ?></h2>
							<div class="about-story__lead">
								<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/top-line.svg' ); ?>" alt="" aria-hidden="true">
								<p><?php esc_html_e( 'Мій шлях у професію почався не з теорії, а з самого життя — з реальних історій дітей, які потребували більше, аніж просто стандартних рішень. І від моменту мого знайомства з ТІММІ, хто сприймає наш світ інакше, я зрозуміла, що хочу бути поруч — і допомагати їм знайти у цьому світі шлях свій, унікальний.', 'zadzerkalya' ); ?></p>
								<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/bottom-line.svg' ); ?>" alt="" aria-hidden="true">
							</div>
						</div>

						<div class="about-story__columns">
							<div>
								<p><?php esc_html_e( 'Мої перші кроки у корекційній психології були непростими: глибоке занурення у науку, нескінченні години навчання, пошук найефективніших методів роботи…', 'zadzerkalya' ); ?></p>
								<p><?php esc_html_e( 'Але найголовніше — це діти, з якими я працювала. Їхні батьки та їхні історії.', 'zadzerkalya' ); ?></p>
								<p><?php esc_html_e( 'Коли я бачила, як кожного дня маленькі перемоги на очах перетворюються на великі досягнення.', 'zadzerkalya' ); ?></p>
								<p><?php esc_html_e( 'Як ті, кого ще вчора вважали «проблемними», починали вибудовувати свій контакт з цим світом, розуміти його та ставати більш зрозумілими для нього. Як очі батьків наповнювалися радістю та новою надією — адже нарешті вони могли почути свою дитину!', 'zadzerkalya' ); ?></p>
							</div>
							<div>
								<p><?php esc_html_e( 'Саме це і стало моїм головним поштовхом до відкриття свого реабілітаційного центру. Я хотіла створити місце, де кожна дитина буде побаченою та зрозумілою, де головним інструментом роботи будуть не стандартні протоколи, а їх адаптація до реальних потреб кожного нашого клієнта. Де кожен з батьків відчує себе прийнятим у своїй особливій історії.', 'zadzerkalya' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="about-mission">
			<div class="about-mission__inner">
				<p><?php esc_html_e( 'Так і зʼявилося «Задзеркалля» — простір, у якому дітки з особливостями розвитку отримують підтримку, необхідні навички, можливість стати самостійнішими та щасливішими у цьому світі.', 'zadzerkalya' ); ?></p>
				<div class="about-mission__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото центру буде додано', 'zadzerkalya' ); ?>">
					<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
				</div>
			</div>
		</section>

		<section class="about-relocation">
			<div class="about-relocation__inner">
				<div class="about-relocation__content">
					<svg class="about-relocation__scribble" viewBox="0 0 143 102" fill="none" aria-hidden="true">
						<path d="M3 54C17 31 62 20 85 35C109 51 79 76 47 69C14 62 4 38 27 19C48 2 103 8 118 31C135 58 92 91 54 84C19 78 12 44 39 29C67 14 129 31 139 55M8 18C34 32 60 54 80 99M37 4C48 32 57 62 55 96M74 3C66 36 65 72 79 101M105 15C88 38 75 59 68 87" />
					</svg>

					<div class="about-relocation__text">
						<p><?php esc_html_e( 'Життя внесло свої корективи — і у 2022 році наш центр пережив непростий період переїзду до Львова, за сотні кілометрів від дому. Це був справжній виклик — залишити все створене за багато років — і створити з самого початку. І ми його прийняли.', 'zadzerkalya' ); ?></p>
						<p><?php esc_html_e( 'Тут ми не просто адаптувалися — ми виросли! Львів став для нас новим домом, місцем, де ми продовжили нашу місію. Тут ми зібрали ще більше фахівців, розширили наш простір в декілька разів, запровадили нові підходи, знайшли однодумців та зустріли так багато довіри та підтримки, які рухають нас вперед кожного дня!', 'zadzerkalya' ); ?></p>
					</div>
				</div>

				<svg class="about-relocation__decor" viewBox="0 0 488 344" fill="none" aria-hidden="true">
					<path d="M1 301C57 232 121 243 166 188C198 149 184 100 212 44" />
					<path d="M69 326C128 265 196 283 246 222C284 176 272 114 302 41" />
					<path d="M157 343C209 308 264 293 303 247C350 192 367 128 386 43" />
					<path d="M445 17C450 1 470 -3 478 10C487 27 464 38 458 47C453 36 437 31 445 17Z" />
				</svg>
			</div>
		</section>

		<section class="about-values">
			<div class="about-values__inner">
				<div class="about-values__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото інтерʼєру центру буде додано', 'zadzerkalya' ); ?>">
					<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
				</div>

				<div class="about-values__content">
					<p class="about-values__highlight"><?php esc_html_e( 'Для мене важливо не лише допомагати дітям, а й створювати середовище, в якому зростають і фахівці. Я завжди піклуюся про свою команду, адже вірю, що турбота про співробітників — це запорука якісної роботи. Ми постійно вчимося, обговорюємо складні випадки, підтримуємо один одного. Я хочу, аби кожен у «Задзеркаллі» відчував себе не просто частиною команди — а частиною великої справи.', 'zadzerkalya' ); ?></p>

					<div class="about-values__columns">
						<div>
							<p><?php esc_html_e( 'А ще я розумію: допомагаючи дітям, ми робимо більше, аніж просто терапію — ми формуємо майбутнє.', 'zadzerkalya' ); ?></p>
							<p><?php esc_html_e( 'Адже кожен маленький крок до самостійності однієї дитини — це крок до більш інклюзивного суспільства. Ми змінюємо не лише життя окремих сімей, а й саму культуру ставлення до дітей із особливими потребами.', 'zadzerkalya' ); ?></p>
						</div>

						<div class="about-values__conclusion">
							<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/top-line.svg' ); ?>" alt="" aria-hidden="true">
							<div>
								<p><?php esc_html_e( 'Сьогодні «Задзеркалля» — це не просто реабілітаційний центр.', 'zadzerkalya' ); ?></p>
								<p><?php esc_html_e( 'Це місце, де змінюються долі, де страх поступається надії, а неможливе стає можливим через любов, прийняття та віру у кожну дитину.', 'zadzerkalya' ); ?></p>
							</div>
							<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/bottom-line.svg' ); ?>" alt="" aria-hidden="true">
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="about-ending" aria-label="<?php esc_attr_e( 'Завершення історії', 'zadzerkalya' ); ?>">
			<div class="about-ending__inner">
				<div class="about-ending__rabbit" aria-hidden="true">
					<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/rabbit.png' ); ?>" alt="">
					<svg viewBox="0 0 184 27" fill="none">
						<path d="M1 25C39 20 65 21 91 23C119 25 146 19 183 24" />
					</svg>
				</div>

				<div class="about-ending__message">
					<img class="about-ending__curve-line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/about-curve-line.svg' ); ?>" alt="" aria-hidden="true">
					<svg class="about-ending__curve-text" viewBox="0 0 1730 224" aria-hidden="true">
						<defs>
							<path id="about-ending-text-path" transform="translate(34 89)" d="M0.5 134.198C0.912385 134.003 27.2233 116.123 115.051 77.9394C174.466 52.1082 273.35 35.0461 382.609 20.8298C491.868 6.61347 610.891 1.35171 686.115 0.593521C761.338 -0.164669 789.154 3.74016 824.335 10.3826C859.516 17.025 901.217 26.2867 935.249 32.1868C994.025 42.3769 1029.04 44.8475 1065.97 57.1952C1094.35 66.6866 1141.25 85.4667 1173.84 95.8919C1222.11 111.333 1247.19 111.898 1295.69 113.931C1337.08 115.666 1412.63 115.555 1458.28 114.343C1517.6 112.767 1544.83 99.8607 1569.27 86.7266C1620.12 59.4809 1673.01 37.2528 1682.55 33.746C1686.99 32.2198 1690.63 31.2081 1695.47 30.1658" />
						</defs>
						<text dy="-20" textLength="1690" lengthAdjust="spacing">
							<textPath href="#about-ending-text-path" startOffset="0"><?php esc_html_e( 'ЦЕ НАША ІСТОРІЯ — І ВОНА ТІЛЬКИ ПОЧИНАЄТЬСЯ!', 'zadzerkalya' ); ?></textPath>
						</text>
					</svg>
				</div>
			</div>
		</section>

		<?php if ( trim( get_the_content() ) ) : ?>
			<article <?php post_class( 'about-content container section' ); ?>>
				<div class="prose">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>
		<?php
	endwhile;

	$specialists = zadzerkalya_query_latest( 'specialist', 4 );
	if ( $specialists->have_posts() ) :
		?>
		<section class="section section--alt">
			<div class="container">
				<div class="section-head">
					<h2><?php esc_html_e( 'Команда', 'zadzerkalya' ); ?></h2>
					<a class="text-link" href="<?php echo esc_url( get_post_type_archive_link( 'specialist' ) ); ?>"><?php esc_html_e( 'Усі спеціалісти', 'zadzerkalya' ); ?></a>
				</div>
				<div class="cards-grid cards-grid--4">
					<?php
					while ( $specialists->have_posts() ) :
						$specialists->the_post();
						get_template_part( 'template-parts/card', 'specialist' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
