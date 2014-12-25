<?php
/**
 * Follet Core.
 *
 * Manage localization functionality.
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

if ( ! function_exists( 'follet_load_textdomain' ) ) :
add_action( 'after_setup_theme', 'follet_load_textdomain' );
/**
 * Make theme available for translation.
 *
 * @since  1.0
 *
 * @uses   load_theme_textdomain()
 *
 * @global Follet $follet Current instance of Follet object.
 */
function follet_load_textdomain() {
	global $follet;
	$follet_textdomain_location = apply_filters( 'follet_textdomain_location', $follet->template_directory . '/languages' );

	load_theme_textdomain( $follet->textdomain, $follet_textdomain_location );
}
endif;
