<?php
/**
 * Follet Core.
 *
 * Functions for Follet Core internal usage.
 *
 * These functions are not meant to be used outside the Follet Core scope.
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

if ( ! function_exists( '_follet_bootstrap_active' ) ) :
/**
 * Check if Bootstrap is active.
 *
 * @internal
 *
 * @since  1.0
 *
 * @param  boolean $check_style Check if stylesheet is loaded.
 * @return boolean
 */
function _follet_bootstrap_active( $check_style = false ) {
	$bootstrap = true;

	if ( $check_style && $theme_support = get_theme_support( 'bootstrap' ) ) {
		$bootstrap = $theme_support && wp_style_is( 'follet-bootstrap-styles' );
	}

	$bootstrap = apply_filters( 'follet_bootstrap_active', $bootstrap );

	return $bootstrap;
}
endif;

if ( function_exists( '_follet_modify_link_pages' ) ) :
/**
 * Apply extra styles to wp_link_pages.
 *
 * @internal
 *
 * @since  1.0
 *
 * @global integer $page Number of current page.
 *
 * @param  string  $link Link to the current page in loop.
 * @param  integer $i    Number of the current page in loop.
 * @return string        Filtered link to the current page in loop.
 */
function _follet_modify_link_pages( $link, $i ) {
	global $page;

	$bootstrap = _follet_bootstrap_active( true );

	if ( ! is_home() && ! is_archive() && ! is_search() && $i == $page ) {
		$link = _wp_link_page( $i ) . $link . '</a>';
	}

	$replace = $bootstrap ? 'class="btn btn-default page-' . $i . '" href' : 'class="" href';
	$link    = $bootstrap ? str_replace( 'href', $replace, $link ) : $link;

	if ( $i == $page ) {
		$link = str_replace( 'class="', 'class="active ', $link );
	}

	$link = apply_filters( 'follet_modify_link_pages', $link );

	return $link;
}
endif;

if ( function_exists( '_follet_continue_reading_excerpt_link' ) ) :
/**
 * This function is used to filter the "read more" link when called next to an excerpt.
 *
 * @internal
 *
 * @since  1.0.2
 *
 * @param  string $content Default content to be filtered.
 * @return string          Filtered content.
 */
function _follet_continue_reading_excerpt_link( $content ) {
	$link_begin = apply_filters( 'follet_continue_reading_excerpt_link_begin', '<a class="more-link" href="' . get_permalink() . '">' );
	$link_end   = apply_filters( 'follet_continue_reading_excerpt_link_end', '</a>' );

	$content = $link_begin . $content . $link_end;
	$content = apply_filters( 'follet_continue_reading_excerpt_link', $content );

	return $content;
}
endif;
