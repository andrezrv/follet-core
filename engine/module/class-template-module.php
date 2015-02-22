<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class TemplateModule extends ModuleAbstract {
    protected $directory = '';
    protected $directory_uri = '';

    public function register() {
        add_action( 'follet_setup', array( $this, 'set_directory' ) );
        add_action( 'follet_setup', array( $this, 'set_directory_uri' ) );
        add_filter( 'follet_setup_template_directory', array( $this, 'get_directory' ) );
        add_filter( 'follet_setup_template_directory_uri', array( $this, 'get_directory_uri' ) );
    }

    public function set_directory() {
        if ( ! $this->directory ) {
            $this->directory = get_template_directory();
        }
    }

    public function get_directory() {
        return $this->directory;
    }

    public function set_directory_uri() {
        if ( ! $this->directory_uri ) {
            $this->directory_uri = get_template_directory_uri();
        }
    }

    public function get_directory_uri() {
        return $this->directory_uri;
    }
}
