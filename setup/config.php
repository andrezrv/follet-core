<?php
if ( ! function_exists( 'follet_do_config' ) ) :
add_filter( 'follet_config', 'follet_do_config' );
/**
 * Set configuration values.
 *
 * @since  2.0
 *
 * @return array
 */
function follet_do_config() {
	$values = array(
		'app_name'       => 'Follet Core',
		'app_dir'        => ( defined( 'FOLLET_DIR' ) && FOLLET_DIR ) ? FOLLET_DIR : dirname( __FILE__ ),
		'app_engine_dir' => ( defined( 'FOLLET_ENGINE_DIR' ) && FOLLET_ENGINE_DIR ) ? FOLLET_ENGINE_DIR : dirname( __FILE__ ),
		'app_lib_dir'    => ( defined( 'FOLLET_LIB_DIR' ) && FOLLET_LIB_DIR ) ? FOLLET_LIB_DIR : dirname( __FILE__ ),
		'prefix'         => 'follet_',
	);

	return $values;
}
endif;