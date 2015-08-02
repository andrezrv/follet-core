<?php
/**
 * Follet Core.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.0
 */
namespace Follet\Module;

/**
 * Load dependencies.
 *
 * @since 1.1
 */
use Follet\Application\ModuleAbstract;

/**
 * Class SidebarModule
 *
 * Main class for sidebar management.
 *
 * @package Follet_Core
 * @since   1.1
 */
class SidebarModule extends ModuleAbstract {
	/**
	 * List of sidebars for the current theme.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $sidebars = array();

	/**
	 * Obtain list of actions hooked by class methods.
	 *
	 * @since  1.1
	 *
	 * @return array
	 */
	public function get_actions() {
		return array(
			'widgets_init' => 'register_sidebars',
		);
	}

	/**
	 * Register a sidebar.
	 *
	 * @since 1.1
	 *
	 * @uses  register_sidebar()
	 *
	 * @param string $id   Internal name for the new sidebar.
	 * @param array  $args List of arguments to register the new sidebar.
	 */
	public function register_sidebar( $id, $args ) {
		// Allow name to be passed as $args in the form of a string.
		if ( ! is_array( $args ) ) {
			$name = $args;
			unset( $args );
			$args['name'] = $name;
		}

		$defaults = apply_filters( $this->config->prefix . 'register_sidebar_defaults', array(
			'id'            => $id,
			'name'          => __( 'Sidebar', follet_textdomain() ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><hr class="separator" />',
		), $args );

		$args = wp_parse_args( $args, $defaults );

		register_sidebar( $args );
	}

	/**
	 * Register widget areas.
	 *
	 * @since 1.1
	 */
	public function register_sidebars() {
		$this->sidebars = apply_filters( $this->config->prefix . 'sidebars', $this->sidebars );

		if ( ! empty( $this->sidebars ) ) {
			foreach ( $this->sidebars as $name => $args ) {
				$this->register_sidebar( $name, $args );
			}
		}
	}
}
