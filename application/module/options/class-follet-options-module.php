<?php
if ( ! class_exists( 'Follet_Options_Module' ) ) :
class Follet_Options_Module extends Follet_Singleton implements Follet_ModuleInterface {
	/**
	 * Utilitary property to store theme options.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $options = array();

	protected function __construct() {
		$this->register();
		$this->process_globals();
	}

	private function process_globals() {
		$GLOBALS['follet_options_manager'] = $this;
		$GLOBALS['follet_options'] = $this->options;
	}

	/**
	 * Register theme options on `init` action.
	 *
	 * @since 1.1
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_options' ) );
	}

	/**
	 * Add an option to $this->_options.
	 *
	 * @since  1.0
	 *
	 * @global Follet_Customizer_Module $follet_customizer
	 *
	 * @param  string $name    Name of the new option.
	 * @param  mixed  $default Default value for the option.
	 * @param  mixed  $current Current value of the option.
	 */
	protected function register_option( $name, $default, $current = false ) {
		$atts = $default;
		$default = is_array( $atts ) && isset( $atts['default'] ) ? $atts['default'] : $atts;

		$this->options[ $name ] = array(
			'default' => $default,
			'current' => $current ? : get_theme_mod( $name, $default ),
		);

		if ( ! empty( $atts['section'] ) ) {
			global $follet_customizer;
			$follet_customizer->add_customizer_setting( $name, $atts );
		}
	}

	/**
	 * Bulk-register all theme options.
	 *
	 * @since 1.1
	 *
	 * @uses   self::register_option()
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
endif;