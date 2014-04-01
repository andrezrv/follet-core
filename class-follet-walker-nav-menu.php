<?php
/**
 * Extend Walker_Nav_Menu as needed for this theme.
 *
 * This is a pluggable class.
 *
 * @package Follet_Core
 * @since   1.0
 */
if ( ! class_exists( 'Follet_Walker_Nav_Menu' ) ) :
/**
 * Follet_Walker_Nav_Menu
 *
 * Extend Walker_Nav_Menu to apply Bootstrap classes to submenus.
 *
 * @package Follet
 * @since   1.0
 */
class Follet_Walker_Nav_Menu extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n" . $indent . '<ul class="dropdown-menu">' . "\n";
	}

}
endif;
