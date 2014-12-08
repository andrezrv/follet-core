<?php
/**
 * Utilitary functions for customization management.
 *
 * @package Follet_Core
 * @since   1.0
 */

/**
 * Get Follet instance.
 *
 * @since  1.0
 *
 * @param  string $value Name of a public Follet property.
 * @return mixed         Value of a Follet property, or current Follet instance.
 */
function follet( $value = '' ) {
	global $follet;

	if ( $value && property_exists( $follet, $value ) ) {
		return $follet->$value;
	}

	return $follet;
}

/**
 * Overhead-free alias for get_template_directory().
 *
 * @since  1.0
 * @return string
 */
function follet_template_directory() {
	return follet( 'template_directory' );
}

/**
 * Overhead-free alias for get_template_directory_uri().
 *
 * @since  1.0
 * @return string
 */
function follet_template_directory_uri() {
	return follet( 'template_directory_uri' );
}

/**
 * Follet directory.
 *
 * @since  1.0
 * @return string
 */
function follet_directory() {
	return follet( 'directory' );
}

/**
 * Follet directory URI.
 *
 * @since  1.0
 * @return string
 */
function follet_directory_uri() {
	return follet( 'directory_uri' );
}

/**
 * Add an option to the Follet instance.
 *
 * @example
 *
	follet_register_option( 'my-option', 'foo', get_option( 'foo' ) );

 * @since  1.0
 *
 * @uses   Follet->register_option()
 *
 * @param  string $name    Name of the new option.
 * @param  mixed  $default Default value for the option.
 * @param  mixed  $current Current value of the option.
 */
function follet_register_option( $name, $default, $current = null ) {
	global $follet;
	$follet->register_option( $name, $default, $current );
}

/**
 * Remove option from the Follet instance.
 *
 * @uses   Follet->remove_option()
 * @param  string $name Name of the option.
 * @return void
 * @since  1.0
 */
function follet_remove_option( $name ) {
	global $follet;
	$follet->remove_option( $name );
}

/**
 * Get the default value of a Follet option.
 *
 * @uses   Follet->get_default()
 * @param  string $name Name of the option.
 * @return mixed        Default value of the option.
 * @since  1.0
 */
function follet_get_default( $name ) {
	global $follet;
	return $follet->get_default( $name );
}

/**
 * Get the current value of a Follet option.
 *
 * @uses   Follet->get_current()
 * @param  string $name Name of the option.
 * @return mixed        Current value of the option.
 * @since  1.0
 */
function follet_get_current( $name ) {
	global $follet;
	return $follet->get_current( $name );
}

/**
 * Get the value of a Follet option.
 *
 * Usage of this function is preferred to `follet_get_current()`.
 *
 * @since  1.1
 *
 * @uses   Follet->get_option()
 * @see    follet_get_current()
 *
 * @param  string $name Name of the option.
 * @return mixed        Current value of the option.
 */
function follet_get_option( $name ) {
	global $follet;
	return $follet->get_option( $name );
}

/**
 * Get the current value of a Follet option.
 *
 * @since  1.0
 *
 * @uses   Follet->option_exists()
 *
 * @param  string $name Name of the option.
 * @return boolean
 */
function follet_option_exists( $name ) {
	global $follet;
	return $follet->option_exists( $name );
}

/**
 * Check if the current post has a previous one.
 *
 * @return boolean
 * @since  1.0
 */
function follet_previous_post_exists() {
	$post = get_post();
	return ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
}

/**
 * Check if the current post has a next one.
 *
 * @return boolean
 * @since  1.0
 */
function follet_next_post_exists() {
	return get_adjacent_post( false, '', false );
}

/**
 * Copy a stylesheet and replace colors to pass result to wp_add_inline_style().
 *
 * Use this function wisely. It's REALLY experimental.
 *
 * @example
 *
	function follet_primary_color() {
		$primary_color_style = follet_override_stylesheet_colors(
			get_theme_mod( 'primary_color', '#428BCA' ),
			get_template_directory() . '/css/primary-color.css',
			'#428BCA',
			array( '#428BCA' )
		);
		if ( $primary_color_style ) {
			wp_add_inline_style( 'primary-color-style', $primary_color_style );
		}
	}
	add_action( 'wp_enqueue_scripts', 'follet_primary_color' );

 * @param  string $current_value Current color value.
 * @param  string $stylesheet    Path of the stylesheet to copy.
 * @param  string $default_color Default color to check.
 * @param  array  $colors        List of color values to replace.
 * @return string
 * @since  1.0
 */
function follet_override_stylesheet_colors( $current_value, $stylesheet, $default_color, $colors = array() ) {
	if ( ( $default_color != $current_value ) && ( strtolower( $default_color ) != $current_value ) ) {
		ob_start();

		include $stylesheet;

		$content = ob_get_contents();
		ob_end_clean();
		if ( ! empty( $colors ) ) {
			foreach ( $colors as $color ) {
				$content = str_replace( $color, $current_value, $content );
				$content = str_replace( strtolower( $color ), $current_value, $content );
			}
		}

		return $content;
	}

	return '';
}
