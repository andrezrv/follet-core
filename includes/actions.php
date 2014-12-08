<?php
if ( ! function_exists( 'follet_editor_styles' ) ) :
add_action( 'init', 'follet_editor_styles' );
/**
 * Run a hook to register editor styles to.
 *
 * @since 1.1
 */
function follet_editor_styles() {
	do_action( 'follet_editor_styles' );
}
endif;

if ( ! function_exists( 'follet_enqueue_scripts' ) ) :
add_action( 'wp_enqueue_scripts', 'follet_enqueue_scripts' );
/**
 * Run a hook to register custom scripts to.
 *
 * @since 1.1
 */
function follet_enqueue_scripts() {
	do_action( 'follet_enqueue_scripts' );
}
endif;

if ( ! function_exists( 'follet_enqueue_styles' ) ) :
add_action( 'wp_enqueue_scripts', 'follet_enqueue_styles' );
/**
 * Run a hook to register custom styles to.
 *
 * @since 1.1
 */
function follet_enqueue_styles() {
	do_action( 'follet_enqueue_styles' );
}
endif;

if ( ! function_exists( 'follet_theme_support' ) ) :
add_action( 'after_setup_theme', 'follet_theme_support' );
/**
 * Run a hook to register theme support to.
 *
 * @since 1.1
 */
function follet_theme_support() {
	do_action( 'follet_theme_support' );
}
endif;
