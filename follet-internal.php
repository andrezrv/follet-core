<?php
/**
 * Functions for Follet internal usage. Not meant to be used outside the Follet
 * Framework scope.
 *
 * @package Follet_Core
 * @since   1.0
 */

/**
 * Check if Bootstrap is active.
 *
 * @internal
 *
 * @return boolean
 * @since  1.0
 */
function _follet_bootstrap_active() {
	$bootstrap = get_theme_support( 'bootstrap' ) && wp_style_is( 'follet-bootstrap-styles' );
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
	$bootstrap = _follet_bootstrap_active();
	if ( ! is_home() && ! is_archive() && ! is_search() && $i == $page ) {
		$link = _wp_link_page( $i ) . $link . '</a>';
	}
	$replace = $bootstrap ? 'class="btn btn-default page-' . $i . '" href' : 'class="" href';
	$link = $bootstrap ? str_replace( 'href', $replace, $link ) : $link;
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
