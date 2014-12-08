<?php
/**
 * Load all the files that Follet needs to work properly.
 *
 * @package Follet_Core
 * @since   1.0
 */

$dirname = dirname( __FILE__ );

/**
 * Load Follet main class.
 */
require $dirname . '/class-follet.php';

/**
 * Extend Customizer.
 */
require $dirname . '/class-follet-text-control.php';

/**
 * Extend Walker_Nav_Menu.
 */
require $dirname . '/class-follet-walker-nav-menu.php';

/**
 * Instance Follet.
 */
$follet = Follet::get_instance();

/**
 * Remember to add `do_action( 'follet_setup' );` in your functions.php!
 */