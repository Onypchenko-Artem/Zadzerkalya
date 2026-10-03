<?php
/**
 * CTA послуги і слайдер ознак.
 *
 * @package Zadzerkalya
 *
 * @var array $args {
 *     @type string $title            Заголовок у колі.
 *     @type string $description      Підпис під заголовком.
 *     @type bool   $show_description Чи показувати підпис.
 *     @type mixed  $illustration     Ілюстрація над заголовком.
 *     @type mixed  $background       Фон картки.
 *     @type string $button_label     Текст кнопки.
 *     @type string $button_url       Посилання кнопки.
 *     @type string $button_variant   primary|secondary.
 *     @type array  $slides           Слайди зі списком рядків.
 * }
 */

$title            = $args['title'] ?? '';
$description      = $args['description'] ?? '';
$show_description = ! empty( $args['show_description'] );
$illustration     = $args['illustration'] ?? null;
$background       = $args['background'] ?? null;
$button_label     = $args['button_label'] ?? '';
$button_url       = $args['button_url'] ?? '';
$button_variant   = $args['button_variant'] ?? 'primary';
$slides           = is_array( $args['slides'] ?? null ) ? $args['slides'] : array();

$show_description = $show_description && '' !== trim( wp_strip_all_tags( (string) $description ) );
$show_button      = '' !== trim( wp_strip_all_tags( (string) $button_label ) );

$prepared_slides = array();
foreach ( $slides as $slide ) {
	$rows = array();
	foreach ( (array) ( $slide['rows'] ?? array() ) as $row ) {
		$row_title = trim( (string) ( $row['title'] ?? '' ) );
		$row_text  = trim( (string) ( $row['description'] ?? '' ) );
		if ( '' === $row_title && '' === $row_text ) {
			continue;
		}

		$show_row_text = ! array_key_exists( 'show_description', $row ) || ! in_array( (string) $row['show_description'], array( '0', '', 'false' ), true );
		$rows[]        = array(
			'title'            => $row_title,
			'description'      => $row_text,
			'show_description' => $show_row_text && '' !== $row_text,
		);
	}

	if ( $rows ) {
		$prepared_slides[] = $rows;
	}
}

$has_content = '' !== trim( wp_strip_all_tags( (string) $title ) ) || $prepared_slides;
if ( ! $has_content && ! $show_button ) {
	return;
}

if ( $has_content && ! $show_button ) {
	$button_label = __( 'забронювати первинну консультацію', 'zadzerkalya' );
	$show_button  = true;
}

if ( $show_button && ! $button_url ) {
	$button_url = zadzerkalya_get_page_url( 'contacts' );
}

$background_url = '';
if ( is_numeric( $background ) ) {
	$background_url = wp_get_attachment_image_url( (int) $background, 'full' );
} elseif ( is_array( $background ) ) {
	$background_url = $background['url'] ?? '';
} elseif ( is_string( $background ) ) {
	$background_url = $background;
}

$section_class = 'service-cta';
$section_style = '';
if ( $background_url ) {
	$section_class .= ' service-cta--custom-background';
	$section_style  = '--service-cta-background: url(' . esc_url( $background_url ) . ');';
}

$slide_count = count( $prepared_slides );
?>
<section class="<?php echo esc_attr( $section_class ); ?>"<?php echo $section_style ? ' style="' . esc_attr( $section_style ) . '"' : ''; ?> data-service-cta>
	<div class="service-cta__panel">
		<div class="service-cta__circle" aria-hidden="true"></div>
		<div class="service-cta__content">
			<?php if ( zadzerkalya_acf_image( $illustration, 'medium', array( 'class' => 'service-cta__illustration', 'alt' => '' ) ) ) : ?>
			<?php endif; ?>

			<?php if ( $title || $show_description ) : ?>
				<div class="service-cta__text">
					<?php if ( $title ) : ?>
						<h2><?php echo wp_kses( nl2br( esc_html( $title ) ), array( 'br' => array() ) ); ?></h2>
					<?php endif; ?>
					<?php if ( $show_description ) : ?>
						<div class="service-cta__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_button ) : ?>
				<?php
				zadzerkalya_button(
					array(
						'label'   => $button_label,
						'url'     => $button_url,
						'variant' => $button_variant,
					)
				);
				?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $prepared_slides ) : ?>
		<div class="service-cta__slider">
			<div class="service-cta__viewport">
				<div class="service-cta__track">
					<?php foreach ( $prepared_slides as $slide_index => $rows ) : ?>
						<div class="service-cta__slide" <?php echo 0 === $slide_index ? '' : 'aria-hidden="true"'; ?>>
							<?php foreach ( $rows as $row ) : ?>
								<article class="service-cta__row">
									<div class="service-cta__row-text">
										<?php if ( $row['title'] ) : ?>
											<h3><?php echo esc_html( $row['title'] ); ?></h3>
										<?php endif; ?>
										<?php if ( $row['show_description'] ) : ?>
											<p><?php echo esc_html( $row['description'] ); ?></p>
										<?php endif; ?>
									</div>
									<img class="service-cta__line" src="<?php echo esc_url( ZADZERKALYA_URI . '/assets/images/service-cta-line.svg' ); ?>" alt="" aria-hidden="true">
								</article>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="service-cta__controls">
				<button class="service-cta__arrow service-cta__arrow--prev" type="button" aria-label="<?php esc_attr_e( 'Попередній слайд', 'zadzerkalya' ); ?>" <?php disabled( $slide_count < 2 ); ?>></button>
				<p class="service-cta__counter" aria-live="polite">
					<span class="service-cta__counter-current">1/</span><span class="service-cta__counter-total"><?php echo esc_html( (string) $slide_count ); ?></span>
				</p>
				<button class="service-cta__arrow service-cta__arrow--next" type="button" aria-label="<?php esc_attr_e( 'Наступний слайд', 'zadzerkalya' ); ?>" <?php disabled( $slide_count < 2 ); ?>></button>
			</div>
		</div>
	<?php endif; ?>
</section>
