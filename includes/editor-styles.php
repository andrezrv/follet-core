<?php
if ( ! function_exists( 'follet_add_editor_styles' ) ) :
add_action( 'init', 'follet_add_editor_styles' );
function follet_add_editor_styles() {
	// Bootstrap styles.
	if ( get_theme_support( 'bootstrap' ) ) {
		add_editor_style( follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css' );
	}

	// Main style.
	if ( follet_get_option( 'load_main_style' ) ) {
		add_editor_style( get_stylesheet_uri() );
	}
}
endif;