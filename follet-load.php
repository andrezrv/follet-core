<?php
/**
 * Follet Core.
 *
 * Load all the files that Follet needs to work properly.
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

/**
 * Load auto-loader class.
 *
 * @since 1.1
 */
require dirname( __FILE__ ) . '/bootstrap/class-follet-loader.php';

/**
 * Auto-load all PHP files.
 *
 * @since 1.1
 */
new Follet_Loader( dirname( __FILE__ ) );

/**
 * Initialize Follet.
 *
 * @since 1.0
 */
Follet::get_instance();
