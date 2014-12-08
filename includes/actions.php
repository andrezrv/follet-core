<?php
/**
* Follet_Actions
*
* Setup of the Follet theme happens here.
*
* @package Follet_Core
* @since   1.0
*/

if ( ! function_exists( 'follet_load_textdomain' ) ) :
add_action( 'after_setup_theme', 'follet_load_textdomain' );
/**
 * Make theme available for translation.
 *
 * @uses   load_theme_textdomain()
 * @return void
 * @since  1.0
 */
function follet_load_textdomain() {
	global $follet;

	load_theme_textdomain( $follet->textdomain, apply_filters(
			'follet_textdomain_location',
			$follet->template_directory . '/languages'
		)
	);
}
endif;

if ( ! function_exists( 'follet_theme_support' ) ) :
add_action( 'after_setup_theme', 'follet_theme_support' );
/**
 * Initialize default theme support.
 *
 * Default theme support includes:
 * automatic-feed-links
 * post-thumbnails
 * post-formats (all post formats)
 * custom-background
 * html5
 * bootstrap (custom, for Bootstrap)
 * infinite-scroll (for Jetpack)
 *
 * @uses   add_theme_support()
 * @return void
 * @since  1.0
 */
function follet_theme_support() {
	// Process actions before theme support. Use it to register new theme support.
	do_action( 'follet_theme_support' );

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

	// Process actions after theme support. Use to remove theme support.
	do_action( 'follet_after_theme_support' );
}
endif;

if ( ! function_exists( 'follet_enqueue_scripts' ) ) :
add_action( 'wp_enqueue_scripts', 'follet_enqueue_scripts' );
/**
 * Enqueue scripts and styles.
 *
 * @return void
 * @since  1.0
 */
function follet_enqueue_scripts() {
	global $follet;

	// Bootstrap styles.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_style(
			'follet-bootstrap-styles',
			apply_filters(
				'follet_bootstrap_uri',
				$follet->directory_uri . '/includes/assets/bootstrap/css/bootstrap.min.css'
			)
		);
	}

	// Main style.
	if ( $follet->get_current( 'load_main_style' ) ) {
		wp_enqueue_style(
			'follet-style',
			get_stylesheet_uri(),
			apply_filters( 'follet_main_style_dependencies', array() ),
			$follet->theme_version
		);
	}

	// Bootstrap JS.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_script(
			'follet-bootstrap-js',
			$follet->directory_uri . '/includes/assets/bootstrap/js/bootstrap.min.js',
			array( 'jquery' ),
			'3.1.1',
			true
		);
	}

	// Respond JS.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_script(
			'respond',
			$follet->directory_uri . '/includes/assets/respond/min/respond.min.js',
			array( 'jquery' ),
			false,
			false
		);
	}

	// Comment reply JS.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;

if ( ! function_exists( 'follet_add_editor_styles' ) ) :
add_action( 'init', 'follet_add_editor_styles' );
function follet_add_editor_styles() {
	global $follet;

	// Bootstrap styles.
	if ( get_theme_support( 'bootstrap' ) ) {
		add_editor_style( $follet->directory_uri . '/includes/assets/bootstrap/css/bootstrap.min.css' );
	}

	// Main style.
	if ( $follet->get_current( 'load_main_style' ) ) {
		add_editor_style( get_stylesheet_uri() );
	}
}
endif;

if ( ! function_exists( 'follet_add_ie_compatibility_modes' ) ) :
add_filter( 'wp_head', 'follet_add_ie_compatibility_modes' );
/**
 * Add support for IE compatibility modes.
 *
 * {@link http://getbootstrap.com/getting-started/#support-ie-compatibility-modes}
 *
 * @since  1.0
 * @return void
 */
function follet_add_ie_compatibility_modes() {
	if ( get_theme_support( 'bootstrap' ) ) {
		$content = '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . "\n";

		echo $content;
	}
}
endif;

if ( ! function_exists( 'follet_add_html5_shim' ) ) :
add_filter( 'wp_head', 'follet_add_html5_shim' );
/**
 * HTML5 shim, for IE6-8 support of HTML5 elements.
 *
 * @since  1.0
 * @return void
 */
function follet_add_html5_shim() {
	if ( get_theme_support( 'bootstrap' ) ) {
		ob_start();
		?>
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	}
}
endif;

if ( ! function_exists( 'follet_add_to_author_profile' ) ) :
add_filter( 'user_contactmethods', 'follet_add_to_author_profile' );
/**
 * Add contact methods to author profile.
 *
 * This is disabled by default, since the best practice is adding the contact
 * methods through a plugin. You can enable this function with the following code:
 *
	add_action( 'follet_options_registered', 'follet_add_to_author_profile_enable' );
	function follet_add_to_author_profile() {
		follet_register_option( 'contact_methods_show', true );
	}
 *
 * @since  1.0
 *
 * @param  array $contact_methods List of current contact methods.
 * @return array                  Mixed list of contact methods.
 */
function follet_add_to_author_profile( $contact_methods ) {
	if ( follet_get_option( 'contact_methods_show' ) ) {
		$supported_contact_methods = follet_get_option( 'contact_methods' );

		foreach ( $supported_contact_methods as $key => $value ) {
			$contact_methods[ $key ] = $value;
		}
	}

	return $contact_methods;
}
endif;
