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

		$about_field = static function( $name, $fallback = '' ) {
			$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		$hero_title   = $about_field( 'about_hero_title', "Дізнайтеся більше про центр\nпсихології та логопедії\n«Задзеркалля»" );
		$hero_quote   = $about_field( 'about_hero_quote', 'Кожна велика історія починається з рішення і волі однієї людини' );
		$hero_scene   = $about_field( 'about_hero_scene' );
		$hero_background = $about_field( 'about_hero_background' );
		$story_photo  = $about_field( 'about_story_founder_photo' );
		$story_name   = $about_field( 'about_story_founder_name', 'Плєхова Софія Володимирівна' );
		$story_role   = $about_field( 'about_story_founder_role', 'Засновниця центру, психолог, АВА-терапевт, спеціаліст з терапії TOMATIS.' );
		$story_art    = $about_field( 'about_story_illustration' );
		$story_title  = $about_field( 'about_story_title', 'Історія, що почалася з любові — і стала місією. Моя дорога до «Задзеркалля»' );
		$story_lead   = $about_field( 'about_story_lead', 'Мій шлях у професію почався не з теорії, а з самого життя — з реальних історій дітей, які потребували більше, аніж просто стандартних рішень. І від моменту мого знайомства з ТІММІ, хто сприймає наш світ інакше, я зрозуміла, що хочу бути поруч — і допомагати їм знайти у цьому світі шлях свій, унікальний.' );
		$mission_text = $about_field( 'about_mission_text', 'Так і зʼявилося «Задзеркалля» — простір, у якому дітки з особливостями розвитку отримують підтримку, необхідні навички, можливість стати самостійнішими та щасливішими у цьому світі.' );
		$mission_img  = $about_field( 'about_mission_image' );
		$relocation_1 = $about_field( 'about_relocation_first', 'Життя внесло свої корективи — і у 2022 році наш центр пережив непростий період переїзду до Львова, за сотні кілометрів від дому. Це був справжній виклик — залишити все створене за багато років — і створити з самого початку. І ми його прийняли.' );
		$relocation_2 = $about_field( 'about_relocation_second', 'Тут ми не просто адаптувалися — ми виросли! Львів став для нас новим домом, місцем, де ми продовжили нашу місію. Тут ми зібрали ще більше фахівців, розширили наш простір в декілька разів, запровадили нові підходи, знайшли однодумців та зустріли так багато довіри та підтримки, які рухають нас вперед кожного дня!' );
		$values_img   = $about_field( 'about_values_image' );
		$values_highlight = $about_field( 'about_values_highlight', 'Для мене важливо не лише допомагати дітям, а й створювати середовище, в якому зростають і фахівці. Я завжди піклуюся про свою команду, адже вірю, що турбота про співробітників — це запорука якісної роботи. Ми постійно вчимося, обговорюємо складні випадки, підтримуємо один одного. Я хочу, аби кожен у «Задзеркаллі» відчував себе не просто частиною команди — а частиною великої справи.' );
		$ending_rabbit = $about_field( 'about_ending_rabbit' );
		$ending_message = $about_field( 'about_ending_message', 'ЦЕ НАША ІСТОРІЯ — І ВОНА ТІЛЬКИ ПОЧИНАЄТЬСЯ!' );
		$gallery_title = $about_field( 'about_gallery_title', 'Галерея' );
		$team_title    = $about_field( 'about_team_title', 'Команда' );
		$team_link     = $about_field( 'about_team_link_label', 'Усі спеціалісти' );

		$story_left = $about_field(
			'about_story_left_column',
			array(
				array( 'about_story_left_text' => 'Мої перші кроки у корекційній психології були непростими: глибоке занурення у науку, нескінченні години навчання, пошук найефективніших методів роботи…' ),
				array( 'about_story_left_text' => 'Але найголовніше — це діти, з якими я працювала. Їхні батьки та їхні історії.' ),
				array( 'about_story_left_text' => 'Коли я бачила, як кожного дня маленькі перемоги на очах перетворюються на великі досягнення.' ),
				array( 'about_story_left_text' => 'Як ті, кого ще вчора вважали «проблемними», починали вибудовувати свій контакт з цим світом, розуміти його та ставати більш зрозумілими для нього. Як очі батьків наповнювалися радістю та новою надією — адже нарешті вони могли почути свою дитину!' ),
			)
		);
		$story_right = $about_field( 'about_story_right_column', array( array( 'about_story_right_text' => 'Саме це і стало моїм головним поштовхом до відкриття свого реабілітаційного центру. Я хотіла створити місце, де кожна дитина буде побаченою та зрозумілою, де головним інструментом роботи будуть не стандартні протоколи, а їх адаптація до реальних потреб кожного нашого клієнта. Де кожен з батьків відчує себе прийнятим у своїй особливій історії.' ) ) );
		$values_left = $about_field( 'about_values_left', array( array( 'about_values_left_text' => 'А ще я розумію: допомагаючи дітям, ми робимо більше, аніж просто терапію — ми формуємо майбутнє.' ), array( 'about_values_left_text' => 'Адже кожен маленький крок до самостійності однієї дитини — це крок до більш інклюзивного суспільства. Ми змінюємо не лише життя окремих сімей, а й саму культуру ставлення до дітей із особливими потребами.' ) ) );
		$values_conclusion = $about_field( 'about_values_conclusion', array( array( 'about_values_conclusion_text' => 'Сьогодні «Задзеркалля» — це не просто реабілітаційний центр.' ), array( 'about_values_conclusion_text' => 'Це місце, де змінюються долі, де страх поступається надії, а неможливе стає можливим через любов, прийняття та віру у кожну дитину.' ) ) );
		?>
		<?php
		get_template_part(
			'template-parts/section',
			'about-hero',
			array(
				'title'      => $hero_title,
				'quote'      => $hero_quote,
				'scene'      => $hero_scene,
				'background' => $hero_background,
				'current'    => __( 'Про нас', 'zadzerkalya' ),
				'heading_id' => 'about-hero-title',
			)
		);
		?>

		<section class="about-story" aria-labelledby="about-story-title">
			<div class="about-story__inner">
				<figure class="about-story__person">
					<?php if ( $story_photo ) : ?>
						<?php zadzerkalya_acf_image( $story_photo, 'large', array( 'class' => 'about-story__photo' ) ); ?>
					<?php else : ?>
						<div class="about-story__photo-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото засновниці буде додано', 'zadzerkalya' ); ?>">
							<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
						</div>
					<?php endif; ?>
					<figcaption>
						<strong><?php echo esc_html( $story_name ); ?></strong>
						<span><?php echo esc_html( $story_role ); ?></span>
					</figcaption>
				</figure>

				<div class="about-story__body">
					<?php if ( $story_art ) : ?>
						<?php zadzerkalya_acf_image( $story_art, 'large', array( 'class' => 'about-story__illustration' ) ); ?>
					<?php else : ?>
						<div class="about-story__illustration-placeholder" role="img" aria-label="<?php esc_attr_e( 'Ілюстрацію буде додано', 'zadzerkalya' ); ?>">
							<span><?php esc_html_e( 'Ілюстрація', 'zadzerkalya' ); ?></span>
						</div>
					<?php endif; ?>

					<div class="about-story__text">
						<div class="about-story__intro">
							<h2 id="about-story-title"><?php echo esc_html( $story_title ); ?></h2>
							<div class="about-story__lead">
								<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/story-lead-line.svg' ); ?>" alt="" aria-hidden="true">
								<p><?php echo esc_html( $story_lead ); ?></p>
								<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/story-lead-line.svg' ); ?>" alt="" aria-hidden="true">
							</div>
						</div>

						<div class="about-story__columns">
							<div>
								<?php foreach ( $story_left as $row ) : ?>
									<p><?php echo esc_html( $row['about_story_left_text'] ?? '' ); ?></p>
								<?php endforeach; ?>
							</div>
							<div>
								<?php foreach ( $story_right as $row ) : ?>
									<p><?php echo esc_html( $row['about_story_right_text'] ?? '' ); ?></p>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="about-mission">
			<div class="about-mission__inner">
				<p><?php echo esc_html( $mission_text ); ?></p>
				<?php if ( $mission_img ) : ?>
					<?php zadzerkalya_acf_image( $mission_img, 'large', array( 'class' => 'about-mission__image' ) ); ?>
				<?php else : ?>
					<div class="about-mission__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото центру буде додано', 'zadzerkalya' ); ?>">
						<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<section class="about-relocation">
			<div class="about-relocation__inner">
				<div class="about-relocation__content">
					<svg class="about-relocation__scribble" viewBox="0 0 144 103" fill="none" aria-hidden="true">
						<path d="M89.3866 25.255C93.3604 29.9395 102.651 57.1346 101.673 85.9121C101.293 97.0657 97.7749 100.972 90.7818 102.143C72.805 105.153 47.6034 88.7891 24.5684 67.1572C14.0986 57.3251 9.45391 46.5238 15.3113 37.4447C21.1687 28.3657 38.5251 20.4939 52.0834 19.6122C65.6416 18.7306 74.8759 25.0775 81.6988 32.3831C88.5218 39.6888 92.6536 47.7608 90.9762 55.6731C89.2988 63.5855 81.687 71.0936 75.789 64.1288C69.8911 57.164 65.9375 35.4988 66.9222 33.1992C67.9068 30.8996 73.9494 48.6221 72.3727 62.8832C70.796 77.1443 61.417 87.4069 58.603 89.1698C44.7113 97.8722 66.0962 43.3994 60.8772 27.683C55.7979 12.387 121.912 37.9353 133.667 44.6933C182.607 72.8282 33.0305 47.0446 31.4708 44.9324C14.9868 22.609 68.7049 84.5629 67.1125 93.8543C66.3575 98.2594 59.1339 99.505 53.2223 99.4203C47.3108 99.3356 41.249 97.2361 38.8485 80.9174C36.448 64.5988 37.8926 34.1247 35.7847 21.2598C33.6768 8.39497 27.9727 14.0628 31.1626 21.7574C34.3526 29.452 46.6094 39.0016 41.7006 43.0022C36.7918 47.0027 14.3459 45.1649 11.3684 45.8833C-36.0368 57.3209 87.1036 89.7266 85.1727 84.0125C82.2315 75.3084 60.3926 64.3381 58.0879 51.8854C55.7832 39.4327 69.8615 28.2633 74.9783 20.536C80.0951 12.8086 75.8238 8.86169 70.9525 5.43943C66.0811 2.01717 60.739 -0.760859 65.7269 2.51092C85.8586 15.7163 106.303 30.2474 111.225 43.175C117.367 59.3073 111.644 73.9056 111.968 79.6037C112.272 84.9256 91.729 81.7689 87.6394 81.8571C83.5498 81.9454 91.1348 81.9454 88.4852 80.5007C85.8356 79.056 72.7216 76.1666 63.2429 65.1975C53.7642 54.2284 48.3183 35.267 49.4025 26.5265C50.4867 17.7859 58.266 19.8406 68.9581 26.9502C79.6502 34.0599 93.0193 46.1621 93.5297 53.3789C94.61 68.6535 56.2085 64.242 50.1368 64.7053C44.2327 65.1559 80.0357 67.4741 86.6479 69.5932C93.26 71.7124 81.7236 73.9279 71.3864 72.2239C61.0491 70.52 52.2608 64.8294 51.4679 52.9052C50.6751 40.981 58.144 22.9956 63.1708 11.9315C68.1977 0.867453 70.5562 -2.73023 82.9162 3.64247C95.2762 10.0152 117.566 26.4673 119.917 36.0346C124.639 55.2575 58.528 49.71 23.4113 50.7478C18.5254 50.8922 45.688 68.1518 54.7443 81.8821C56.9019 85.1532 44.2927 83.4936 42.2083 82.3819C46.4368 81.4148 58.9615 83.7246 68.3808 83.28C71.7797 80.6744 72.3745 73.2369 84.3961 65.574" />
					</svg>

					<div class="about-relocation__text">
						<p><?php echo esc_html( $relocation_1 ); ?></p>
						<p><?php echo esc_html( $relocation_2 ); ?></p>
					</div>
				</div>

				<svg class="about-relocation__decor" viewBox="0 0 488 344" fill="none" aria-hidden="true">
					<path d="M1 302C1 298.196 2.12145 288.018 10.8048 274.418C20.7575 258.831 45.5439 252.474 73.0173 244.811C112.489 233.801 142.107 230.759 151.226 226.504C168.522 218.434 182.98 199.628 205.358 160.851C216.999 140.678 232.613 129.288 262.846 115.404C280.709 107.2 298.925 104.137 310.759 95.3625C315.645 91.255 318.259 87.8482 322.088 79.2724C325.918 70.6966 330.884 57.055 336 43" />
					<path d="M69 326C69 325.597 69.8086 321.892 72.1142 315.517C74.2295 309.668 86.4471 303.444 109.229 292.767C142.309 277.262 175.297 271.024 193.3 267.371C202.208 265.564 207.63 262.572 215.046 257.64C224.181 251.565 238.263 229.278 255.99 200.261C274.536 169.902 298.081 163.59 314.554 160.241C336.727 155.734 356.946 153.332 369.872 138.219C377.563 127.915 387.553 112.259 396.52 95.0982C405.486 77.937 413.127 59.7441 421 41" />
					<path d="M157 343C161.284 341.575 180.723 331.123 221.392 301.625C242.234 286.507 267.683 279.443 290.273 274.037C305.619 270.365 316.408 270.026 327.365 263.097C334.558 258.548 342.715 248.638 354.065 230.382C365.416 212.126 379.112 184.988 396.709 160.757C414.306 136.526 435.389 116.025 447.194 103.004C459 89.9829 460.888 85.064 462.219 76.2606C463.549 67.4573 464.264 54.9186 465 42" />
					<path d="M463.307 7.71851C458.514 5.09007 453.721 2.46164 450.537 3.09564C447.352 3.72965 445.921 7.70574 446.003 11.2059C446.086 14.7061 447.725 17.6098 449.52 19.6306C451.315 21.6513 453.215 22.701 456.063 23.983C458.911 25.2651 462.649 26.7475 469 30" />
					<path d="M465 7.1431C465 7.03675 466.131 5.38503 469 2.73474C472.16 -0.185012 478.021 1.13488 482.664 2.67089C484.894 3.40849 486.125 5.70634 486.957 7.70979C487.491 15.4756 482.993 23.075 477.731 30.0603C475.514 32.6541 474.216 33.317 472.878 34" />
				</svg>
			</div>
		</section>

		<section class="about-values">
			<div class="about-values__inner">
				<?php if ( $values_img ) : ?>
					<?php zadzerkalya_acf_image( $values_img, 'large', array( 'class' => 'about-values__image' ) ); ?>
				<?php else : ?>
					<div class="about-values__image-placeholder" role="img" aria-label="<?php esc_attr_e( 'Фото інтерʼєру центру буде додано', 'zadzerkalya' ); ?>">
						<span><?php esc_html_e( 'Фото буде додано', 'zadzerkalya' ); ?></span>
					</div>
				<?php endif; ?>

				<div class="about-values__content">
					<p class="about-values__highlight"><?php echo esc_html( $values_highlight ); ?></p>

					<div class="about-values__columns">
						<div>
							<?php foreach ( $values_left as $row ) : ?>
								<p><?php echo esc_html( $row['about_values_left_text'] ?? '' ); ?></p>
							<?php endforeach; ?>
						</div>

						<div class="about-values__conclusion">
							<svg class="about-values__conclusion-line" viewBox="0 0 437 8" fill="none" aria-hidden="true">
								<path d="M0.5 4.84647C1.4087 4.84647 2.31741 4.84647 40.0186 3.61925C77.7197 2.39203 152.186 -0.0624191 217.782 0.616668C283.378 1.29575 337.848 5.18275 370.124 6.69372C402.401 8.20469 410.834 7.22185 417.301 6.71553C423.767 6.20922 428.011 6.20922 430.877 6.30742C433.742 6.40562 435.101 6.60202 436.5 6.80437" />
							</svg>
							<div>
								<?php foreach ( $values_conclusion as $row ) : ?>
									<p><?php echo esc_html( $row['about_values_conclusion_text'] ?? '' ); ?></p>
								<?php endforeach; ?>
							</div>
							<svg class="about-values__conclusion-line about-values__conclusion-line--bottom" viewBox="0 0 437 8" fill="none" aria-hidden="true">
								<path d="M0.5 4.84647C1.4087 4.84647 2.31741 4.84647 40.0186 3.61925C77.7197 2.39203 152.186 -0.0624191 217.782 0.616668C283.378 1.29575 337.848 5.18275 370.124 6.69372C402.401 8.20469 410.834 7.22185 417.301 6.71553C423.767 6.20922 428.011 6.20922 430.877 6.30742C433.742 6.40562 435.101 6.60202 436.5 6.80437" />
							</svg>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="about-ending" aria-label="<?php esc_attr_e( 'Завершення історії', 'zadzerkalya' ); ?>">
			<div class="about-ending__inner">
				<div class="about-ending__rabbit" aria-hidden="true">
					<?php
					if ( $ending_rabbit ) {
						zadzerkalya_acf_image( $ending_rabbit, 'medium', array( 'alt' => '' ) );
					} else {
						?>
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/rabbit.png' ); ?>" alt="">
						<?php
					}
					?>
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
							<textPath href="#about-ending-text-path" startOffset="0"><?php echo esc_html( $ending_message ); ?></textPath>
						</text>
					</svg>
				</div>
			</div>
		</section>

		<?php
		$gallery_images = array();
		$gallery_items  = $about_field( 'about_gallery_items', array() );

		if ( is_array( $gallery_items ) ) {
			foreach ( $gallery_items as $gallery_item ) {
				$image_id = isset( $gallery_item['image'] ) ? (int) $gallery_item['image'] : 0;
				$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

				if ( $image_url ) {
					$gallery_images[] = array(
						'src' => $image_url,
						'alt' => $gallery_item['alt'] ?? '',
					);
				}
			}
		}

		if ( ! $gallery_images ) {
			$gallery_images = array_fill(
				0,
				6,
				array(
					'src' => ZADZERKALYA_URI . '/assets/images/team.jpg',
					'alt' => __( 'Команда центру «Задзеркалля»', 'zadzerkalya' ),
				)
			);
		}
		?>
		<section class="about-gallery" data-gallery aria-labelledby="about-gallery-title">
			<h2 id="about-gallery-title"><?php echo esc_html( $gallery_title ); ?></h2>

			<div class="about-gallery__cards">
				<div class="about-gallery__controls" aria-label="<?php esc_attr_e( 'Керування галереєю', 'zadzerkalya' ); ?>">
					<button class="about-gallery__arrow about-gallery__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Попередні фото', 'zadzerkalya' ); ?>">
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/left-arrow.svg' ); ?>" alt="">
					</button>
					<button class="about-gallery__arrow about-gallery__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Наступні фото', 'zadzerkalya' ); ?>">
						<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/right-arrow.svg' ); ?>" alt="">
					</button>
				</div>

				<div class="about-gallery__viewport" tabindex="0">
					<div class="about-gallery__track">
						<?php foreach ( $gallery_images as $image ) : ?>
							<figure class="about-gallery__card">
								<div class="about-gallery__image">
									<img src="<?php echo esc_url( $image['src'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
								</div>
								<figcaption class="about-gallery__actions" aria-hidden="true">
									<div>
										<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/like.svg' ); ?>" alt="">
										<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/comment.svg' ); ?>" alt="">
										<img src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/share.svg' ); ?>" alt="">
									</div>
									<img class="about-gallery__bookmark" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/save.svg' ); ?>" alt="">
								</figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

		<?php
		get_template_part(
			'template-parts/section',
			'form-home',
			array(
				'title'        => $about_field( 'about_form_title', 'Кожен день — важливий!' ),
				'description'  => $about_field( 'about_form_text', 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!' ),
				'button_label' => $about_field( 'about_form_button', 'забронювати первинну консультацію' ),
				'source'       => 'about',
			)
		);
		?>

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
					<h2><?php echo esc_html( $team_title ); ?></h2>
					<a class="text-link" href="<?php echo esc_url( zadzerkalya_get_specialists_url() ); ?>"><?php echo esc_html( $team_link ); ?></a>
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
