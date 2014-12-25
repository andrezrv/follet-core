<?php
/**
 * Follet Core.
 *
 * Manage editor styles.
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

if ( ! function_exists( 'follet_add_bootstrap_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_bootstrap_editor_styles' );
/**
 * Add editor styles for Bootstrap.
 *
 * @since 1.1
 */
function follet_add_bootstrap_editor_styles() {
	// Bootstrap styles.
	if ( _follet_bootstrap_active() ) {
		add_editor_style( follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css' );
	}
}
endif;

if ( ! function_exists( 'follet_add_main_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_main_editor_styles' );
/**
 * Add main editor styles.
 *
 * This function tries to add the `editor-style.css` file. In case it does not
 * exist, `style.css` will be added instead.
 *
 * @since 1.1
 */
function follet_add_main_editor_styles() {
	// Main style.
	if ( file_exists( $file = follet( 'template_directory' ) . '/editor-style.css' ) ) {
		add_editor_style( $file );
	} elseif ( follet_get_option( 'load_main_style' ) ) {
		add_editor_style( get_stylesheet_uri() );
	}
}
endif;
