<?php
/**
 * Follet Core.
 *
 * This file contains functions to manage loading of basic theme style sheets.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'follet_styles' ) ) :
add_filter( 'follet_styles', 'follet_styles' );
/**
 * Register main stylesheets.
 *
 * @since  2.0
 *
 * @param  array  $styles List of theme styles to be registered.
 * @return array
 */
function follet_styles( $styles ) {
	$styles = array_merge( $styles, apply_filters( 'follet_default_styles', array(
		/**
		 * Load Bootstrap styles.
		 *
		 * @since 1.0
		 */
		'follet-bootstrap-styles' => array(
			'src'  => follet_directory_uri() . '/includes/assets/bootstrap/css/bootstrap.min.css',
			'eval' => _follet_bootstrap_active(),
		),

		/**
		 * Load main style sheet.
		 *
		 * @since 1.0
		 */
		'follet-style' => array(
			'src'  => get_stylesheet_uri(),
			'deps' => apply_filters( 'follet_main_style_dependencies', array() ),
			'eval' => follet_get_option( 'load_main_style' ),
		),
	) ) );

	return $styles;
}
endif;


if ( ! function_exists( 'follet_add_ie_compatibility_modes' ) ) :
add_filter( 'wp_head', 'follet_add_ie_compatibility_modes' );
/**
 * Add support for IE compatibility modes.
 *
 * @link http://getbootstrap.com/getting-started/#support-ie-compatibility-modes
 *
 * @since 1.0
 */
function follet_add_ie_compatibility_modes() {
	if ( _follet_bootstrap_active() ) {
		$content = '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . "\n";

		echo $content;
	}
}
endif;
