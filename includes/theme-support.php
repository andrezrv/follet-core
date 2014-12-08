<?php
if ( ! function_exists( 'follet_do_theme_support' ) ) :
add_action( 'follet_theme_support', 'follet_do_theme_support' );
/**
 * Initialize default theme support.
 *
 * Default theme support includes:
 *
 * - automatic-feed-links
 * - post-thumbnails
 * - post-formats (all post formats)
 * - custom-background
 * - html5
 * - bootstrap (custom, for Bootstrap)
 * - infinite-scroll (for Jetpack)
 *
 * @uses   add_theme_support()
 * @return void
 * @since  1.0
 */
function follet_do_theme_support() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Enable support for Post Formats.
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

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters(
			'follet_custom_background_args', array(
				'default-color' => 'FFFFFF',
				'default-image' => '',
			)
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', apply_filters( 'follet_html5_args', array(
		'comment-list',
		'search-form',
		'comment-form',
	) ) );

	// Enable support for Bootstrap.
	add_theme_support( 'bootstrap' );

	/**
	 * Add theme support for Infinite Scroll.
	 *
	 * @see {@link http://jetpack.me/support/infinite-scroll/}
	 */
	add_theme_support( 'infinite-scroll', apply_filters(
			'follet_infinite_scroll', array(
				'container' => 'main',
				'footer'    => false,
			)
		)
	);
}
endif;
