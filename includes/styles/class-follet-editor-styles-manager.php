<?php
if ( ! class_exists( 'Follet_Editor_Styles_Manager' ) ) :
class Follet_Editor_Styles_Manager extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

	protected $editor_styles = array();

	/**
	 * Register theme options on `init` action.
	 *
	 * @since 1.1
	 */
	protected function __construct() {
		if ( is_admin() && ! follet_doing_ajax() ) {
			add_action( 'after_setup_theme', array( $this, 'register_editor_styles' ) );
		}

		$this->process_globals();
	}

	private function process_globals() {
		global $follet_editor_styles;

		$follet_editor_styles = $this;
	}

	private function register_editor_style( $src ) {
		add_editor_style( $src );
	}

	public function register_editor_styles() {
		$this->editor_styles = apply_filters( 'follet_editor_styles', $this->editor_styles, $this );

		if ( ! empty( $this->editor_styles ) ) {
			foreach ( $this->editor_styles as $name => $src ) {
				$this->register_editor_style( $src );
			}
		}
	}
}
endif;
