<?php
/**
 * Follet Core.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Follet' ) ) :
/**
 * Class Follet
 *
 * Main class for theme management.
 *
 * @package Follet_Core
 * @since   1.0
 */
class Follet extends Follet_Singleton {
	/**
	 * Instance for singleton.
	 *
	 * @var    Follet
	 * @since  1.0
	 */
	protected static $instance;

	protected $modules = array();

	/**
	 * Utilitary property to store theme options.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $_options = array();

	/**
	 * Store $template_directory to prevent overhead.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory;

	/**
	 * Store $template_directory_uri to prevent overhead.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $template_directory_uri;

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
	 * Theme text domain.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $textdomain;

	/**
	 * Check if we're in an AJAX context.
	 *
	 * @var   bool
	 * @since 1.1
	 */
	public $doing_ajax;

	/**
	 * Current version of package.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $version = '1.1';

	/**
	 * Current version of the supported theme.
	 * @var    string
	 * @since  1.0
	 */
	public $theme_version = '1.0';

	/**
	 * Initialize Follet.
	 *
	 * @since  1.0
	 */
	protected function __construct() {
		// Process actions before setup.
		do_action( 'follet_setup' );

		// Obtain theme values.
		$theme = wp_get_theme();

		// Process object values.
		$this->theme_version          = $theme->get( 'Version' );
		$this->doing_ajax             = defined( 'DOING_AJAX' ) && DOING_AJAX;
		$this->textdomain             = $theme->get( 'TextDomain' );
		$this->template_directory     = get_template_directory();
		$this->template_directory_uri = get_template_directory_uri();
		$this->directory              = $this->get_directory();
		$this->directory_uri          = $this->get_directory_uri();

		// Process global variables.
		$this->process_globals();

		// Initialize modules.
		$this->modules = Follet_Modules_Manager::get_instance();

		// Process actions after setup.
		do_action( 'follet_after_setup' );
	}

	/**
	 * Obtain self instance of this class.
	 *
	 * @since  1.1
	 * @return Follet Self instance of this class.
	 */
	final public static function get_instance() {
		// Load all dependencies that need to be active before the class is instantiated.
		// self::load_dependencies();

		return parent::get_instance();
	}

	/* ========================================================================
	   Basic Theme Processes.
	   ===================================================================== */

	/**
	 * Process global variables.
	 *
	 * @since 1.1
	 */
	private function process_globals() {
		global $follet;

		$follet = $this;
	}

	/* ========================================================================
	   Methods for Paths Management.
	   ===================================================================== */

	/**
	 * Get Follet Core path.
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	public function get_directory() {
		return $this->template_directory . str_replace( $this->template_directory, '', dirname( __FILE__ ) );
	}

	/**
	 * Get Follet Core directory URI.
	 *
	 * @return string
	 * @since  1.0
	 */
	public function get_directory_uri() {
		return $this->template_directory_uri . str_replace( $this->template_directory, '', dirname( __FILE__ ) );
	}
}
endif;
