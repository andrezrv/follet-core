<?php
if ( ! class_exists( 'Follet_Menus_Manager' ) ) :
class Follet_Menus_Manager extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * List of menus for the current theme.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $menus = array();

	protected  function __construct() {
		/**
		 * Process registration of menus for the current theme.
		 *
		 * @since 1.1
		 */
		add_action( 'init', array( $this, 'register_menus' ) );

		$this->process_globals();
	}

	private function process_globals() {
		global $follet_menus;

		$follet_menus = $this;
	}

	/**
	 * Register navigation menus.
	 *
	 * @since 1.1
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
endif;