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
namespace Follet;

use Follet\Application\PluginAPIManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define framework directory.
 *
 * @since 2.0
 */
define( 'FOLLET_DIR', trailingslashit( dirname( __FILE__ ) ) );

/**
 * Define main application directory.
 *
 * @since 2.0
 */
define( 'FOLLET_ENGINE_DIR', FOLLET_DIR . 'engine/' );

/**
 * Define directory for third-party dependencies.
 *
 * @since 2.0
 */
define( 'FOLLET_LIB_DIR', FOLLET_ENGINE_DIR . 'lib' );

/**
 * Load auto-loader class.
 *
 * @since 2.0
 */
require FOLLET_ENGINE_DIR . 'bootstrap/class-loader.php';

/**
 * Auto-load all PHP files.
 *
 * @since 2.0
 */
new Bootstrap\Loader( FOLLET_DIR, 'engine', array( FOLLET_LIB_DIR ) );

/**
 * Initialize Follet.
 *
 * @since 1.0
 */
follet( follet_config(), new PluginAPIManager() );
