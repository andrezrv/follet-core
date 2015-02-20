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
 * Define framework directory.
 *
 * @since 1.1
 */
define( 'FOLLET_DIR', trailingslashit( dirname( __FILE__ ) ) );

/**
 * Load auto-loader class.
 *
 * @since 1.1
 */
require FOLLET_DIR . 'engine/bootstrap/class-loader.php';

/**
 * Auto-load all PHP files.
 *
 * @since 1.1
 */
new \Follet\Bootstrap\Loader( FOLLET_DIR, 'engine', array( FOLLET_DIR . 'engine/lib' ) );

/**
 * Initialize Follet.
 *
 * @since 1.0
 */
Follet\Application\Core::get_instance();
