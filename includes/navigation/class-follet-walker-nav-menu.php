<?php
/**
 * Follet Core.
 *
 * Extend Walker_Nav_Menu as needed for this theme.
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

if ( ! class_exists( 'Follet_Walker_Nav_Menu' ) ) :
/**
 * Class Follet_Walker_Nav_Menu
 *
 * Extend Walker_Nav_Menu to apply Bootstrap classes to sub-menus.
 *
 * @package Follet
 * @since   1.0
 */
class Follet_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * Add "dropdown-menu" class to navigation submenus.
	 *
	 * @see   Walker_Nav_Menu::start_lvl()
	 *
	 * @param string $output
	 * @param int    $depth
	 * @param array  $args
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n" . $indent . '<ul class="dropdown-menu">' . "\n";
	}
}
endif;
