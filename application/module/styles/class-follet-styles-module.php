<?php
if ( ! class_exists( 'Follet_Styles_Module' ) ) :
class Follet_Styles_Module extends Follet_Singleton implements Follet_ModuleInterface {
	/**
	 * Instance for singleton.
	 *
	 * @var   Follet_Styles_Module
	 * @since 1.1
	 */
	protected static $instance;

	/**
	 * List of styles to be registered.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $styles = array();

	/**
	 * Initialize instance.
	 *
	 * @since 1.1
	 */
	protected function __construct() {
		$this->register();
		// Process global variables.
		$this->process_globals();
	}

	public function register() {
		// Register theme styles on `wp_enqueue_scripts` action.
		if ( ! follet_doing_ajax() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
		}
	}

	private function process_globals() {
		global $follet_styles;

		$follet_styles = $this;
	}

	private function register_style( $name, $args ) {
		if ( ! is_array( $args ) ) {
			$src = $args;
			unset( $args );
			$args['src'] = $src;
		}

		$defaults = apply_filters( 'follet_style_defaults', array(
			'handle' => $name,
			'src'    => '',
			'deps'   => array(),
			'media'  => follet_theme_version(),
			'eval'   => true,
		), $args );

		$args = wp_parse_args( $args, $defaults );

		if ( $args['eval'] ) {
			wp_enqueue_style( $args['handle'], $args['src'], $args['deps'], $args['media'] );
		}
	}

	public function register_styles() {
		$this->styles = apply_filters( 'follet_styles', $this->styles, $this );

		if ( ! empty( $this->styles ) ) {
			foreach ( $this->styles as $name => $args ) {
				$this->register_style( $name, $args );
			}
		}
	}
}
endif;