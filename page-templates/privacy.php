<?php
/**
 * Template Name: Політика конфіденційності
 *
 * Банер як у статті. Зміст зліва збирається з заголовків тексту,
 * дата дії — справа (поле «Діє з»).
 *
 * У редакторі:
 * - перший H2 — назва документа в колонці тексту (у зміст не входить);
 * - H3 — пункти змісту;
 * - наступний H2 — окремий документ у тому ж тексті (наприклад, згода), він є в змісті;
 * - цитата, код і моноширинний абзац — як у статті блогу;
 * - виділення — група з класом policy-callout;
 * - вкладені пункти — група з класом policy-sub;
 * - контакти — група з класом policy-contacts: перший абзац-заголовок і список визначень (Адреса / значення);
 * - маркований список — як у статті, у рамці.
 *
 * @package Zadzerkalya
 */

get_header();
?>
<main id="content" class="site-main policy-page">
	<?php
	while ( have_posts() ) :
		the_post();

		$field = static function ( $name, $fallback = '' ) {
			$value = function_exists( 'get_field' ) ? get_field( $name ) : null;

			return ( null === $value || false === $value || '' === $value ) ? $fallback : $value;
		};

		$hero_title       = $field( 'policy_hero_title', get_the_title() );
		$hero_description = $field( 'policy_hero_description' );
		$hero_photo       = $field( 'policy_hero_photo', get_post_thumbnail_id() );
		$hero_background  = $field( 'policy_hero_background' );
		$effective_raw    = $field( 'policy_effective_date' );
		$effective_date   = '';

		if ( $effective_raw ) {
			$timestamp      = strtotime( (string) $effective_raw );
			$effective_date = $timestamp ? wp_date( 'd.m.Y', $timestamp ) : (string) $effective_raw;
		}

		get_template_part(
			'template-parts/section',
			'event-hero',
			array(
				'title'       => $hero_title,
				'description' => $hero_description,
				'photo'       => $hero_photo,
				'background'  => $hero_background,
				'current'     => get_the_title(),
				'crumb_label' => '',
				'heading_id'  => 'policy-hero-title',
			)
		);

		get_template_part(
			'template-parts/section',
			'policy',
			array(
				'effective_date' => $effective_date,
			)
		);

		get_template_part(
			'template-parts/section',
			'form-home',
			array(
				'title'        => __( 'Кожен день — важливий!', 'zadzerkalya' ),
				'description'  => __( 'Зробіть перший крок на шляху до розвитку вашої дитини — запишіться на первинну консультацію вже зараз!', 'zadzerkalya' ),
				'button_label' => __( 'забронювати первинну консультацію', 'zadzerkalya' ),
				'source'       => 'contacts',
			)
		);
	endwhile;
	?>
</main>
<?php
get_footer();
