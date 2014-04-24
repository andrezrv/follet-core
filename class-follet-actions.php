<?php
if ( ! class_exists( 'Follet_Actions' ) ) :
/**
 * Follet_Actions
 *
 * Setup of the Follet theme happens here.
 *
 * @package Follet_Core
 * @since   1.0
 */
class Follet_Actions extends Follet {

	/**
	 * Instance of Follet.
	 * @var Follet
	 */
	protected $follet;

	/**
	 * Setup theme.
	 *
	 * @since  1.0
	 */
	protected function __construct() {

		// Process actions before the ones here.
		do_action( 'follet_actions' );

		$this->follet = Follet::get_instance();

		// Register actions to be executed.
		add_action( 'after_setup_theme',   array( $this, 'theme_support' ) );
		add_action( 'after_setup_theme',   array( $this, 'load_textdomain' ) );
		add_action( 'init',                array( $this, 'add_editor_styles' ) );
		add_action( 'wp_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );
		add_filter( 'user_contactmethods', array( $this, 'add_to_author_profile' ), 10, 1 );
		add_filter( 'wp_head',             array( $this, 'add_ie_compatibility_modes' ) );
		add_filter( 'wp_head',             array( $this, 'add_html5_shim' ) );

		// Process actions after the ones here.
		do_action( 'follet_after_actions' );

	}

	/**
	 * Make theme available for translation.
	 *
	 * @uses   load_theme_textdomain()
	 * @return void
	 * @since  1.0
	 */
	public function load_textdomain() {
		load_theme_textdomain( wp_get_theme()->get( 'TextDomain' ), apply_filters(
				'follet_textdomain_location',
				$this->follet->template_directory . '/languages'
			)
		);

	}

	/**
	 * Initialize default theme support.
	 *
	 * Default theme support includes:
	 * automatic-feed-links
	 * post-thumbnails
	 * post-formats (all post formats)
	 * custom-background
	 * html5
	 * bootstrap (custom, for Bootstrap)
	 * infinite-scroll (for Jetpack)
	 *
	 * @uses   add_theme_support()
	 * @return void
	 * @since  1.0
	 */
	public function theme_support() {

		// Process actions before theme support. Use it to register new theme support.
		do_action( 'follet_theme_support' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Enable support for Post Formats.
		add_theme_support(
			'post-formats', apply_filters( 'follet_post_formats', array(
					'aside',
					'gallery',
					'image',
					'video',
					'quote',
					'link',
					'status',
					'audio',
					'chat',
				)
			)
		);

		// Setup the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters(
				'follet_custom_background_args', array(
					'default-color' => 'FFFFFF',
					'default-image' => '',
				)
			)
		);

		// Enable support for HTML5 markup.
		add_theme_support( 'html5', apply_filters( 'follet_html5_args', array(
			'comment-list',
			'search-form',
			'comment-form',
		) ) );

		// Enable support for Bootstrap.
		add_theme_support( 'bootstrap' );

		/**
		 * Add theme support for Infinite Scroll.
		 *
		 * @see {@link http://jetpack.me/support/infinite-scroll/}
		 */
		add_theme_support( 'infinite-scroll', apply_filters(
			'follet_infinite_scroll', array(
					'container' => 'main',
					'footer'    => false,
				)
			)
		);

		// Process actions after theme support. Use to remove theme support.
		do_action( 'follet_after_theme_support' );

	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 * @since  1.0
	 */
	function enqueue_scripts() {

		// Bootstrap styles.
		if ( get_theme_support( 'bootstrap' ) ) {
			wp_enqueue_style(
				'follet-bootstrap-styles',
				apply_filters(
					'follet_bootstrap_uri',
					$this->follet->directory_uri . '/includes/bootstrap/css/bootstrap.min.css'
				)
			);
		}
		// Main style.
		if ( $this->follet->get_current( 'load_main_style' ) ) {
			wp_enqueue_style(
				'follet-style',
				get_stylesheet_uri(),
				apply_filters( 'follet_main_style_dependencies', array() ),
				$this->follet->theme_version
			);
		}
		// Bootstrap JS.
		if ( get_theme_support( 'bootstrap' )	) {
			wp_enqueue_script(
				'follet-bootstrap-js',
				$this->follet->directory_uri . '/includes/bootstrap/js/bootstrap.min.js',
				array( 'jquery' ),
				'3.1.1',
				true
			);
		}
		// Respond JS.
		if ( get_theme_support( 'bootstrap' )	) {
			wp_enqueue_script(
				'respond',
				$this->follet->directory_uri . '/includes/respond/min/respond.min.js',
				array( 'jquery' ),
				false,
				false
			);
		}
		// Comment reply JS.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

	}

	public function add_editor_styles() {

		// Bootstrap styles.
		if ( get_theme_support( 'bootstrap' ) ) {
			add_editor_style( $this->follet->directory_uri . '/includes/bootstrap/css/bootstrap.min.css' );
		}
		// Main style.
		if ( $this->follet->get_current( 'load_main_style' ) ) {
			add_editor_style( get_stylesheet_uri() );
		}

	}

	/**
	 * Add support for IE compatibility modes.
	 *
	 * {@link http://getbootstrap.com/getting-started/#support-ie-compatibility-modes}
	 *
	 * @return void
	 * @since  1.0
	 */
	public function add_ie_compatibility_modes() {
		if ( get_theme_support( 'bootstrap' ) ) {
			$content = '<meta http-equiv="X-UA-Compatible" content="IE=edge" />' . "\n";
			echo $content;
		}
	}

	/**
	 * HTML5 shim, for IE6-8 support of HTML5 elements.
	 *
	 * @return void
	 * @since  1.0
	 */
	public function add_html5_shim() {
		if ( get_theme_support( 'bootstrap' ) ) {
			ob_start();
			?>
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			echo $content;
		}
	}

	/**
	 * Add contact methods to author profile.
	 *
	 * This is disabled by default, since the best practice is adding the contact
	 * methods through a plugin. You can enable this function with the following code:
	 *
		function follet_add_to_author_profile() {
			follet_register_option( 'contact_methods_show', true );
		}
		add_action( 'follet_options_registered', 'follet_add_to_author_profile_enable' );

	 *
	 * @param  array $contactmethods List of current contact methods.
	 * @return array                 Mixed list of contact methods.
	 * @since  1.0
	 */
	public function add_to_author_profile( $contactmethods ) {
		if ( $this->follet->get_current( 'contact_methods_show' ) ) {
			$supported_contact_methods = $this->follet->get_current( 'contact_methods' );
			foreach ( $supported_contact_methods as $key => $value ) {
				$contactmethods[$key] = $value;
			}
		}
		return $contactmethods;
	}

}
endif;
