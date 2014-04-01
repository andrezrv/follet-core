<?php
/**
 * Load all the files that Follet needs to work properly.
 *
 * @package Follet_Core
 * @since   1.0
 */

$dirname = dirname( __FILE__ );

/**
 * Load utilitary functions.
 */
require $dirname . '/follet-functions.php';
require $dirname . '/follet-internal.php';

/**
 * Load standard template tags.
 */
require $dirname . '/follet-template-tags.php';

/*
 * Load Follet main classes
 */
require $dirname . '/class-follet-singleton.php';
require $dirname . '/class-follet.php';
require $dirname . '/class-follet-actions.php';
require $dirname . '/class-follet-filters.php';

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
$follet_actions = Follet_Actions::get_instance();
$follet_filters = Follet_Filters::get_instance();

/**
 * Remember to add `do_action( 'follet_setup' );` in your functions.php!
 */