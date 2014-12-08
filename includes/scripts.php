<?php
if ( ! function_exists( 'follet_do_enqueue_scripts' ) ) :
add_action( 'follet_enqueue_scripts', 'follet_do_enqueue_scripts' );
/**
 * Enqueue scripts and styles.
 *
 * @return void
 * @since  1.0
 */
function follet_do_enqueue_scripts() {
	// Bootstrap styles.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_style(
			'follet-bootstrap-styles',
			apply_filters(
				'follet_bootstrap_uri',
				follet( 'directory_uri' ) . '/includes/assets/bootstrap/css/bootstrap.min.css'
			)
		);
	}

	// Main style.
	if ( follet_get_option( 'load_main_style' ) ) {
		wp_enqueue_style(
			'follet-style',
			get_stylesheet_uri(),
			apply_filters( 'follet_main_style_dependencies', array() ),
			follet( 'theme_version' )
		);
	}

	// Bootstrap JS.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_script(
			'follet-bootstrap-js',
			follet( 'directory_uri' ) . '/includes/assets/bootstrap/js/bootstrap.min.js',
			array( 'jquery' ),
			'3.1.1',
			true
		);
	}

	// Respond JS.
	if ( get_theme_support( 'bootstrap' ) ) {
		wp_enqueue_script(
			'respond',
			follet( 'directory_uri' ) . '/includes/assets/respond/min/respond.min.js',
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
endif;

if ( ! function_exists( 'follet_add_html5_shim' ) ) :
add_filter( 'wp_head', 'follet_add_html5_shim' );
/**
 * HTML5 shim, for IE6-8 support of HTML5 elements.
 *
 * @since  1.0
 * @return void
 */
function follet_add_html5_shim() {
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
endif;
