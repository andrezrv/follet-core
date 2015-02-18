<?php
if ( ! class_exists( 'Follet_Template_Module' ) ) :
class Follet_Template_Module extends Follet_Singleton implements Follet_ModuleInterface {
    protected $directory = '';
    protected $directory_uri = '';
    private $locked = false;

    protected function __construct() {
        $this->register();
    }

    public function register() {
        if ( ! $this->locked ) {
            $this->register_directory();
            $this->register_directory_uri();
            $this->locked = true;
        }
    }

    private function register_directory() {
        if ( ! $this->directory ) {
            $this->directory = get_template_directory();
        }
    }

    private function register_directory_uri() {
        if ( ! $this->directory_uri ) {
            $this->directory_uri = get_template_directory_uri();
        }
    }
}
endif;
