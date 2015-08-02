<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class ScriptsModule extends ModuleAbstract {
	/**
	 * List of styles to be registered.
	 *
	 * @var   array
	 * @since 2.0
	 */
	protected $scripts = array();

	/**
	 * Initialize instance.
	 *
	 * @since 2.0
	 */
	protected function __construct() {
		$this->register();
	}

	public function register() {
		// Register theme styles on `wp_enqueue_scripts` action.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
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
		if ( follet_doing_ajax() ) {
			return;
		}

		$this->scripts = apply_filters( 'follet_scripts', $this->scripts, $this );

		if ( ! empty( $this->scripts ) ) {
			foreach ( $this->scripts as $name => $args ) {
				$this->register_script( $name, $args );
			}
		}
	}
}
