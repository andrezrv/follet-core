<?php
namespace Follet\Module;

use Follet\Application\ModuleAbstract;

class ThemeModule extends ModuleAbstract {
	protected $theme = null;

	protected $version = '1.0';

	protected $textdomain = 'follet';

	public function register() {
		add_action( 'follet_setup', array( $this, 'set_theme' ) );
		add_action( 'follet_setup', array( $this, 'set_version' ) );
		add_action( 'follet_setup', array( $this, 'set_textdomain' ) );
	}

	public function set_theme() {
		$this->theme = wp_get_theme();
	}

	public function set_version() {
		if ( $this->theme instanceof \WP_Theme ) {
			$this->version = $this->theme->get( 'Version' );
		}
	}

	public function set_textdomain() {
		if ( $this->theme instanceof \WP_Theme ) {
			$this->textdomain = $this->theme->get( 'TextDomain' );
		}
	}
}