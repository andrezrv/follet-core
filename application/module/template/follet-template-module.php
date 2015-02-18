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
function follet_get_template_module( Follet_Template_Module $template = null ) {
	if ( ! $template ) {
        $template = Follet_Template_Module::get_instance();
    }

    return $template;
}
endif;
