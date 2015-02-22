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
		'options' => \Follet\Module\OptionsModule::get_instance(),
		'styles'          => \Follet\Module\StylesModule::get_instance(),
		'editor_styles'   => \Follet\Module\EditorStylesModule::get_instance(),
		'scripts'         => \Follet\Module\ScriptsModule::get_instance(),
		'customizer'      => \Follet\Module\CustomizerModule::get_instance(),
		'menus'           => \Follet\Module\MenuModule::get_instance(),
		'sidebars'        => \Follet\Module\SidebarModule::get_instance(),
		'theme_support'   => \Follet\Module\ThemeSupportModule::get_instance(),
		'template' => \Follet\Module\TemplateModule::get_instance(),
		'ajax' => \Follet\Module\AjaxModule::get_instance(),
		'admin' => \Follet\Module\AdminModule::get_instance(),
		'theme' => \Follet\Module\ThemeModule::get_instance(),
	) ) );

	return $modules;
}
endif;

if ( ! function_exists( 'follet_modules_instance' ) ) :
add_action( 'follet_before_setup', 'follet_modules_instance' );
/**
 * Initialize Module Manager instance.
 *
 * @since 1.0.0
 */
function follet_modules_instance() {
	Follet\Module\ModuleManager::get_instance()->initialize();
}
endif;