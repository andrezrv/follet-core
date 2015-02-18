<?php
if ( ! class_exists( 'Follet_Module_Manager' ) ) :
class Follet_Module_Manager extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var   Follet_Module_Manager
	 * @since 1.0
	 */
	protected static $instance;

	protected $modules = array();

	protected function __construct() {
		add_action( 'follet_after_setup', array( $this, 'process_modules' ) );

		$this->process_globals();
	}

	private function process_globals() {
		global $follet_modules;

		$follet_modules = $this;
	}

	public function process_modules() {
		$this->modules = apply_filters( 'follet_modules', array() );
	}
}
endif;