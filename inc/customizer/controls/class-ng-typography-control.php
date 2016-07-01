<?php

/**
 * Customizer Typography Control
 *
 * Taken from Kirki.
 *
 * @package   theme-slug
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class NG_Typography_Control extends WP_Customize_Control {

	public $tooltip = '';
	public $js_vars = array();
	public $output = array();
	public $option_type = 'theme_mod';
	public $type = 'ng_typography';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}
		$this->json['js_vars'] = $this->js_vars;
		$this->json['output']  = $this->output;
		$this->json['value']   = $this->value();
		$this->json['choices'] = $this->choices;
		$this->json['link']    = $this->get_link();
		$this->json['tooltip'] = $this->tooltip;
		$this->json['id']      = $this->id;
		$this->json['l10n']    = apply_filters( 'ng-typography-control/il8n/strings', array(
			'on'                 => esc_attr__( 'ON', 'theme-slug' ),
			'off'                => esc_attr__( 'OFF', 'theme-slug' ),
			'all'                => esc_attr__( 'All', 'theme-slug' ),
			'cyrillic'           => esc_attr__( 'Cyrillic', 'theme-slug' ),
			'cyrillic-ext'       => esc_attr__( 'Cyrillic Extended', 'theme-slug' ),
			'devanagari'         => esc_attr__( 'Devanagari', 'theme-slug' ),
			'greek'              => esc_attr__( 'Greek', 'theme-slug' ),
			'greek-ext'          => esc_attr__( 'Greek Extended', 'theme-slug' ),
			'khmer'              => esc_attr__( 'Khmer', 'theme-slug' ),
			'latin'              => esc_attr__( 'Latin', 'theme-slug' ),
			'latin-ext'          => esc_attr__( 'Latin Extended', 'theme-slug' ),
			'vietnamese'         => esc_attr__( 'Vietnamese', 'theme-slug' ),
			'hebrew'             => esc_attr__( 'Hebrew', 'theme-slug' ),
			'arabic'             => esc_attr__( 'Arabic', 'theme-slug' ),
			'bengali'            => esc_attr__( 'Bengali', 'theme-slug' ),
			'gujarati'           => esc_attr__( 'Gujarati', 'theme-slug' ),
			'tamil'              => esc_attr__( 'Tamil', 'theme-slug' ),
			'telugu'             => esc_attr__( 'Telugu', 'theme-slug' ),
			'thai'               => esc_attr__( 'Thai', 'theme-slug' ),
			'serif'              => _x( 'Serif', 'font style', 'theme-slug' ),
			'sans-serif'         => _x( 'Sans Serif', 'font style', 'theme-slug' ),
			'monospace'          => _x( 'Monospace', 'font style', 'theme-slug' ),
			'font-family'        => esc_attr__( 'Font Family', 'theme-slug' ),
			'font-size'          => esc_attr__( 'Font Size', 'theme-slug' ),
			'font-weight'        => esc_attr__( 'Font Weight', 'theme-slug' ),
			'line-height'        => esc_attr__( 'Line Height', 'theme-slug' ),
			'font-style'         => esc_attr__( 'Font Style', 'theme-slug' ),
			'letter-spacing'     => esc_attr__( 'Letter Spacing', 'theme-slug' ),
			'text-align'         => esc_attr__( 'Text Align', 'theme-slug' ),
			'text-transform'     => esc_attr__( 'Text Transform', 'theme-slug' ),
			'none'               => esc_attr__( 'None', 'theme-slug' ),
			'uppercase'          => esc_attr__( 'Uppercase', 'theme-slug' ),
			'lowercase'          => esc_attr__( 'Lowercase', 'theme-slug' ),
			'top'                => esc_attr__( 'Top', 'theme-slug' ),
			'bottom'             => esc_attr__( 'Bottom', 'theme-slug' ),
			'left'               => esc_attr__( 'Left', 'theme-slug' ),
			'right'              => esc_attr__( 'Right', 'theme-slug' ),
			'center'             => esc_attr__( 'Center', 'theme-slug' ),
			'justify'            => esc_attr__( 'Justify', 'theme-slug' ),
			'color'              => esc_attr__( 'Color', 'theme-slug' ),
			'select-font-family' => esc_attr__( 'Select a font-family', 'theme-slug' ),
			'variant'            => esc_attr__( 'Variant', 'theme-slug' ),
			'style'              => esc_attr__( 'Style', 'theme-slug' ),
			'size'               => esc_attr__( 'Size', 'theme-slug' ),
			'height'             => esc_attr__( 'Height', 'theme-slug' ),
			'spacing'            => esc_attr__( 'Spacing', 'theme-slug' ),
			'ultra-light'        => esc_attr__( 'Ultra-Light 100', 'theme-slug' ),
			'ultra-light-italic' => esc_attr__( 'Ultra-Light 100 Italic', 'theme-slug' ),
			'light'              => esc_attr__( 'Light 200', 'theme-slug' ),
			'light-italic'       => esc_attr__( 'Light 200 Italic', 'theme-slug' ),
			'book'               => esc_attr__( 'Book 300', 'theme-slug' ),
			'book-italic'        => esc_attr__( 'Book 300 Italic', 'theme-slug' ),
			'regular'            => esc_attr__( 'Normal 400', 'theme-slug' ),
			'italic'             => esc_attr__( 'Normal 400 Italic', 'theme-slug' ),
			'medium'             => esc_attr__( 'Medium 500', 'theme-slug' ),
			'medium-italic'      => esc_attr__( 'Medium 500 Italic', 'theme-slug' ),
			'semi-bold'          => esc_attr__( 'Semi-Bold 600', 'theme-slug' ),
			'semi-bold-italic'   => esc_attr__( 'Semi-Bold 600 Italic', 'theme-slug' ),
			'bold'               => esc_attr__( 'Bold 700', 'theme-slug' ),
			'bold-italic'        => esc_attr__( 'Bold 700 Italic', 'theme-slug' ),
			'extra-bold'         => esc_attr__( 'Extra-Bold 800', 'theme-slug' ),
			'extra-bold-italic'  => esc_attr__( 'Extra-Bold 800 Italic', 'theme-slug' ),
			'ultra-bold'         => esc_attr__( 'Ultra-Bold 900', 'theme-slug' ),
			'ultra-bold-italic'  => esc_attr__( 'Ultra-Bold 900 Italic', 'theme-slug' ),
			'invalid-value'      => esc_attr__( 'Invalid Value', 'theme-slug' ),
		) );

		$defaults = array(
			'font-family'    => false,
			'font-size'      => false,
			'line-height'    => false,
			'letter-spacing' => false,
			'color'          => false,
			'text-align'     => false,
			'text-transform' => false
		);

		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/*
		 * Styles
		 */

		wp_enqueue_style( 'ng-typography', get_template_directory_uri() . '/inc/customizer/css/typography' . $suffix . '.css', array(), false );

		/*
		 * JavaScript
		 */

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-stepper-min-js' );
		wp_enqueue_style( 'wp-color-picker' );

		// Selectize
		wp_enqueue_script( 'selectize', get_template_directory_uri() . '/inc/customizer/js/selectize' . $suffix . '.js', array( 'jquery' ), false, true );

		// Typography
		wp_enqueue_script( 'ng-typography-control', get_template_directory_uri() . '/inc/customizer/js/control-typography' . $suffix . '.js', array(
			'jquery',
			'selectize'
		), false, true );

		$google_fonts   = NG_Fonts::get_google_fonts();
		$standard_fonts = NG_Fonts::get_standard_fonts();
		$all_variants   = NG_Fonts::get_all_variants();

		$standard_fonts_final = array();
		foreach ( $standard_fonts as $key => $value ) {
			$standard_fonts_final[] = array(
				'family'      => $value['stack'],
				'label'       => $value['label'],
				'is_standard' => true,
				'variants'    => array(
					array(
						'id'    => 'regular',
						'label' => $all_variants['regular'],
					),
					array(
						'id'    => 'italic',
						'label' => $all_variants['italic'],
					),
					array(
						'id'    => '700',
						'label' => $all_variants['700'],
					),
					array(
						'id'    => '700italic',
						'label' => $all_variants['700italic'],
					),
				),
			);
		}

		$google_fonts_final = array();

		if ( is_array( $google_fonts ) ) {
			foreach ( $google_fonts as $family => $args ) {
				$label    = ( isset( $args['label'] ) ) ? $args['label'] : $family;
				$variants = ( isset( $args['variants'] ) ) ? $args['variants'] : array( 'regular', '700' );

				$available_variants = array();
				foreach ( $variants as $variant ) {
					if ( array_key_exists( $variant, $all_variants ) ) {
						$available_variants[] = array( 'id' => $variant, 'label' => $all_variants[ $variant ] );
					}
				}

				$google_fonts_final[] = array(
					'family'   => $family,
					'label'    => $label,
					'variants' => $available_variants
				);
			}
		}

		$final = array_merge( $standard_fonts_final, $google_fonts_final );
		wp_localize_script( 'ng-typography-control', 'ngAllFonts', $final );
	}

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
	 *
	 * Supports basic input types `text`, `checkbox`, `textarea`, `radio`, `select` and `dropdown-pages`.
	 * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
	 *
	 * Control content can alternately be rendered in JS. See {@see WP_Customize_Control::print_template()}.
	 *
	 * @access public
	 * @return void
	 */
	public function render_content() {

		// intentionally empty
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * I put this in a separate file because PhpStorm didn't like it and it fucked with my formatting.
	 *
	 * @see    WP_Customize_Control::print_template()
	 *
	 * @access protected
	 * @return void
	 */
	protected function content_template() {
		include dirname( __FILE__ ) . '/typography-control-template.php';
	}

}