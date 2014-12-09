<?php
if ( ! function_exists( 'follet_bootstrap_styles' ) ) :
add_action( 'follet_enqueue_styles', 'follet_bootstrap_styles' );
/**
 * Enqueue Bootstrap styles.
 *
 * @return void
 * @since  1.1
 */
function follet_bootstrap_styles() {
	// Bootstrap styles.
	if ( _follet_bootstrap_active() ) {
		wp_enqueue_style(
			'follet-bootstrap-styles',
			apply_filters(
				'follet_bootstrap_uri',
				follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css'
			)
		);
	}
}
endif;

if ( ! function_exists( 'follet_main_styles' ) ) :
add_action( 'follet_enqueue_styles', 'follet_main_styles' );
/**
 * Enqueue main stylesheet.
 *
 * @since 1.1
 */
function follet_main_styles() {
	// Main style.
	if ( follet_get_option( 'load_main_style' ) ) {
		wp_enqueue_style(
			'follet-style',
			get_stylesheet_uri(),
			apply_filters( 'follet_main_style_dependencies', array() ),
			follet( 'theme_version' )
		);
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
	if ( _follet_bootstrap_active() ) {
		$content = '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . "\n";

		echo $content;
	}
}
endif;
