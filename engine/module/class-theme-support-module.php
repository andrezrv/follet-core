<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class ThemeSupportModule extends ModuleAbstract {	protected $theme_support = array();

	protected function __construct() {
		$this->register();
	}

	public function register() {
		add_action( 'after_setup_theme', array( $this, 'register_theme_support' ) );
	}

	public function register_theme_support() {
		$this->theme_support = apply_filters( 'follet_theme_support', $this->theme_support, $this );

		if ( ! empty( $this->theme_support ) ) {
			foreach ( $this->theme_support as $name => $args ) {
				if ( is_array( $args ) ) {
					add_theme_support( $name, $args );
				} else {
					add_theme_support( $args );
				}
			}
		}
	}
}
