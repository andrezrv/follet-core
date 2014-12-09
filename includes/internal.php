<?php
/**
 * Functions for Follet Core internal usage. Not meant to be used outside the Follet
 * Core scope.
 *
 * @package Follet_Core
 * @since   1.0
 */

/**
 * Check if Bootstrap is active.
 *
 * @internal
 *
 * @since  1.0
 *
 * @param  boolean $check_style CHeck if stylesheet is loaded.
 * @return boolean
 *
 */
function _follet_bootstrap_active( $check_style = false ) {
	$theme_support = get_theme_support( 'bootstrap' );
	$bootstrap     = $theme_support;

	if ( $check_style && $theme_support ) {
		$bootstrap = $bootstrap && wp_style_is( 'follet-bootstrap-styles' );
	}

	return $bootstrap;
}

/**
 * Apply extra styles to wp_link_pages.
 *
 * @internal
 *
 * @param  string  $link Link to the current page in loop.
 * @param  integer $i    Number of the current page in loop.
 * @return string        Filtered link to the current page in loop.
 * @since  1.0
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

	return $link;
}


/**
 * This function is used to filter the read-more link when called next to an
 * excerpt.
 *
 * @internal
 *
 * @param  string $content Default content to be filtered.
 * @return string          Filtered content.
 * @since  1.0.2
 */
function _follet_continue_reading_excerpt_link( $content ) {
	$link_begin = apply_filters(
		'follet_continue_reading_excerpt_link_begin',
		'<a class="more-link" href="' . get_permalink() . '">'
	);
	$link_end = apply_filters(
		'follet_continue_reading_excerpt_link_end',
		'</a>'
	);
	$content = $link_begin . $content . $link_end;
	$content = apply_filters( 'follet_continue_reading_excerpt_link', $content );
	return $content;
}

if ( ! function_exists( '_follet_get_called_class' ) ) :
/**
 * Add support for get_called_class in PHP < 5.3
 *
 * @internal
 * @see      Follet
 *
 * @since  1.0
 *
 * @return string Class name.
 */
function _follet_get_called_class() {
	$bt = debug_backtrace();
	$l = 0;
	do {
		$l++;
		$lines = file($bt[$l]['file']);
		$callerLine = $lines[$bt[$l]['line']-1];
		preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/', $callerLine, $matches);
	} while ($matches[1] === 'parent' && $matches[1]);

	return $matches[1];
}
endif;
