<?php

/**
 * Customizer Settings
 *
 * @package   theme-slug
 * @copyright Copyright (c) 2016, Nose Graze Ltd.
 * @license   GPL2+
 */

if ( class_exists( 'Theme_Slug_Customizer' ) ) {
	return;
}

class Theme_Slug_Customizer {

	/**
	 * Theme object
	 *
	 * @var WP_Theme
	 * @access private
	 * @since  1.0
	 */
	private $theme;

	/**
	 * Theme_Slug_Customizer constructor.
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function __construct() {

		$theme       = wp_get_theme();
		$this->theme = $theme;

		add_action( 'customize_register', array( $this, 'include_controls' ) );
		add_action( 'customize_register', array( $this, 'register_customize_sections' ) );

	}

	/**
	 * Include Custom Controls
	 *
	 * Includes all our custom control classes.
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function include_controls( $wp_customize ) {

		require_once get_template_directory() . '/inc/customizer/class-ng-fonts.php';
		require_once get_template_directory() . '/inc/customizer/controls/class-ng-typography-control.php';

		$wp_customize->register_control_type( 'NG_Typography_Control' );

	}

	/**
	 * Add all panels to the Customizer
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	 */
	public function register_customize_sections( $wp_customize ) {

		/*
		 * Panels
		 */

		$wp_customize->add_panel( 'typography', array(
			'title'    => esc_html__( 'Typography', 'theme-slug' ),
			'priority' => 51
		) );

		/*
		 * Sections
		 */

		// Add typography settings. We put each font setting in its own section to make things more organized.
		foreach ( theme_slug_typography_settings() as $key => $options ) {
			$wp_customize->add_section( 'typography_' . $key, array(
				'title' => sprintf( esc_html__( '%s Font', 'theme-slug' ), $options['name'] ),
				'panel' => 'typography'
			) );
		}

		/*
		 * Populate Sections
		 */

		$this->typography_section( $wp_customize );

	}

	/**
	 * Section: Typography
	 *
	 * @uses   theme_slug_typography_settings()
	 *
	 * @param WP_Customize_Manager $wp_customize
	 *
	 * @access private
	 * @since  1.0
	 * @return void
	 */
	private function typography_section( $wp_customize ) {

		// Actual font defaults are declared in theme_slug_typography_settings()
		foreach ( theme_slug_typography_settings() as $key => $options ) {
			$description = array_key_exists( 'description', $options ) ? $options['description'] : '';

			$wp_customize->add_setting( $key . '_font', array(
				'default'           => $options['settings'],
				'sanitize_callback' => array( 'NG_Fonts', 'sanitize_typography' )
			) );

			$wp_customize->add_control( new NG_Typography_Control( $wp_customize, $key . '_font', array(
				'label'       => sprintf( esc_html__( '%s Font', 'theme-slug' ), $options['name'] ),
				'description' => $description,
				'type'        => 'ng_typography',
				'section'     => 'typography_' . $key,
				'settings'    => $key . '_font'
			) ) );
		}

	}

}