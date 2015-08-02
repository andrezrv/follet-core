<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class MenuModule extends ModuleAbstract {
	/**
	 * List of menus for the current theme.
	 *
	 * @var   array
	 * @since 2.0
	 */
	protected $menus = array();

	protected function __construct() {
		$this->register();
	}

	/**
	 * Process registration of menus for the current theme.
	 *
	 * @since 2.0
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_menus' ) );
	}

	/**
	 * Register navigation menus.
	 *
	 * @since 2.0
	 *
	 * @uses  register_nav_menu()
	 */
	public function register_menus() {
		$this->menus = apply_filters( 'follet_menus', $this->menus );

		if ( ! empty( $this->menus ) ) {
			foreach ( $this->menus as $name => $description ) {
				register_nav_menu( $name, $description );
			}
		}
	}
}
