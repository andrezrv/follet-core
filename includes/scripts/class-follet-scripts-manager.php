<?php
if ( ! class_exists( 'Follet_Scripts_Manager' ) ) :
class Follet_Scripts_Manager extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var   Follet_Scripts_Manager
	 * @since 1.1
	 */
	protected static $instance;

	/**
	 * List of styles to be registered.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $scripts = array();

	/**
	 * Initialize instance.
	 *
	 * @since 1.1
	 */
	protected function __construct() {
		// Register theme styles on `wp_enqueue_scripts` action.
		if ( ! follet_doing_ajax() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		}

		// Process global variables.
		$this->process_globals();
	}

	private function process_globals() {
		global $follet_scripts;

		$follet_scripts = $this;
	}

	/**
	 * Scripts are printed by default in the footer.
	 *
	 * @param $name
	 * @param array $args
	 */
	private function register_script( $name, $args = array() ) {
		if ( ! is_array( $args ) ) {
			$src = $args;
			unset( $args );
			$args['src'] = $src;
		}

		$defaults = apply_filters( 'follet_script_defaults', array(
			'handle'    => $name,
			'src'       => '',
			'deps'      => array( 'jquery' ),
			'version'   => follet_theme_version(),
			'in_footer' => true,
			'eval'      => true,
		), $args );

		$args = wp_parse_args( $args, $defaults );

		if ( $args['eval'] ) {
			wp_enqueue_script( $args['handle'], $args['src'], $args['deps'], $args['version'], $args['in_footer'] );
		}
	}

	public function register_scripts() {
		$this->scripts = apply_filters( 'follet_scripts', $this->scripts, $this );

		if ( ! empty( $this->scripts ) ) {
			foreach ( $this->scripts as $name => $args ) {
				$this->register_script( $name, $args );
			}
		}
	}
}
endif;