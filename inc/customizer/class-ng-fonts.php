<?php

/**
 * Fonts
 *
 * Used for compiling the standard and Google fonts, along with all
 * the variants (weights).
 *
 * Taken from Kirki.
 *
 * @package   bookstagram
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

if ( class_exists( 'NG_Fonts' ) ) {
	return;
}

final class NG_Fonts {

	/**
	 * One instance of NG_Fonts
	 *
	 * @var NG_Fonts
	 */
	private static $instance = null;

	/**
	 * Array of all Google Fonts
	 *
	 * @var array|null
	 */
	public static $google_fonts = null;

	/**
	 * Key used in transient name
	 *
	 * @var string
	 * @access public
	 */
	public static $transient_key = '';

	/**
	 * Time in seconds to cache the results for
	 *
	 * @var int
	 * @access public
	 */
	public static $cache_time = 0;

	/**
	 * Whether or not to cache the Google response.
	 * Only set this to true if debugging.
	 *
	 * @var bool
	 * @access public
	 */
	public static $cache = true;

	/**
	 * NG_Fonts constructor.
	 */
	private function __construct() {
	}

	/**
	 * Get the one, true instance of this class.
	 * Prevents performance issues since this is only loaded once.
	 *
	 * @return object NG_Fonts
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Compile font options from different sources.
	 *
	 * @return array All available fonts.
	 */
	public static function get_all_fonts() {
		$standard_fonts = self::get_standard_fonts();
		$google_fonts   = self::get_google_fonts();

		return apply_filters( 'ng-fonts/fonts/all', array_merge( $standard_fonts, $google_fonts ) );
	}

	/**
	 * Return an array of standard websafe fonts.
	 *
	 * @return array Standard websafe fonts.
	 */
	public static function get_standard_fonts() {
		return apply_filters( 'ng-fonts/fonts/standard_fonts', array(
			'serif'      => array(
				'label' => _x( 'Serif', 'font style', 'theme-slug' ),
				'stack' => 'Georgia,Times,"Times New Roman",serif',
			),
			'sans-serif' => array(
				'label' => _x( 'Sans Serif', 'font style', 'theme-slug' ),
				'stack' => 'Helvetica,Arial,sans-serif',
			),
			'monospace'  => array(
				'label' => _x( 'Monospace', 'font style', 'theme-slug' ),
				'stack' => 'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace',
			),
		) );
	}

	/**
	 * Return an array of backup fonts based on the font-category
	 *
	 * @return array
	 */
	public static function get_backup_fonts() {
		$backup_fonts = array(
			'sans-serif'  => 'Helvetica, Arial, sans-serif',
			'serif'       => 'Georgia, serif',
			'display'     => '"Comic Sans MS", cursive, sans-serif',
			'handwriting' => '"Comic Sans MS", cursive, sans-serif',
			'monospace'   => '"Lucida Console", Monaco, monospace',
		);

		return apply_filters( 'ng-fonts/fonts/backup_fonts', $backup_fonts );
	}

	/**
	 * Return an array of all available Google Fonts.
	 *
	 * @return array All Google Fonts.
	 */
	public static function get_google_fonts() {

		if ( null === self::$google_fonts || empty( self::$google_fonts ) ) {

			$fonts = array(
				'body' => ''
			);

			// If the Google Fonts API key is set, then pull data direct from Google.
			if ( defined( 'GOOGLE_FONTS_API_KEY' ) && GOOGLE_FONTS_API_KEY ) {

				// Cache the data from Google.
				if ( false === ( $fonts = get_transient( self::$transient_key ) ) || false === self::$cache ) {
					$google_api = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=' . GOOGLE_FONTS_API_KEY;
					$fonts      = wp_remote_get( $google_api, array( 'sslverify' => false ) );
					set_transient( self::$transient_key, $fonts, self::$cache_time );
				}

			} else {
				$fonts['body'] = file_get_contents( wp_normalize_path( dirname( __FILE__ ) ) . '/json/google-web-fonts-request.txt' );
			}

			$fonts        = json_decode( $fonts['body'], true );
			$google_fonts = array();

			if ( is_array( $fonts ) && array_key_exists( 'items', $fonts ) && is_array( $fonts['items'] ) ) {
				foreach ( $fonts['items'] as $font ) {
					$google_fonts[ $font['family'] ] = array(
						'label'    => $font['family'],
						'variants' => $font['variants'],
						'subsets'  => $font['subsets'],
						'category' => $font['category'],
					);
				}
			}

			self::$google_fonts = apply_filters( 'ng-fonts/fonts/google_fonts', $google_fonts );

		}

		return self::$google_fonts;

	}

	/**
	 * Get Google Font subsets
	 *
	 * @return array
	 */
	public static function get_google_font_subsets() {
		return array(
			'cyrillic'     => esc_attr__( 'Cyrillic', 'theme-slug' ),
			'cyrillic-ext' => esc_attr__( 'Cyrillic Extended', 'theme-slug' ),
			'devanagari'   => esc_attr__( 'Devanagari', 'theme-slug' ),
			'greek'        => esc_attr__( 'Greek', 'theme-slug' ),
			'greek-ext'    => esc_attr__( 'Greek Extended', 'theme-slug' ),
			'khmer'        => esc_attr__( 'Khmer', 'theme-slug' ),
			'latin'        => esc_attr__( 'Latin', 'theme-slug' ),
			'latin-ext'    => esc_attr__( 'Latin Extended', 'theme-slug' ),
			'vietnamese'   => esc_attr__( 'Vietnamese', 'theme-slug' ),
			'hebrew'       => esc_attr__( 'Hebrew', 'theme-slug' ),
			'arabic'       => esc_attr__( 'Arabic', 'theme-slug' ),
			'bengali'      => esc_attr__( 'Bengali', 'theme-slug' ),
			'gujarati'     => esc_attr__( 'Gujarati', 'theme-slug' ),
			'tamil'        => esc_attr__( 'Tamil', 'theme-slug' ),
			'telugu'       => esc_attr__( 'Telugu', 'theme-slug' ),
			'thai'         => esc_attr__( 'Thai', 'theme-slug' )
		);
	}

	/**
	 * Google Font Variants
	 *
	 * @return array
	 */
	public static function get_all_variants() {
		return array(
			'100'       => esc_attr__( 'Ultra-Light 100', 'theme-slug' ),
			'100italic' => esc_attr__( 'Ultra-Light 100 Italic', 'theme-slug' ),
			'200'       => esc_attr__( 'Light 200', 'theme-slug' ),
			'200italic' => esc_attr__( 'Light 200 Italic', 'theme-slug' ),
			'300'       => esc_attr__( 'Book 300', 'theme-slug' ),
			'300italic' => esc_attr__( 'Book 300 Italic', 'theme-slug' ),
			'regular'   => esc_attr__( 'Normal 400', 'theme-slug' ),
			'italic'    => esc_attr__( 'Normal 400 Italic', 'theme-slug' ),
			'500'       => esc_attr__( 'Medium 500', 'theme-slug' ),
			'500italic' => esc_attr__( 'Medium 500 Italic', 'theme-slug' ),
			'600'       => esc_attr__( 'Semi-Bold 600', 'theme-slug' ),
			'600italic' => esc_attr__( 'Semi-Bold 600 Italic', 'theme-slug' ),
			'700'       => esc_attr__( 'Bold 700', 'theme-slug' ),
			'700italic' => esc_attr__( 'Bold 700 Italic', 'theme-slug' ),
			'800'       => esc_attr__( 'Extra-Bold 800', 'theme-slug' ),
			'800italic' => esc_attr__( 'Extra-Bold 800 Italic', 'theme-slug' ),
			'900'       => esc_attr__( 'Ultra-Bold 900', 'theme-slug' ),
			'900italic' => esc_attr__( 'Ultra-Bold 900 Italic', 'theme-slug' ),
		);
	}

	/**
	 * Is Google Font
	 *
	 * @param string $fontname
	 */
	public static function is_google_font( $fontname ) {
		return ( array_key_exists( $fontname, self::$google_fonts ) );
	}

	/**
	 * Returns an array of all font choices
	 *
	 * @return array
	 */
	public static function get_font_choices() {
		$fonts       = self::get_all_fonts();
		$fonts_array = array();
		foreach ( $fonts as $key => $args ) {
			$fonts_array[ $key ] = $key;
		}

		return $fonts_array;
	}

	/**
	 * Sanitize Typography Control
	 *
	 * @param array $value
	 *
	 * @access public
	 * @return array
	 */
	public static function sanitize_typography( $value ) {

		if ( ! is_array( $value ) ) {
			return array();
		}

		$sanitized_value = array();

		// Sanitize font family.
		if ( isset( $value['font-family'] ) ) {
			$sanitized_value['font-family'] = esc_attr( $value['font-family'] );
		}

		// Use a valid variant.
		if ( isset( $value['variant'] ) ) {
			$valid_variants = array(
				'regular',
				'italic',
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'700italic',
				'900',
				'900italic',
				'100italic',
				'300italic',
				'500italic',
				'800',
				'800italic',
				'600italic',
				'200italic',
			);

			$sanitized_value['variant'] = ( in_array( $value['variant'], $valid_variants ) ) ? sanitize_text_field( $value['variant'] ) : 'regular';
		}

		// Sanitize the subset
		if ( isset( $value['subset'] ) ) {
			$valid_subsets = array(
				'all',
				'greek-ext',
				'greek',
				'cyrillic-ext',
				'cyrillic',
				'latin-ext',
				'latin',
				'vietnamese',
				'arabic',
				'gujarati',
				'devanagari',
				'bengali',
				'hebrew',
				'khmer',
				'tamil',
				'telugu',
				'thai',
			);

			$sanitized_subsets = array();

			if ( is_array( $value['subset'] ) ) {
				foreach ( $value['subset'] as $subset ) {
					if ( in_array( $subset, $valid_subsets ) ) {
						$sanitized_subsets[] = sanitize_text_field( $subset );
					}
				}
				$sanitized_value['subsets'] = $sanitized_subsets;
			}
		}

		// Sanitize the font size.
		if ( isset( $value['font-size'] ) && ! empty( $value['font-size'] ) ) {
			$sanitized_value['font-size'] = self::css_dimension( $value['font-size'] );

			if ( $sanitized_value['font-size'] == self::filter_number( $sanitized_value['font-size'] ) ) {
				$sanitized_value['font-size'] .= 'px';
			}
		}

		// Sanitize the lineheight
		if ( isset( $value['line-height'] ) && ! empty( $value['line-height'] ) ) {
			$sanitized_value['line-height'] = self::css_dimension( $value['line-height'] );
		}

		// Sanitize the letter-spacing
		if ( isset( $value['letter-spacing'] ) && ! empty( $value['letter-spacing'] ) ) {
			$sanitized_value['letter-spacing'] = self::css_dimension( $value['letter-spacing'] );

			if ( $sanitized_value['letter-spacing'] == self::filter_number( $sanitized_value['letter-spacing'] ) ) {
				$sanitized_value['letter-spacing'] .= 'px';
			}
		}

		// Sanitize the color
		if ( isset( $value['color'] ) && ! empty( $value['color'] ) ) {
			$sanitized_value['color'] = sanitize_hex_color( $value['color'] );
		}

		// Sanitize the text align
		if ( isset( $value['text-align'] ) ) {
			$allowed_choices = array(
				'left',
				'right',
				'center',
				'justify'
			);

			if ( in_array( $value['text-align'], $allowed_choices ) ) {
				$sanitized_value['text-align'] = strip_tags( $value['text-align'] );
			}
		}

		// Sanitize the text transform
		if ( isset( $value['text-transform'] ) ) {
			$allowed_choices = array(
				'none',
				'uppercase',
				'lowercase'
			);

			if ( in_array( $value['text-transform'], $allowed_choices ) ) {
				$sanitized_value['text-transform'] = strip_tags( $value['text-transform'] );
			}
		}

		return $sanitized_value;

	}

	/**
	 * Sanitizes css dimensions
	 *
	 * @access public
	 * @return string
	 */
	public static function css_dimension( $value ) {
		// trim it
		$value = trim( $value );

		// if round, return 50%
		if ( 'round' == $value ) {
			$value = '50%';
		}

		// if empty, return empty
		if ( '' == $value ) {
			return '';
		}

		// If auto, return auto
		if ( 'auto' == $value ) {
			return 'auto';
		}

		// If inherit, return inherit
		if ( 'inherit' == $value ) {
			return 'inherit';
		}

		// Return empty if there are no numbers in the value.
		if ( ! preg_match( '#[0-9]#', $value ) ) {
			return '';
		}

		// If we're using calc() then return the value
		if ( false !== strpos( $value, 'calc(' ) ) {
			return $value;
		}

		// The raw value without the units
		$raw_value = self::filter_number( $value );
		$unit_used = '';

		// An array of all valid CSS units. Their order was carefully chosen for this evaluation, don't mix it up!!!
		$units = array(
			'rem',
			'em',
			'ex',
			'%',
			'px',
			'cm',
			'mm',
			'in',
			'pt',
			'pc',
			'ch',
			'vh',
			'vw',
			'vmin',
			'vmax'
		);

		foreach ( $units as $unit ) {
			if ( false !== strpos( $value, $unit ) ) {
				$unit_used = $unit;
			}
		}

		return $raw_value . $unit_used;
	}

	/**
	 * Filter Number
	 *
	 * @param string $value
	 *
	 * @return int
	 */
	public static function filter_number( $value ) {
		return filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

}