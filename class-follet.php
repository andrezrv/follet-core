<?php
if ( ! class_exists( 'Follet' ) ) :
/**
 * Class Follet
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
	 * Customizer interfacing object for API interactions.
	 *
	 * @var   Customizer_Library
	 * @since 1.1
	 */
	protected $customizer;

	/**
	 * List of sections to be initialized via Customizer.
	 *
	 * @array  var
	 * @since  1.1
	 */
	protected $customizer_sections;

	/**
	 * List of controls to be initialized via Customizer.
	 *
	 * @array  var
	 * @since  1.1
	 */
	protected $customizer_controls;

	/**
	 * List of settings to be initialized via Customizer.
	 *
	 * @array  var
	 * @since  1.1
	 */
	protected $customizer_settings;

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
	 * Check if we're in an AJAX context.
	 *
	 * @var   bool
	 * @since 1.1
	 */
	public $doing_ajax;

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
		$this->customizer             = Customizer_Library::instance();
		$this->theme_version          = $theme->get( 'Version' );
		$this->doing_ajax             = defined( 'DOING_AJAX' ) && DOING_AJAX;
		$this->textdomain             = $theme->get( 'TextDomain' );
		$this->template_directory     = get_template_directory();
		$this->template_directory_uri = get_template_directory_uri();
		$this->directory              = $this->get_directory();
		$this->directory_uri          = $this->get_directory_uri();

		// Process global variables.
		$this->process_globals();

		// Process theme options.
		$this->process_options();

		// Process customizer settings.
		$this->process_customizations();

		// Process actions after setup.
		do_action( 'follet_after_setup' );
	}

	/* ========================================================================
	   Singleton Class Methods.
	   ===================================================================== */

	/**
	 * Obtain self instance of this class.
	 *
	 * @since  1.1
	 * @return Follet Self instance of this class.
	 */
	final public static function get_instance() {
		// Load all dependencies that need to be active before the class is instantiated.
		self::load_dependencies();

		static $instances = array();

		$called_class = self::get_called_class();

		if ( ! isset( $instances[ $called_class ] ) ) {
			$instances[ $called_class ] = new $called_class();
		}

		return $instances[ $called_class ];
	}

	/**
	 * Add support for get_called_class in PHP < 5.3
	 *
	 * @since  1.1
	 *
	 * @return string Class name.
	 */
	private static function get_called_class() {
		$bt = debug_backtrace();
		$l = 0;
		do {
			$l ++;
			$lines      = file( $bt[ $l ]['file'] );
			$callerLine = $lines[ $bt[ $l ]['line'] - 1 ];
			preg_match( '/([a-zA-Z0-9\_]+)::' . $bt[ $l ]['function'] . '/', $callerLine, $matches );
		} while ( $matches[1] === 'parent' && $matches[1] );

		return $matches[1];
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

	/* ========================================================================
	   Basic Theme Processes.
	   ===================================================================== */

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
	 * Register theme options on `init` action.
	 *
	 * @since 1.1
	 */
	private function process_options() {
		add_action( 'init', array( $this, 'register_options' ) );
	}

	/**
	 * Process theme customization settings on `customize_register` action.
	 *
	 * @since 1.1
	 */
	private function process_customizations() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
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

		$dependencies = array(
			$dirname . '/includes/',                    // All files inside `./includes`.
			$dirname . '/includes/navigation/',         // All files inside `./includes/navigation`.
			$dirname . '/includes/customizer/',         // All files inside `./includes/customizer`.
			$dirname . '/includes/customizer-library/', // All files inside `./includes/customizer-library`.
		);

		$dependencies = apply_filters( 'follet_dependencies', $dependencies );

		foreach ( $dependencies as $library ) {
			self::load_library( $library );
		}
	}

	/* ========================================================================
	   Methods for Paths Management.
	   ===================================================================== */

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

	/* ========================================================================
	   Methods for Theme Options Management.
	   ===================================================================== */

	/**
	 * Add an option to $this->_options.
	 *
	 * @since  1.0
	 *
	 * @param  string $name    Name of the new option.
	 * @param  mixed  $default Default value for the option.
	 * @param  mixed  $current Current value of the option.
	 */
	public function register_option( $name, $default, $current = false ) {
		$atts = $default;
		$default = is_array( $atts ) && isset( $atts['default'] ) ? $atts['default'] : $atts;

		$this->_options[ $name ] = array(
			'default' => $default,
			'current' => $current ? : get_theme_mod( $name, $default ),
		);

		if ( ! empty( $atts['section'] ) ) {
			$this->add_customizer_setting( $name, $atts );
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
		$options = apply_filters( 'follet_options', $this->_options );

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

	/* ========================================================================
	   Methods for Customizer Management.
	   ===================================================================== */

	/**
	 * Add a settings section to be initialized via Customizer.
	 *
	 * @since 1.1
	 *
	 * @param string $name Internal name for the section.
	 * @param array  $atts Section attributes.
	 */
	public function add_customizer_section( $name, $atts ) {
		$this->customizer_sections[ $name ] = $atts;
	}

	/**
	 * Add a settings control to be initialized via Customizer.
	 *
	 * @since 1.1
	 *
	 * @param string $name Internal name for the control.
	 * @param array  $atts Control attributes.
	 */
	public function add_customizer_control( $name, $atts ) {
		$this->customizer_controls[ $name ] = $atts;
	}

	/**
	 * Add a setting to be initialized via Customizer.
	 *
	 * @since 1.1
	 *
	 * @param string $name Internal name for setting.
	 * @param array  $atts Setting attributes.
	 */
	public function add_customizer_setting( $name, $atts ) {
		$this->customizer_settings[ $name ] = $atts;
	}

	/**
	 * Initialize sections, controls and settings via Customizer.
	 *
	 * @since 1.1
	 *
	 * @uses  self::customizer->add_options() (AKA Customizer_Library::add_options())
	 * @param WP_Customize_Manager $wp_customize Active Customizer instance.
	 */
	public function customize_register( $wp_customize ) {
		// Return early if $wp_customize is not what we expect.
		if ( ! $wp_customize instanceof WP_Customize_Manager ) {
			return;
		}

		// Initialize options.
		$options = array();

		/**
		 * Process Customizer settings.
		 */
		$this->customizer_settings = apply_filters( 'follet_customizer_settings', $this->customizer_settings, $wp_customize );

		if ( ! empty( $this->customizer_settings ) ) {
			foreach ( $this->customizer_settings as $name => $atts ) {
				// If the setting already exists, we remove and register it again with our own attributes.
				if ( $setting = $wp_customize->get_setting( $name ) ) {
					$wp_customize->remove_setting( $name );

					// Merge the previous setting attributes with our own.
					$atts = array_merge( (array) $setting, $atts );
				} else {
					$atts['id'] = $name;
				}

				if ( ! empty( $atts['type'] ) && 'plain-text' == $atts['type'] ) {
					$wp_customize->add_setting( $name, array(
						'default'           => $atts['default'],
						'sanitize_callback' => $atts['sanitize_callback'],
					) );

					$wp_customize->add_control( new Follet_Plain_Text_Control( $wp_customize, $name, array(
								'section'  => $atts['section'],
								'settings' => $name,
								'priority' => $atts['priority'],
							)
						)
					);
				} else {
					// Add setting to $options array.
					$options[] = $atts;
				}
			}
		}

		/**
		 * Process Customizer controls.
		 */
		$this->customizer_controls = apply_filters( 'follet_customizer_controls', $this->customizer_controls, $wp_customize );

		if ( ! empty( $this->customizer_controls ) ) {
			foreach ( $this->customizer_controls as $name => $atts ) {
				// If the control already exists, we remove and register it again with our own attributes.
				if ( $control = $wp_customize->get_control( $name ) ) {
					// $wp_customize->remove_control( $name );

					// Merge the previous control attributes with our own.
					// $atts = array_merge( (array) $control, $atts );
					foreach ( $atts as $key => $value ) {
						$control->$key = $value;
					}
				} else {
					$atts['id'] = $name;

					/*
					 * Register control using the WP_Customize_Manager instance directly, since the Customizer_Library
					 * we use doesn't process controls on its own. Maybe this can be changed in the future.
					 */
					$wp_customize->add_control( $name, $atts );
				}
			}
		}

		/**
		 * Process Customizer sections.
		 */
		$this->customizer_sections = apply_filters( 'follet_customizer_sections', $this->customizer_sections, $wp_customize );

		if ( ! empty( $this->customizer_sections ) ) {
			// Initialize our list of sections.
			$sections = array();

			foreach ( $this->customizer_sections as $name => $atts ) {
				if ( $section = $wp_customize->get_section( $name ) ) {
					// If the section already exists, we remove and register it again with our own attributes.
					$wp_customize->remove_section( $name );

					// Merge the previous section attributes with our own.
					$atts = array_merge( (array) $section, $atts );
				} else {
					$atts['id'] = $name;
				}

				// Add sections to $sections array.
				$sections[] = $atts;
			}

			// Add sections to $options array.
			$options['sections'] = $sections;
		}

		// Register sections and options via Customizer.
		$this->customizer->add_options( $options );
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
