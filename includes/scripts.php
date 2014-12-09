<?php
if ( ! function_exists( 'follet_bootstrap_scripts' ) ) :
add_action( 'follet_enqueue_scripts', 'follet_bootstrap_scripts' );
/**
 * Enqueue scripts and styles.
 *
 * @return void
 * @since  1.0
 */
function follet_bootstrap_scripts() {
	// Bootstrap JS.
	if ( _follet_bootstrap_active() ) {
		wp_enqueue_script(
			'follet-bootstrap-js',
			follet( 'directory_uri' ) . '/includes/assets/bootstrap/js/bootstrap.min.js',
			array( 'jquery' ),
			'3.1.1',
			true
		);
	}
}
endif;

if ( ! function_exists( 'follet_response_scripts' ) ) :
add_action( 'follet_enqueue_scripts', 'follet_response_scripts' );
/**
 * Enqueue Respond scripts.
 *
 * @since 1.1
 */
function follet_response_scripts() {
	if ( _follet_bootstrap_active() ) {
		wp_enqueue_script(
			'respond',
			follet( 'directory_uri' ) . '/includes/assets/respond/min/respond.min.js',
			array( 'jquery' ),
			false,
			false
		);
	}
}
endif;

if ( ! function_exists( 'follet_comments_scripts' ) ) :
add_action( 'follet_enqueue_scripts', 'follet_comments_scripts' );
/**
 * Enqueue script for comment replies.
 *
 * @since 1.1
 */
function follet_comments_scripts() {
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
	if ( _follet_bootstrap_active() ) {
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
