<?php
/**
 * Extra Functions
 *
 * @package   theme-slug
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

/**
 * Custom CSS
 *
 * Generates custom CSS based on Customizer settings. This CSS gets merged into
 * our main theme stylesheet.
 *
 * @since 1.0
 * @return string
 */
function theme_slug_custom_css() {
	if ( ! is_customize_preview() ) {
		$saved_css = get_transient( 'theme_slug_customizer_css' );

		if ( $saved_css ) {
			return apply_filters( 'theme-slug/custom-css', $saved_css );
		}
	}

	$css = '';

	// All font CSS
	foreach ( theme_slug_typography_settings() as $key => $options ) {
		$typography = get_theme_mod( $key . '_font' );
		$font_css   = theme_slug_create_font_css( $key, $typography );

		if ( $font_css ) {
			$css .= $options['tag'] . '{ ' . $font_css . ' }';
		}
	}

	// Cache this.
	set_transient( 'theme_slug_customizer_css', $css );

	return apply_filters( 'theme-slug/custom-css', $css );
}

/**
 * Create Font CSS
 *
 * Generates all the typography CSS we need from the Customizer typography control.
 *
 * @param string     $theme_mod  Key value
 * @param array|bool $typography Theme mod from the Customizer
 *
 * @since 1.0
 * @return string
 */
function theme_slug_create_font_css( $theme_mod, $typography = null ) {
	if ( empty( $typography ) ) {
		$typography = get_theme_mod( $theme_mod . '_font' );
	}

	if ( empty( $typography ) || ! is_array( $typography ) ) {
		return '';
	}

	$css = '';

	foreach ( $typography as $property => $value ) {
		if ( $property == 'subsets' ) {
			continue;
		}

		$value   = str_replace( 'regular', '400', $value );
		$italics = false;

		if ( $property == 'variant' && strpos( $value, 'italic' ) !== false ) {
			$value   = ( $value === 'italic' ) ? '400' : str_replace( 'italic', '', $value );
			$italics = true;
		}

		if ( $property == 'font-family' ) {
			$css .= $property . ': "' . esc_html( $value ) . '";';
		} else {
			$css .= str_replace( 'variant', 'font-weight', $property ) . ': ' . esc_html( $value ) . ';';
		}

		if ( $italics ) {
			$css .= 'font-style: italic;';
		}
	}

	return apply_filters( 'theme-slug/create-font-css', $css, $theme_mod, $typography );
}

/**
 * Get Google Fonts
 *
 * Returns an array of all the Google Fonts that are in use with the font
 * family as the key and an array of variants as the value. We make sure that
 * each font and variant is only included once.
 *
 * @since 1.0
 * @return array
 */
function theme_slug_get_google_fonts() {
	$fonts   = array();
	$find    = array( 'regular', 'italic' );
	$replace = array( '400', '400italic' );

	foreach ( bookstagram_typography_settings() as $key => $options ) {
		$settings = get_theme_mod( $key . '_font' );

		if ( empty( $settings ) || ! is_array( $settings ) || ! array_key_exists( 'font-family', $settings ) ) {
			$variant     = $options['settings']['variant'];
			$font_family = $options['settings']['font-family'];
		} else {
			$variant     = $settings['variant'];
			$font_family = $settings['font-family'];
		}

		$variant_final = array( str_replace( $find, $replace, $variant ) );

		// Add italics and bold for body font.
		if ( $key == 'body' ) {
			$variant_final = array(
				$variant,
				$variant . 'italic',
				'700',
				'700italic'
			);
		}

		// Font doesn't already exist - let's add it.
		if ( ! array_key_exists( $font_family, $fonts ) ) {
			$fonts[ $font_family ] = $variant_final;

			continue;
		}

		// The font already exists, so let's add variants.
		foreach ( $variant_final as $single_variant ) {
			if ( ! in_array( $single_variant, $fonts[ $font_family ] ) ) {
				$fonts[ $font_family ][] = $single_variant;
			}
		}
	}

	return apply_filters( 'theme-slug/get-google-fonts', $fonts );
}

/**
 * Get Google Fonts URL
 *
 * Turns our array of Google Fonts into a proper API URL.
 *
 * @uses  theme_slug_get_google_fonts()
 *
 * @since 1.0
 * @return string
 */
function theme_slug_get_google_fonts_url() {
	if ( ! is_customize_preview() ) {
		$saved_url = get_transient( 'theme_slug_google_fonts_url' );

		if ( $saved_url ) {
			return $saved_url;
		}
	}

	$fonts_array    = theme_slug_get_google_fonts();
	$compiled_fonts = array();

	foreach ( $fonts_array as $font_family => $variants ) {
		$compiled_fonts[] = $font_family . ':' . implode( ',', $variants );
	}

	$url = add_query_arg( array( 'family' => implode( '|', $compiled_fonts ) ), 'https://fonts.googleapis.com/css' );

	set_transient( 'theme_slug_google_fonts_url', $url );

	return apply_filters( 'theme-slug/get-google-fonts-url', $url, $fonts_array );
}

/**
 * Get Typography Settings
 *
 * Enter all font settings here.
 *
 * Returns an array of all the typography settings. This array is used to
 * quickly generate the different Customizer options and the accompanying
 * CSS code. We put this code in a function since we re-use it so many times.
 *
 * @since 1.0
 * @return array
 */
function theme_slug_typography_settings() {
	$settings = array(
		'body'        => array(
			'name'     => esc_html__( 'Body', 'theme-slug' ),   // Title to appear in Customizer
			'tag'      => 'body',                               // HTML tag to apply the CSS to
			'settings' => array(                                // Default font styles
				'color'          => '#6b6b6b',
				'font-family'    => 'Merriweather',
				'variant'        => 300,
				'font-size'      => '14px',
				'letter-spacing' => '0',
				'line-height'    => '1.8',
				'text-align'     => 'left'
			)
		),
		'post_titles' => array(
			'name'     => esc_html__( 'Post &amp; Page Titles', 'theme-slug' ),
			'tag'      => '.entry-title',
			'settings' => array(
				'color'          => '#333333',
				'font-family'    => 'Merriweather',
				'variant'        => 'regular',
				'font-size'      => '24px',
				'letter-spacing' => '0',
				'line-height'    => '1.3',
				'text-align'     => 'center',
				'text-transform' => 'none'
			)
		)
	);

	return apply_filters( 'theme-slug/typography-settings', $settings );
}