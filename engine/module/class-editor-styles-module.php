<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class EditorStylesModule extends ModuleAbstract {
	protected $editor_styles = array();

	protected function __construct() {
		$this->register();
	}

	public function register() {
		add_action( 'after_setup_theme', array( $this, 'register_editor_styles' ) );
	}

	private function register_editor_style( $src ) {
		add_editor_style( $src );
	}

	public function register_editor_styles() {
		if ( ! is_admin() || follet_doing_ajax() ) {
			return;
		}

		$this->editor_styles = apply_filters( 'follet_editor_styles', $this->editor_styles, $this );

		if ( ! empty( $this->editor_styles ) ) {
			foreach ( $this->editor_styles as $name => $src ) {
				$this->register_editor_style( $src );
			}
		}
	}
}
