<?php
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
