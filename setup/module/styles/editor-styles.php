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

if ( ! function_exists( 'follet_editor_styles' ) ) :
add_filter( 'follet_editor_styles', 'follet_editor_styles' );
/**
 * Add editor styles for Bootstrap.
 *
 * @since 2.0
 *
 * @param  array  $editor_styles List of editor styles to be registered.
 * @return array
 */
function follet_editor_styles( $editor_styles ) {
	// Bootstrap styles.
	if ( _follet_bootstrap_active() ) {
		$editor_styles['follet-bootstrap'] = follet_directory_uri() . '/includes/assets/bootstrap/css/bootstrap.min.css';
	}

	// Main style.
	if ( file_exists( $file = follet_template_directory() . '/editor-style.css' ) ) {
		$editor_styles['follet-main-style'] =  $file;
	} elseif ( follet_get_option( 'load_main_style' ) ) {
		$editor_styles['follet-main-style'] = get_stylesheet_uri();
	}

	return $editor_styles;
}
endif;
