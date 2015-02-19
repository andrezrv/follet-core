<?php
namespace Follet\Module;
use Follet\Application\ModuleAbstract;

class TemplateModule extends ModuleAbstract {
    protected $directory = '';
    protected $directory_uri = '';

    protected function __construct() {
        $this->register();
    }

    public function register() {
        $this->register_directory();
        $this->register_directory_uri();
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
