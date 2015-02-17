<?php
/**
 * Follet Core.
 *
 * Utilitary functions for customization management.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'follet' ) ) :
/**
 * Get Follet instance or given value.
 *
 * Usage of this function is not recommended to retrieve values other than the
 * instance itself, since it's harder to debug. However, it gives the advantage
 * of not having to call the global object before using it.
 *
 * @since  1.0
 *
 * @global Follet $follet Current instance of Follet object.
 *
 * @param  string $value  Name of a public Follet property.
 * @return mixed          Value of a Follet property, or current Follet instance.
 */
function follet( $value = '' ) {
	global $follet;

	if ( $value && property_exists( $follet, $value ) ) {
		return $follet->$value;
	}

	return $follet;
}
endif;

if ( ! function_exists( 'follet_load_library' ) ) :
/**
 * Load a PHP library given a file or a folder.
 *
 * @uses  Follet_Loader::__get_instance()
 *
 * @since 1.1
 *
 * @param  string        $library Name of folder or PHP file.
 *
 * @return Follet_Loader
 */
function follet_load_library( $library ) {
	return new Follet_Loader( $library );
}
endif;

if ( ! function_exists( 'follet_textdomain' ) ) :
/**
 * Obtain theme text domain.
 *
 * @since 1.1
 */
function follet_textdomain() {
	global $follet;
	return $follet->textdomain;
}
endif;

if ( ! function_exists( 'follet_doing_ajax' ) ) :
/**
 * Check if we're operating in an AJAX context.
 *
 * @since  1.1
 * @return bool
 */
function follet_doing_ajax() {
	global $follet;
	return $follet->doing_ajax;
}
endif;

if ( ! function_exists( 'follet_theme_version' ) ) :
/**
 * Obtain current version of the theme.
 *
 * @since  1.1
 * @return bool
 */
function follet_theme_version() {
	global $follet;
	return $follet->theme_version;
}
endif;

if ( ! function_exists( 'follet_template_directory' ) ) :
/**
 * Overhead-free alias for get_template_directory().
 *
 * @since  1.0
 *
 * @global Follet $follet Current instance of Follet object.
 *
 * @return string
 */
function follet_template_directory() {
	global $follet;
	return $follet->template_directory;
}
endif;

if ( ! function_exists( 'follet_template_directory_uri' ) ) :
/**
 * Overhead-free alias for get_template_directory_uri().
 *
 * @since  1.0
 *
 * @global Follet $follet Current instance of Follet object.
 *
 * @return string
 */
function follet_template_directory_uri() {
	global $follet;
	return $follet->template_directory_uri;
}
endif;

if ( ! function_exists( 'follet_directory' ) ) :
/**
 * Follet directory.
 *
 * @since  1.0
 *
 * @global Follet $follet Current instance of Follet object.
 *
 * @return string
 */
function follet_directory() {
	global $follet;
	return $follet->directory;
}
endif;

if ( ! function_exists( 'follet_directory_uri' ) ) :
/**
 * Follet directory URI.
 *
 * @since  1.0
 *
 * @global Follet $follet Current instance of Follet object.
 *
 * @return string
 */
function follet_directory_uri() {
	global $follet;
	return $follet->directory_uri;
}
endif;

if ( ! function_exists( 'follet_register_option' ) ) :
/**
 * Add an option to the Follet instance.
 *
 * @example
 *
	follet_register_option( 'my-option', 'foo', get_option( 'foo' ) );

 * @since  1.0
 *
 * @uses   Follet_Options_Manager::register_option()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param  string $name    Name of the new option.
 * @param  mixed  $default Default value for the option.
 * @param  mixed  $current Current value of the option.
 */
function follet_register_option( $name, $default, $current = null ) {
	global $follet_options_manager;
	$follet_options_manager->register_option( $name, $default, $current );
}
endif;

if ( ! function_exists( 'follet_remove_option' ) ) :
/**
 * Remove option from the Follet instance.
 *
 * @since  1.0
 *
 * @uses   Follet_Options_Manager::remove_option()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param string $name Name of the option.
 */
function follet_remove_option( $name ) {
	global $follet_options_manager;
	$follet_options_manager->remove_option( $name );
}
endif;

if ( ! function_exists( 'follet_get_default' ) ) :
/**
 * Get the default value of a Follet option.
 *
 * @since  1.0
 *
 * @uses   Follet_Options_Manager::get_default()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param  string $name Name of the option.
 *
 * @return mixed        Default value of the option.
 */
function follet_get_default( $name ) {
	global $follet_options_manager;
	return $follet_options_manager->get_default( $name );
}
endif;

if ( ! function_exists( 'follet_get_current' ) ) :
/**
 * Get the current value of a Follet option.
 *
 * @since  1.0
 *
 * @uses   Follet_Options_Manager::get_current()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param  string $name   Name of the option.
 * @return mixed          Current value of the option.
 */
function follet_get_current( $name ) {
	global $follet_options_manager;
	return $follet_options_manager->get_current( $name );
}
endif;

if ( ! function_exists( 'follet_get_option' ) ) :
/**
 * Get the value of a Follet option.
 *
 * Usage of this function is preferred to `follet_get_current()`.
 *
 * @since  1.1
 *
 * @uses   Follet_Options_Manager::get_option()
 * @see    follet_get_current()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param  string $name Name of the option.
 * @return mixed        Current value of the option.
 */
function follet_get_option( $name ) {
	global $follet_options_manager;
	return $follet_options_manager->get_option( $name );
}
endif;

if ( ! function_exists( 'follet_option_exists' ) ) :
/**
 * Get the current value of a Follet option.
 *
 * @since  1.0
 *
 * @uses   Follet_Options_Manager::option_exists()
 *
 * @global Follet_Options_Manager $follet_options_manager Current instance of options manager.
 *
 * @param  string $name Name of the option.
 * @return boolean
 */
function follet_option_exists( $name ) {
	global $follet_options_manager;
	return $follet_options_manager->option_exists( $name );
}
endif;

if ( ! function_exists( 'follet_previous_post_exists' ) ) :
/**
 * Check if the current post has a previous one.
 *
 * @since  1.0
 *
 * @return boolean
 */
function follet_previous_post_exists() {
	$post = get_post();
	return ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
}
endif;

if ( ! function_exists( 'follet_next_post_exists' ) ) :
/**
 * Check if the current post has a next one.
 *
 * @since  1.0
 *
 * @return boolean
 */
function follet_next_post_exists() {
	return get_adjacent_post( false, '', false );
}
endif;

if ( ! function_exists( 'follet_override_stylesheet_colors' ) ) :
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
 *
 * @since  1.0
 *
 * @param  string $current_value Current color value.
 * @param  string $stylesheet    Path of the stylesheet to copy.
 * @param  string $default_color Default color to check.
 * @param  array  $colors        List of color values to replace.
 *
 * @return string
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
endif;
