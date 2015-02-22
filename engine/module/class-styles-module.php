<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class StylesModule extends ModuleAbstract {
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
	}

	public function register() {
		// Register theme styles on `wp_enqueue_scripts` action.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
	}

	private function register_style( $name, $args ) {
		if ( follet_doing_ajax() ) {
			return;
		}

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
