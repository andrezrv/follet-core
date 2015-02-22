<?php
/**
 * Follet Core.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.1
 */
namespace Follet\Module;

/**
 * Declare namespace dependencies.
 *
 * @since 1.1
 */
use Follet\Application\ModuleAbstract;

/**
 * Class OptionsModule
 *
 * Manage options retrieving and registration.
 *
 * @package Follet_Core
 * @since   1.1
 */
class OptionsModule extends ModuleAbstract {
	/**
	 * Utilitary property to store theme options.
	 *
	 * @var    array
	 *
	 * @since  1.1
	 */
	protected $options = array();

	/**
	 * Current Customizer module object.
	 *
	 * @var   CustomizerModule
	 *
	 * @since 1.1
	 */
	protected $customizer = null;

	/**
	 * Setup module hooks.
	 *
	 * @since 1.1
	 */
	public function register() {
		/**
		 * Save reference to Customizer module before registering options.
		 *
		 * @since 1.1
		 */
		add_action( 'init', array( $this, 'set_customizer' ) );

		/**
		 * Check that $this->customizer has been assigned.
		 *
		 * @since 1.1
		 */
		add_action( 'init', array( $this, 'check_customizer' ) );

		/**
		 * Register theme options when initializing WordPress.
		 *
		 * @since 1.1
		 */
		add_action( 'init', array( $this, 'register_options' ) );
	}

	/**
	 * Save reference to Customizer module.
	 *
	 * @since 1.1
	 */
	public function set_customizer() {
		// Check all initialized modules until we find the one we're looking for.
		foreach ( ModuleManager::get_instance()->modules as $module ) {
			if ( $module instanceof CustomizerModule ) {
				// Save reference to found module.
				$this->customizer = $module;

				// End loop.
				break;
			}
		}
	}

	/**
	 * Check that $this->customizer has been assigned.
	 *
	 * @since 1.1
	 */
	public function check_customizer() {
		if ( ! $this->customizer ) {
			wp_die( __( 'The Options module depends on the Customizer module to be initialized to work properly.', follet_textdomain() ) );
		}
	}

	/**
	 * Add an option to $this->options.
	 *
	 * @since  1.1
	 *
	 * @param  string $name    Name of the new option.
	 * @param  mixed  $default Default value for the option.
	 * @param  mixed  $current Current value of the option.
	 */
	public function register_option( $name, $default, $current = false ) {
		$atts = $default;
		$default = is_array( $atts ) && isset( $atts['default'] ) ? $atts['default'] : $atts;

		$this->options[ $name ] = array(
			'default' => $default,
			'current' => $current ? : get_theme_mod( $name, $default ),
		);

		if ( ! empty( $atts['section'] ) ) {
			$this->customizer->add_setting( $name, $atts );
		}
	}

	/**
	 * Bulk-register all theme options.
	 *
	 * @since 1.1
	 *
	 * @uses  self::register_option()
	 */
	public function register_options() {
		$options = apply_filters( 'follet_options', $this->options );

		// Return early if we have no options set.
		if ( empty( $options ) ) {
			return;
		}

		foreach ( $options as $name => $atts ) {
			if ( ! $this->option_exists( $name ) ) {
				// Set default value for the option.
				$default = empty( $atts['default'] ) ? $atts : $atts['default'];

				$this->register_option( $name, $atts, get_theme_mod( $name, $default ) );
			}
		}
	}

	/**
	 * Remove option from $this->_options.
	 *
	 * @since  1.0
	 *
	 * @param  string $name Name of the option.
	 * @return void
	 */
	public function remove_option( $name ) {
		unset( $this->options[ $name ] );
	}

	/**
	 * Get the default value of an option.
	 *
	 * @since  1.0
	 *
	 * @param  string $name Name of the option.
	 * @return mixed        Default value of the option.
	 */
	public function get_default( $name ) {
		$default = '';

		if ( isset( $this->options[ $name ]['default'] ) ) {
			$default = $this->options[ $name ]['default'];
		}

		$default = apply_filters( 'follet_option_default', $default, $name );
		$default = apply_filters( 'follet_option_default_' . $name, $default );

		return $default;
	}

	/**
	 * Get the current value of an option.
	 *
	 * @since  1.0
	 *
	 * @param  string $name Name of the option.
	 * @return mixed        Current value of the option.
	 */
	public function get_current( $name ) {
		$current = '';

		if ( isset( $this->options[ $name ]['current'] ) ) {
			$current = get_theme_mod( $name, $this->get_default( $name ) );
		}

		$current = apply_filters( 'follet_option_current', $current, $name );
		$current = apply_filters( 'follet_option_current_' . $name, $current );

		return $current;
	}

	/**
	 * Obtain current value of an option. Kind-of an alias for this::get_current().
	 *
	 * @since  1.1
	 *
	 * @param  string $name Name of the option.
	 * @return mixed        Current value of the option.
	 */
	public function get_option( $name ) {
		$current = $this->get_current( $name );

		$current = apply_filters( 'follet_option', $current, $name );
		$current = apply_filters( 'follet_option_' . $name, $current );

		return $current;
	}

	/**
	 * Check if an option already exists.
	 *
	 * @since  1.0
	 *
	 * @param  string  $name Unique ID of the option.
	 * @return boolean
	 */
	public function option_exists( $name ) {
		return isset( $this->options[ $name ] );
	}
}
