<?php
if ( ! function_exists( 'follet_add_default_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_default_editor_styles', 20 );
function follet_add_default_editor_styles() {
	// Main style.
	if ( follet_get_option( 'load_main_style' ) ) {
		add_editor_style( get_stylesheet_uri() );
	}
}
endif;

if ( ! function_exists( 'follet_add_bootstrap_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_bootstrap_editor_styles', 10 );
function follet_add_bootstrap_editor_styles() {
	// Bootstrap styles.
	if ( get_theme_support( 'bootstrap' ) ) {
		add_editor_style( follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css' );
	}
}
endif;
