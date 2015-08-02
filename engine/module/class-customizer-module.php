<?php
/**
 * Follet Core.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     2.0
 */
namespace Follet\Module;

/**
 * Declare namespace dependencies.
 *
 * @since 2.0
 */
use Follet\Application\ModuleAbstract;
use Follet\Bootstrap\Loader;

/**
 * Class CustomizerModule
 *
 * Manage automatic registrations for Customizer sections, controls and settings.
 *
 * @package Follet_Core
 * @since   2.0
 */
class CustomizerModule extends ModuleAbstract {
	/**
	 * Customizer object for API interactions.
	 *
	 * @var   \Customizer_Library
	 *
	 * @since 2.0
	 */
	protected $customizer = null;

	/**
	 * List of sections to be initialized via Customizer.
	 *
	 * @var   array
	 *
	 * @since 2.0
	 */
	protected $sections = array();

	/**
	 * List of controls to be initialized via Customizer.
	 *
	 * @var   array
	 *
	 * @since 2.0
	 */
	protected $controls = array();

	/**
	 * List of settings to be initialized via Customizer.
	 *
	 * @var   array
	 *
	 * @since 2.0
	 */
	protected $settings = array();

	/**
	 * List of scripts to be initialized via Customizer.
	 *
	 * @var   array
	 *
	 * @since 2.0
	 */
	protected $scripts = array();

	/**
	 * Process theme customization settings on `customize_register` action.
	 *
	 * @since 2.0
	 */
	protected function __construct() {
		$this->register();
	}

	public function register() {
		/**
		 * Load Customizer Library dependency.
		 *
		 * @since 2.0
		 */
		add_action( 'customize_register', array( $this, 'load_customizer' ) );

		/**
		 * Initialize Customizer Library and set internal reference.
		 *
		 * @since 2.0
		 */
		add_action( 'customize_register', array( $this, 'set_customizer' ) );

		/**
		 * Register Customizer additions.
		 *
		 * @since 2.0
		 */
		add_action( 'customize_register', array( $this, 'customize_register' ) );

		/**
		 * Register Customizer scripts.
		 *
		 * @since 2.0
		 */
		add_action( 'customize_preview_init', array( $this, 'register_scripts' ) );
	}

	/**
	 * Load Customizer Library as dependency.
	 *
	 * @since 2.0
	 */
	public function load_customizer() {
		if ( ! class_exists( '\Customizer_Library' ) ) {
			new Loader( FOLLET_DIR . 'engine/lib/customizer-library/customizer-library.php' );
		}
	}

	/**
	 * Initialize Customizer Library and set internal reference.
	 *
	 * @since 2.0
	 */
	public function set_customizer() {
		if ( class_exists( '\Customizer_Library' ) ) {
			$this->customizer = \Customizer_Library::instance();
		}
	}

	/**
	 * Add a settings section to be initialized via Customizer.
	 *
	 * @since 2.0
	 *
	 * @param string $name Internal name for the section.
	 * @param array  $atts Section attributes.
	 */
	public function add_section( $name, $atts ) {
		$this->sections[ $name ] = $atts;
	}

	/**
	 * Add a settings control to be initialized via Customizer.
	 *
	 * @since 2.0
	 *
	 * @param string $name Internal name for the control.
	 * @param array  $atts Control attributes.
	 */
	public function add_control( $name, $atts ) {
		$this->controls[ $name ] = $atts;
	}

	/**
	 * Add a setting to be initialized via Customizer.
	 *
	 * @since 2.0
	 *
	 * @param string $name Internal name for setting.
	 * @param array  $atts Setting attributes.
	 */
	public function add_setting( $name, $atts ) {
		$this->settings[ $name ] = $atts;
	}

	/**
	 * Initialize sections, controls and settings via Customizer.
	 *
	 * @since 2.0
	 *
	 * @uses  self::customizer->add_options() (AKA Customizer_Library::add_options())
	 *
	 * @param \WP_Customize_Manager $wp_customize Active Customizer instance.
	 */
	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		// Initialize options.
		$options = array();

		/**
		 * Process Customizer settings.
		 */
		$this->settings = apply_filters( __CLASS__ . '\\customizer_settings', $this->settings, $wp_customize );

		if ( ! empty( $this->settings ) ) {
			foreach ( $this->settings as $name => $atts ) {
				// Check if this setting should be registered.
				if ( isset( $atts['eval'] ) ) {
					if ( ! $atts['eval'] ) {
						continue;
					}

					unset( $atts['eval'] );
				}

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
						)
					);

					$wp_customize->add_control( new \Follet_Plain_Text_Control( $wp_customize, $name, array(
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
		$this->controls = apply_filters( 'customizer_controls', $this->controls, $wp_customize );

		if ( ! empty( $this->controls ) ) {
			foreach ( $this->controls as $name => $atts ) {
				// Check if this control should be registered.
				if ( isset( $atts['eval'] ) ) {
					if ( ! $atts['eval'] ) {
						continue;
					}

					unset( $atts['eval'] );
				}

				// If the control already exists, we remove and register it again with our own attributes.
				if ( $control = $wp_customize->get_control( $name ) ) {
					foreach ( $atts as $key => $value ) {
						$control->$key = $value;
					}
				} else {
					$atts['id'] = $name;

					/**
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
		$this->sections = apply_filters( __CLASS__ . '\\customizer_sections', $this->sections, $wp_customize );

		if ( ! empty( $this->sections ) ) {
			// Initialize our list of sections.
			$sections = array();

			foreach ( $this->sections as $name => $atts ) {
				// Check if this section should be registered.
				if ( isset( $atts['eval'] ) ) {
					if ( ! $atts['eval'] ) {
						continue;
					}

					unset( $atts['eval'] );
				}

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
	 * Scripts are printed by default in the footer.
	 *
	 * @since 2.0
	 *
	 * @param string $name
	 * @param array  $args
	 */
	private function register_script( $name, $args = array() ) {
		if ( ! is_array( $args ) ) {
			$src = $args;
			unset( $args );
			$args['src'] = $src;
		}

		$defaults = apply_filters( __CLASS__ . '\\customizer_script_defaults', array(
				'handle'    => $name,
				'src'       => '',
				'deps'      => array( 'customize-preview' ),
				'version'   => follet_theme_version(),
				'in_footer' => true,
				'eval'      => true,
			), $args
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['eval'] ) {
			wp_enqueue_script( $args['handle'], $args['src'], $args['deps'], $args['version'], $args['in_footer'] );
		}
	}

	/**
	 * Register JavaScript files.
	 *
	 * @since 2.0
	 */
	public function register_scripts() {
		$this->scripts = apply_filters( __CLASS__ . '\\customizer_scripts', $this->scripts );

		if ( ! empty( $this->scripts ) ) {
			foreach ( $this->scripts as $name => $args ) {
				$this->register_script( $name, $args );
			}
		}
	}
}
