<?php
if ( ! function_exists( 'follet_theme_support_feed' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_feed' );
/**
 * Add default posts and comments RSS feed links to head.
 *
 * @since 1.1
 */
function follet_theme_support_feed() {
	add_theme_support( 'automatic-feed-links' );
}
endif;

if ( ! function_exists( 'follet_theme_support_post_thumbnails' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_post_thumbnails' );
/**
 * Enable support for Post Thumbnails on posts and pages.
 *
 * @since 1.1
 */
function follet_theme_support_post_thumbnails() {
	add_theme_support( 'post-thumbnails' );
}
endif;

if ( ! function_exists( 'follet_theme_support_post_formats' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_post_formats' );
/**
 * Enable support for Post Formats.
 *
 * @since 1.1
 */
function follet_theme_support_post_formats() {
	add_theme_support(
		'post-formats', apply_filters( 'follet_post_formats', array(
				'aside',
				'gallery',
				'image',
				'video',
				'quote',
				'link',
				'status',
				'audio',
				'chat',
			)
		)
	);
}
endif;

if ( ! function_exists( 'follet_theme_support_custom_background' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_custom_background' );
/**
 * Setup the WordPress core custom background feature.
 *
 * @uses   add_theme_support()
 * @since  1.1
 */
function follet_theme_support_custom_background() {
	add_theme_support( 'custom-background', apply_filters(
			'follet_custom_background_args', array(
				'default-color' => 'FFFFFF',
				'default-image' => '',
			)
		)
	);
}
endif;

if ( ! function_exists( 'follet_theme_support_html5' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_html5' );
/**
 * Enable support for HTML5 markup.
 *
 * @since 1.1
 * @uses  add_theme_support()
 */
function follet_theme_support_html5() {
	add_theme_support( 'html5', apply_filters( 'follet_html5_args', array(
		'comment-list',
		'search-form',
		'comment-form',
	) ) );
}
endif;

if ( ! function_exists( 'follet_theme_support_bootstrap' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_bootstrap' );
/**
 * Initialize default theme support.
 *
 * @uses   add_theme_support()
 * @since  1.1
 */
function follet_theme_support_bootstrap() {
	add_theme_support( 'bootstrap' );
}
endif;

if ( ! function_exists( 'follet_theme_support_infinite_scroll' ) ) :
add_action( 'follet_theme_support', 'follet_theme_support_infinite_scroll' );
/**
 * Add theme support for Infinite Scroll.
 *
 * @link  http://jetpack.me/support/infinite-scroll/
 *
 * @uses  add_theme_support()
 * @since 1.1
 */
function follet_theme_support_infinite_scroll() {
	add_theme_support( 'infinite-scroll', apply_filters(
			'follet_infinite_scroll', array(
				'container' => 'main',
				'footer'    => false,
			)
		)
	);
}
endif;
