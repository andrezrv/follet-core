<?php
/**
 * Load all the files that Follet needs to work properly.
 *
 * @package Follet_Core
 * @since   1.0
 */

/**
 * Load Follet main class.
 *
 * @since 1.0
 */
require dirname( __FILE__ ) . '/class-follet.php';

/**
 * Instance Follet.
 *
 * @since 1.0
 */
Follet::get_instance();
