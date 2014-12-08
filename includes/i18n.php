<?php
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
	load_theme_textdomain( follet( 'textdomain' ), apply_filters(
			'follet_textdomain_location',
			follet( 'template_directory' ) . '/languages'
		)
	);
}
endif;
