<?php
namespace Follet\Module;

use Follet\Application\SingletonAbstract;

class ModuleManager extends SingletonAbstract {
	/**
	 * Instance for singleton.
	 *
	 * @var   ModuleManager
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
