<?php
if ( ! class_exists( 'Follet_Sidebars_Manager' ) ) :
class Follet_Sidebars_Manager extends Follet_Singleton implements Follet_ModuleInterface {
	/**
	 * List of sidebars for the current theme.
	 *
	 * @var   array
	 * @since 1.1
	 */
	protected $sidebars = array();

	/**
	 * Process registration of menus for the current theme.
	 *
	 * @since 1.1
	 */
	protected function __construct() {
		$this->register();
		$this->process_globals();
	}

	public function register() {
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	private function process_globals() {
		global $follet_sidebars;

		$follet_sidebars = $this;
	}

	/**
	 * Register a sidebar.
	 *
	 * @since 1.1
	 *
	 * @uses  register_sidebar()
	 *
	 * @param string $id   Internal name for the new sidebar.
	 * @param array  $args List of arguments to register the new sidebar.
	 */
	public function register_sidebar( $id, $args ) {
		// Allow name to be passed as $args in the form of a string.
		if ( ! is_array( $args ) ) {
			$name = $args;
			unset( $args );
			$args['name'] = $name;
		}

		$defaults = apply_filters( 'follet_register_sidebar_defaults', array(
			'id'            => $id,
			'name'          => __( 'Sidebar', follet_textdomain() ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><hr class="separator" />',
		), $args );

		$args = wp_parse_args( $args, $defaults );

		register_sidebar( $args );
	}

	/**
	 * Register widget areas.
	 *
	 * @since 1.1
	 */
	public function register_sidebars() {
		$this->sidebars = apply_filters( 'follet_sidebars', $this->sidebars );

		if ( ! empty( $this->sidebars ) ) {
			foreach ( $this->sidebars as $name => $args ) {
				$this->register_sidebar( $name, $args );
			}
		}
	}
}
endif;