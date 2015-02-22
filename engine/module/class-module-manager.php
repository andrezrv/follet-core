<?php
namespace Follet\Module;

use Follet\Application\ModuleInterface;
use Follet\Application\SingletonAbstract;

class ModuleManager extends SingletonAbstract {
	protected $modules = array();

	public function initialize() {
		add_action( 'follet_process_modules', array( $this, 'set_modules' ) );
		add_action( 'follet_process_modules', array( $this, 'register_modules' ) );
		add_action( 'follet_process_modules', array( $this, 'add_modules' ) );
	}

	public function set_modules() {
		$this->modules = apply_filters( 'follet_modules', array() );
	}

	public function register_modules() {
		if ( ! empty( $this->modules ) && is_array( $this->modules ) ) {
			foreach ( $this->modules as $module ) {
				$this->register_module( $module );
			}
		}
	}

	protected function register_module( ModuleInterface $module ) {
		$module->register();
	}

	public function add_modules( $object ) {
		if ( ! empty( $this->modules ) && is_array( $this->modules ) && method_exists( $object, 'add_module' ) ) {
			foreach ( $this->modules as $key => $module ) {
				$object->add_module( $key, $module );
			}
		}
	}
}
