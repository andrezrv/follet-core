<?php
if ( ! class_exists( 'Follet_Customizer_Manager' ) ) :
class Follet_Customizer_Manager extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

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
	 * @var   array
	 * @since 1.1
	 */
	protected $customizer_sections = array();

	/**
	 * List of controls to be initialized via Customizer.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $customizer_controls = array();

	/**
	 * List of settings to be initialized via Customizer.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $customizer_settings = array();

	protected $customizer_scripts = array();

	/**
	 * Process theme customization settings on `customize_register` action.
	 *
	 * @since 1.1
	 */
	protected function __construct() {
		$this->customizer = Customizer_Library::instance();

		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'register_customizer_scripts' ) );

		$this->process_globals();
	}

	private function process_globals() {
		global $follet_customizer;

		$follet_customizer = $this;
	}

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
				// Check if this control should be registered.
				if ( isset( $atts['eval'] ) ) {
					if ( ! $atts['eval'] ) {
						continue;
					}

					unset( $atts['eval'] );
				}

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
	 * @param $name
	 * @param array $args
	 */
	private function register_customizer_script( $name, $args = array() ) {
		if ( ! is_array( $args ) ) {
			$src = $args;
			unset( $args );
			$args['src'] = $src;
		}

		$defaults = apply_filters( 'follet_customizer_script_defaults', array(
			'handle'    => $name,
			'src'       => '',
			'deps'      => array( 'customize-preview' ),
			'version'   => follet_theme_version(),
			'in_footer' => true,
			'eval'      => true,
		), $args );

		$args = wp_parse_args( $args, $defaults );

		if ( $args['eval'] ) {
			wp_enqueue_script( $args['handle'], $args['src'], $args['deps'], $args['version'], $args['in_footer'] );
		}
	}

	public function register_customizer_scripts() {
		$this->customizer_scripts = apply_filters( 'follet_customizer_scripts', $this->customizer_scripts );

		if ( ! empty( $this->customizer_scripts ) ) {
			foreach ( $this->customizer_scripts as $name => $args ) {
				$this->register_customizer_script( $name, $args );
			}
		}
	}
}
endif;