<?php
/**
 * Follet_Plain_Text_Control
 *
 * Extend WP_Customize_Control to show text-based tips for controls.
 *
 * @package Follet_Core
 * @since   1.0
 */
if ( class_exists( 'WP_Customize_Control' ) and ! class_exists( 'Follet_Plain_Text_Control' ) ) :
/**
 * Class to create a custom date picker
 */
class Follet_Plain_Text_Control extends WP_Customize_Control {
    /**
    * Render the content on the theme customizer page
    */
    public function render_content() {
        echo $this->value();
    }

}
endif;
