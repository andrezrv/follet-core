<?php
/**
 * Follet Core.
 *
 * This file contains functions to manage loading of basic theme scripts.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'follet_scripts' ) ) :
add_filter( 'follet_scripts', 'follet_scripts', 10, 2 );
/**
 * Enqueue scripts and styles.
 *
 * @since  1.1
 *
 * @param  array  $scripts List of scripts to be registered.
 * @return array
 */
function follet_scripts( $scripts ) {
	$directory_uri    = follet_directory_uri();
	$bootstrap_active = _follet_bootstrap_active();

	$scripts = array_merge( $scripts, apply_filters( 'follet_default_scripts', array(
		/**
		 * Load Bootstrap JS.
		 */
		'follet-bootstrap-js' => array(
			'src'     => $directory_uri . '/includes/assets/bootstrap/js/bootstrap.min.js',
			'version' => '3.1.1.',
			'eval'    => $bootstrap_active,
		),
		'respond'             => array(
			'src'       => $directory_uri . '/includes/assets/respond/min/respond.min.js',
			'version'   => false,
			'in_footer' => false,
			'eval'      => $bootstrap_active,
		),

		/**
		 * Load Comment reply JS.
		 *
		 * @since 1.0
		 */
		'comment-reply' => array(
			'src'  => '',
			'eval' => ( is_singular() && comments_open() && get_option( 'thread_comments' ) ),
		),
	) ) );

	return $scripts;
}
endif;

if ( ! function_exists( 'follet_add_html5_shim' ) ) :
add_filter( 'wp_head', 'follet_add_html5_shim' );
/**
 * HTML5 shim, for IE6-8 support of HTML5 elements.
 *
 * @since 1.0
 */
function follet_add_html5_shim() {
	if ( _follet_bootstrap_active() ) {
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
