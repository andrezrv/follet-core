<?php
if ( ! function_exists( 'follet_modules' ) ) :
add_filter( 'follet_modules', 'follet_modules' );
/**
 * Initialize and register default Follet Core modules.
 *
 * @since 1.1
 */
function follet_modules( $modules ) {
	$modules = array_merge( $modules, apply_filters( 'follet_default_modules', array(
		'options'  => Follet_Options_Module::get_instance(),
		'styles'  => Follet_Styles_Module::get_instance(),
		'editor_styles' => Follet_Editor_Styles_Manager::get_instance(),
		'scripts' => Follet_Scripts_Module::get_instance(),
		'customizer' => Follet_Customizer_Module::get_instance(),
		'menus' => Follet_Menus_Manager::get_instance(),
		'sidebars' => Follet_Sidebars_Manager::get_instance(),
		'theme-support' => Follet_Theme_Support_Module::get_instance(),
	) ) );

	return $modules;
}
endif;
