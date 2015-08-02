<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class AjaxModule extends ModuleAbstract {
	/**
	 * Check if we're in an AJAX context.
	 *
	 * @var   bool
	 * @since 2.0
	 */
	protected $ajax = false;

	public function register() {
		add_action( 'follet_setup', array( $this, 'set_ajax' ) );
	}

	public function set_ajax() {
		$this->ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	}
}