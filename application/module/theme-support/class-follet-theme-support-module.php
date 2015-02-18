<?php
if ( ! class_exists( 'Follet_Theme_Support_Module' ) ) :
class Follet_Theme_Support_Module extends Follet_Singleton implements Follet_ModuleInterface {
	protected $theme_support = array();

	protected function __construct() {
		$this->register();
		$this->process_globals();
	}

	public function register() {
		add_action( 'after_setup_theme', array( $this, 'register_theme_support' ) );
	}

	private function process_globals() {
		$GLOBALS['follet_theme_support'] = $this;
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
endif;