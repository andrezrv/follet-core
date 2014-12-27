<?php
/**
 * Follet Core.
 *
 * Load all Customizer functionality.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'follet_customizer_library' ) ) :
add_action( 'follet_setup', 'follet_customizer_library' );
/**
 * Load Devin Price's Customizer Library.
 *
 * @since  1.1
 */
function follet_customizer_library() {
	follet_load_library( dirname( __FILE__ ) . '/../customizer-library/' );
}
endif;
