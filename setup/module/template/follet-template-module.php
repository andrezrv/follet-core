<?php
if ( ! function_exists( 'follet_get_template_module' ) ) :
add_filter( 'follet_get_template_module', 'follet_get_template_module' );
/**
 * Initialize Template module.
 *
 * @since  1.1
 *
 * @param  Follet_Template_Module $template
 *
 * @return Follet_Template_Module
 */
function follet_get_template_module( \Follet\Application\ModuleAbstract $template = null ) {
	if ( ! $template ) {
        $template = Follet\Module\TemplateModule::get_instance();
    }

    return $template;
}
endif;
