<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class AdminModule extends ModuleAbstract {
	/**
	 * Check if we're in an admin context.
	 *
	 * @var   bool
	 *
	 * @since 1.1
	 */
	protected $admin = false;

	public function register() {
		add_action( 'follet_setup', array( $this, 'set_admin' ) );
	}

	public function set_admin() {
		$this->admin = is_admin();
	}
}