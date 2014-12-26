<?php
/**
 * Follet Core.
 *
 * Extend WP_Customize_Control to show text-based tips for controls.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Follet_Plain_Text_Control' ) ) :
/**
 * Class Follet_Plain_Text_Control
 *
 * Class to create a custom date picker.
 *
 * @since 1.0
 */
class Follet_Plain_Text_Control extends WP_Customize_Control {
    /**
     * Render the content on the theme customizer page
     *
     * @since 1.0
     */
    public function render_content() {
        echo $this->value();
    }

}
endif;
