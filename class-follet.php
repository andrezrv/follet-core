<?php
if ( ! class_exists( 'Follet' ) ) :
/**
 * Follet
 *
 * Main class for theme management.
 *
 * This is a pluggable class.
 *
 * @package Follet_Core
 * @since   1.0
 */
class Follet extends Follet_Singleton {

	/**
	 * Instance for singleton.
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * Utilitary property to store theme options.
	 * @internal
	 * @var    array
	 * @since  1.0
	 */
	protected $_options = array();

	/**
	 * Store template_directory to prevent overhead.
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory;

	/**
	 * Store template_directory_uri to prevent overhead.
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory_uri;

	/**
	 * Follet absolute path.
	 * @var    string
	 * @since  1.0
	 */
	public $directory;

	/**
	 * Follet directory URI.
	 * @var    string
	 * @since  1.0
	 */
	public $directory_uri;

	/**
	 * Theme textdomain.
	 * @var    string
	 * @since  1.0
	 */
	public $textdomain;

	/**
	 * Current version of package.
	 * @var    string
	 * @since  1.0
	 */
	protected $version = '1.0';

	/**
	 * Current version of the supported theme.
	 * @var    string
	 * @since  1.0
	 */
	public $theme_version = '1.0';

	/**
	 * Initialize Follet.
	 *
	 * @since  1.0
	 */
	protected function __construct() {

		// Process actions before setup.
		do_action( 'follet_setup' );

		$this->theme_version = wp_get_theme()->get( 'Version' );
		$this->textdomain = wp_get_theme()->get( 'TextDomain' );
		$this->template_directory = get_template_directory();
		$this->template_directory_uri = get_template_directory_uri();
		$this->directory = $this->get_directory();
		$this->directory_uri = $this->get_directory_uri();

		// Process actions after setup.
		do_action( 'follet_after_setup' );

	}

	/**
	 * Get Follet path.
	 *
	 * @return string
	 * @since  1.0
	 */
	public function get_directory() {
		return $this->template_directory . str_replace( $this->template_directory, '', dirname( __FILE__ ) );
	}

	/**
	 * Get Follet directory URI.
	 *
	 * @return string
	 * @since  1.0
	 */
	public function get_directory_uri() {
		return $this->template_directory_uri . str_replace( $this->template_directory, '', dirname( __FILE__ ) );
	}

	/**
	 * Add an option to $this->_options.
	 *
	 * @param  string $name    Name of the new option.
	 * @param  mixed  $default Default value for the option.
	 * @param  mixed  $current Current value of the option.
	 * @return void
	 * @since  1.0
	 */
	public function register_option( $name, $default, $current = false ) {
		$this->_options[$name] = array(
			'default' => $default,
			'current' => $current ? $current : get_theme_mod( $name, $default ),
		);
	}

	/**
	 * Remove option from $this->_options.
	 *
	 * @param  string $name Name of the option.
	 * @return void
	 * @since  1.0
	 */
	public function remove_option( $name ) {
		unset( $this->_options[$name] );
	}

	/**
	 * Get the default value of an option.
	 *
	 * @param  string $name Name of the option.
	 * @return mixed        Default value of the option.
	 * @since  1.0
	 */
	public function get_default( $name ) {
		$default = '';
		if ( isset( $this->_options[$name]['default'] ) ) {
			$default = $this->_options[$name]['default'];
		}
		$default = apply_filters( 'follet_option_default', $default, $name );
		$default = apply_filters( 'follet_option_default_' . $name, $default );
		return $default;
	}

	/**
	 * Get the current value of an option.
	 *
	 * @param  string $name Name of the option.
	 * @return mixed        Current value of the option.
	 * @since  1.0
	 */
	public function get_current( $name ) {
		$current = '';
		if ( isset( $this->_options[$name]['current'] ) ) {
			$current = get_theme_mod( $name, $this->get_default( $name ) );
		}
		$current = apply_filters( 'follet_option_current', $current, $name );
		$current = apply_filters( 'follet_option_current_' . $name, $current );
		return $current;
	}

	/**
	 * Check if an option already exists.
	 *
	 * @param  string  $name Unique ID of the option.
	 * @return boolean
	 * @return void
	 * @since  1.0
	 */
	public function option_exists( $name ) {
		if ( isset( $this->_options[$name] ) ) {
			return true;
		}
		return false;
	}

}
endif;
