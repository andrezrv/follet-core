<?php
/**
 * Follet Core.
 *
 * This file includes functions for theme support management.
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

if ( ! function_exists( 'follet_theme_support' ) ) :
add_filter( 'follet_theme_support', 'follet_theme_support' );
/**
 * Add default posts and comments RSS feed links to head.
 *
 * @since  1.1
 *
 * @param  array $theme_support List of supported features.
 * @return array
 */
function follet_theme_support( $theme_support ) {
	$theme_support = array_merge( $theme_support, apply_filters( 'follet_default_theme_support', array(
		/**
		 * Initialize Bootstrap support.
		 *
		 * @since 1.0
		 */
		'bootstrap',

		/**
		 * Add default posts and comments RSS feed links to head.
		 *
		 * @since 1.0
		 */
		'automatic-feed-links',

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @since 1.0
		 */
		'post-thumbnails',

		/**
		 * Enable support for all Post Formats.
		 *
		 * Hook into the `follet_post_formats` filter to change this.
		 *
		 * @since 1.0
		 */
		'post-formats' => apply_filters( 'follet_post_formats', array(
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
		),

		/**
		 * Setup the WordPress core custom background feature.
		 *
		 * @since 1.0
		 */
		'custom-background' => apply_filters( 'follet_custom_background_args', array(
				'default-color' => 'FFFFFF',
				'default-image' => '',
			)
		),

		/**
		 * Enable support for HTML5 markup.
		 *
		 * @since 1.0
		 */
		'html5' => apply_filters( 'follet_html5_args', array(
				'comment-list',
				'search-form',
				'comment-form',
			)
		),

		/**
		 * Add theme support for Infinite Scroll.
		 *
		 * @link  http://jetpack.me/support/infinite-scroll/
		 *
		 * @since 1.0
		 */
		'infinite-scroll' => apply_filters( 'follet_infinite_scroll', array(
				'container' => 'main',
				'footer'    => false,
			)
		),
	) ) );

	return $theme_support;
}
endif;
