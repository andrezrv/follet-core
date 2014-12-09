<?php
if ( ! function_exists( 'follet_add_bootstrap_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_bootstrap_editor_styles' );
function follet_add_bootstrap_editor_styles() {
	// Bootstrap styles.
	if ( _follet_bootstrap_active() ) {
		add_editor_style( follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css' );
	}
}
endif;

if ( ! function_exists( 'follet_add_main_editor_styles' ) ) :
add_action( 'follet_editor_styles', 'follet_add_main_editor_styles' );
function follet_add_main_editor_styles() {
	// Main style.
	if ( file_exists( $file = follet( 'template_directory' ) . '/editor-style.css' ) ) {
		add_editor_style( $file );
	} elseif ( follet_get_option( 'load_main_style' ) ) {
		add_editor_style( get_stylesheet_uri() );
	}
}
endif;
