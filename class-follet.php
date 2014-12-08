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
class Follet {

	/**
	 * Instance for singleton.
	 *
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * Utilitary property to store theme options.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $_options = array();

	/**
	 * Store $template_directory to prevent overhead.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory;

	/**
	 * Store $template_directory_uri to prevent overhead.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory_uri;

	/**
	 * Follet Core absolute path.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $directory;

	/**
	 * Follet Core directory URI.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $directory_uri;

	/**
	 * Theme text domain.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $textdomain;

	/**
	 * Current version of package.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $version = '1.1';

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

		// Obtain theme values.
		$theme = wp_get_theme();

		// Process object values.
		$this->theme_version          = $theme->get( 'Version' );
		$this->textdomain             = $theme->get( 'TextDomain' );
		$this->template_directory     = get_template_directory();
		$this->template_directory_uri = get_template_directory_uri();
		$this->directory              = $this->get_directory();
		$this->directory_uri          = $this->get_directory_uri();

		// Process global variables.
		$this->process_globals();

		// $this->load_dependencies();

		// Process actions after setup.
		do_action( 'follet_after_setup' );
	}

	/**
	 * Obtain self instance of this class.
	 *
	 * @since  1.1
	 * @return Follet Self instance of this class.
	 */
	final public static function get_instance() {
		self::load_dependencies();

		static $instances = array();

		$called_class = _follet_get_called_class();

		if ( ! isset( $instances[ $called_class ] ) ) {
			$instances[ $called_class ] = new $called_class();
		}

		return $instances[ $called_class ];
	}

	/**
	 * Prevent clone() for an instance of this class.
	 *
	 * @return void
	 * @since  1.1
	 */
	public function __clone() {
		$class = get_class( $this );
		$error = __( 'Invalid operation: you cannot clone an instance of ', $this->textdomain ) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

	/**
	 * Prevent unserialize() for an instance of this class.
	 *
	 * @return void
	 * @since  1.1
	 */
	public function __wakeup() {
		$class = get_class( $this );
		$error = __( 'Invalid operation: you cannot unserialize an instance of ', $this->textdomain ) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

	/**
	 * Process global variables.
	 *
	 * @since 1.1
	 */
	private function process_globals() {
		global $follet;

		$follet = $this;
	}

	/**
	 * Load all dependencies that need to be active before the class is instantiated.
	 *
	 * @see   Follet::get_instance()
	 * @uses  Follet::load_library()
	 *
	 * @since 1.1
	 */
	private static function load_dependencies() {
		$dirname = dirname( __FILE__ );

		// Add all files inside `./includes`.
		self::load_library( $dirname . '/includes/' );
	}

	/**
	 * Get Follet Core path.
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	public function get_directory() {
		return $this->template_directory . str_replace( $this->template_directory, '', dirname( __FILE__ ) );
	}

	/**
	 * Get Follet Core directory URI.
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
	 * @since  1.0
	 *
	 * @param  string $name    Name of the new option.
	 * @param  mixed  $default Default value for the option.
	 * @param  mixed  $current Current value of the option.
	 * @return void
	 */
	public function register_option( $name, $default, $current = false ) {
		$this->_options[ $name ] = array(
			'default' => $default,
			'current' => $current ? : get_theme_mod( $name, $default ),
		);
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
		unset( $this->_options[ $name ] );
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

		if ( isset( $this->_options[ $name ]['default'] ) ) {
			$default = $this->_options[ $name ]['default'];
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

		if ( isset( $this->_options[ $name ]['current'] ) ) {
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
		return isset( $this->_options[ $name ] );
	}

	/**
	 * Load a PHP library given a file or a folder.
	 *
	 * @since 1.1
	 *
	 * @param string $library Name of folder or php file.
	 */
	public static function load_library( $library ){
		if ( is_dir( $library ) ) {
			if ( $d = opendir( $library ) ) {
				while ( ( $file = readdir( $d ) ) !== false ) {
					if( stristr( $file , '.php' ) !== false ) {
						require_once( $library . $file );
					}
				}
			}
		} elseif ( ( is_file( $library ) ) && ( is_readable( $library ) ) ) {
			if ( stristr( $library , '.php' ) !== false ) {
				require_once( $library );
			}
		}
	}
}
endif;
