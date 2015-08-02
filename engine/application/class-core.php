<?php
/**
 * Follet Core.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.0
 */
namespace Follet\Application;

/**
 * Class Core (ex Follet)
 *
 * Main class for theme management.
 *
 * @package Follet_Core
 * @since   1.0
 */
class Core extends SingletonAbstract implements EventSubscriberInterface {
	/**
	 * Current Follet configuration.
	 *
	 * @var   Config
	 *
	 * @since 2.0
	 */
	protected $config = null;

	/**
	 * Current version of package.
	 *
	 * @var    string
	 *
	 * @since  1.0
	 */
	protected $version = '2.0';

	/**
	 * Helper class to manage events.
	 *
	 * @var   PluginAPIManager
	 *
	 * @since 2.0
	 */
	protected $api_manager = null;

	/**
	 * List of active modules.
	 *
	 * @var   array
	 *
	 * @since 2.0
	 */
	protected $modules = array();

	/**
	 * Follet Core absolute path.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $directory;

	/**
	 * Follet Core directory URI.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $directory_uri;

	/**
	 * Initialize Follet Core.
	 *
	 * @since  1.0
	 *
	 * @param  Config           $config      Configuration data.
	 * @param  PluginAPIManager $api_manager Helper object to deal with events..
	 */
	protected function __construct( Config $config = null, PluginAPIManager $api_manager = null ) {
		/**
		 * Set configuration data.
		 *
		 * @since 2.0
		 */
		$this->config = $config;

		/**
		 * Initialize Plugin API Manager.
		 *
		 * @since 2.0
		 */
		$this->api_manager = $api_manager;

		/**
		 * Run actions before Follet setup.
		 *
		 * @since 2.0
		 */
		$this->before_setup();

		/**
		 * Process all module-related functionality.
		 *
		 * @since 2.0
		 */
		$this->process_modules();

		/**
		 * Register internal class events.
		 *
		 * @since 2.0
		 */
		$this->register_events();

		/**
		 * Process all Follet setup functionality.
		 *
		 * @since 2.0
		 */
		$this->setup();

		/**
		 * Run actions after seyup is complete.
		 *
		 * @since 2.0
		 */
		$this->after_setup();
	}

	/**
	 * Process actions before setup.
	 *
	 * @since 2.0
	 */
	protected function before_setup() {
		/**
		 * Hook all the actions that need to run before setup here.
		 *
		 * @since 2.0
		 */
		do_action( $this->config->prefix . 'before_setup', $this );
	}

	/**
	 * Process actions on setup.
	 *
	 * @since 2.0
	 */
	protected function setup() {
		/**
		 * Hook all the actions that need to run during setup here.
		 *
		 * @since 2.0
		 */
		do_action( $this->config->prefix . 'setup', $this );
	}

	/**
	 * Process actions after setup.
	 *
	 * @since 2.0
	 */
	protected function after_setup() {
		/**
		 * Hook all the actions that need to run after setup here.
		 *
		 * @since 2.0
		 */
		do_action( $this->config->prefix . 'after_setup', $this );
	}

	/**
	 * Process module functionality.
	 *
	 * @since 2.0
	 */
	protected function process_modules() {
		/**
		 * Hook all module-related processes here.
		 *
		 * @since 2.0
		 */
		do_action( $this->config->prefix . 'process_modules', $this );
	}

	/**
	 * Register internal events.
	 *
	 * @since 2.0
	 */
	protected function register_events() {
		/**
		 * Let the API Manager object register our events.
		 *
		 * @since 2.0
		 */
		$this->api_manager->register( $this );
	}

	public function get_actions() {
		return array(
			/**
			 * Set path and URI for Follet Core directory.
			 *
			 * @since 2.0
			 */
			$this->config->prefix . 'setup' => 'set_directories'
		);
	}

	public function get_filters() {

	}

	/**
	 * Obtain a module by given key name.
	 *
	 * @since  2.0
	 *
	 * @param  string $name Name of the module.
	 *
	 * @return mixed|null|ModuleInterface
	 */
	public function get_module( $name ) {
		if ( ! empty( $this->modules[ $name ] ) ) {
			return $this->modules[ $name ];
		} else {
			wp_die( sprintf( __( 'Module "%s" was required, but it\'s not registered.' ), $name ) );
		}

		return null;
	}

	/**
	 * Add a module to the list of modules.
	 *
	 * @since 2.0
	 *
	 * @param $name
	 * @param ModuleInterface $module
	 */
	public function add_module( $name, ModuleInterface $module ) {
		$this->modules[ $name ] = $module;
	}

	/**
	 * Set Follet Core directories.
	 *
	 * @since 2.0
	 */
	public function set_directories() {
		/**
		 * Locate filters to obtain template paths.
		 *
		 * @since 2.0
		 */
		$template_directory     = apply_filters( $this->config->prefix . 'setup_template_directory', '', 'template_directory' );
		$template_directory_uri = apply_filters( $this->config->prefix . 'setup_template_directory_uri', '', 'template_directory_uri' );

		/**
		 * Set Follet Core path.
		 *
		 * @since 2.0
		 */
		if ( $template_directory ) {
			$this->directory = $template_directory . str_replace( $template_directory, '', $this->config->app_dir );
		}

		/**
		 * Set Follet Core URI.
		 *
		 * @since 2.0
		 */
		if ( $template_directory && $template_directory_uri ) {
			$this->directory_uri = $template_directory_uri . str_replace( $template_directory, '', $this->config->app_dir );
		}
	}
}
