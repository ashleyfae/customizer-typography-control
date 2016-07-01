<?php
/**
 * Theme Functions
 *
 * @package   customizer-typography
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

/**
 * Enqueue scripts and styles.
 *
 * @uses  wp_get_theme()
 * @uses  theme_slug_get_google_fonts_url()
 * @uses  theme_slug_custom_css()
 *
 * @since 1.0
 * @return void
 */
function theme_slug_assets() {
	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );

	// Google Fonts
	wp_enqueue_style( 'theme-slug-google-fonts', theme_slug_get_google_fonts_url() );

	// Add styles
	wp_enqueue_style( 'theme-slug', get_stylesheet_uri(), array(), $version );
	wp_add_inline_style( 'theme-slug', theme_slug_custom_css() );
}

add_action( 'wp_enqueue_scripts', 'theme_slug_assets' );

/**
 * Extra functions.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer settings.
 */
require get_template_directory() . '/inc/customizer/class-theme-slug-customizer.php';
new Theme_Slug_Customizer();