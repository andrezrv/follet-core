<?php
if ( ! function_exists( 'follet_thumbnail_class' ) ) :
add_filter( 'post_thumbnail_html', 'follet_thumbnail_class', 10, 3 );
/**
 * Add support for Vertical Featured Images.
 *
 * {@link http://johnregan3.wordpress.com/2014/01/02/adding-support-for-vertical-featured-images-in-wordpress-themes/}
 *
 * @since  1.0
 *
 * @param $html              string  Default unfiltered content.
 * @param $post_id           integer ID of the post.
 * @param $post_thumbnail_id integer ID of the thumbnail.
 *
 * @return                   string  Filtered HTML
 */
function follet_thumbnail_class( $html, $post_id, $post_thumbnail_id ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$image_data = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );

	//Get the image width and height from the data provided by wp_get_attachment_image_src()
	$width  = $image_data[1];
	$height = $image_data[2];

	$html = '<div class="entry-thumbnail entry-thumbnail-' . $post_id . '">' . $html . '</div>';

	if ( $height > $width ) {
		$html = str_replace( 'attachment-', 'vertical-image attachment-', $html );
		$html = str_replace( 'entry-thumbnail', 'portrait entry-thumbnail', $html );
	} elseif ( $height < $width ) {
		$html = str_replace( 'attachment-', 'horizontal-image attachment-', $html );
		$html = str_replace( 'entry-thumbnail', 'landscape entry-thumbnail', $html );
	}

	return $html;
}
endif;

if ( ! function_exists( 'follet_body_classes' ) ) :
add_filter( 'body_class', 'follet_body_classes' );
/**
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0
 *
 * @global WP_Post $post    Current post object.
 *
 * @param  array   $classes Classes for the body element.
 * @return array            Filtered classes.
 */
function follet_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_singular() ) {
		global $post;

		if ( $userdata = get_userdata( $post->post_author ) ) {
			array_unshift( $classes, 'author-' . $userdata->user_nicename );
		}
	}

	array_unshift(
		$classes,
		'wordpress',
		'y' . date( 'Y' ),
		'm' . date( 'm' ),
		'd' . date( 'd' ),
		'h' . date( 'h' ),
		strtolower( date( 'l' ) )
	);

	return $classes;
}
endif;

if ( ! function_exists( 'follet_wp_title' ) ) :
add_filter( 'wp_title', 'follet_wp_title', 10, 2 );
/**
 * Set HTML title.
 *
 * @since  1.0
 *
 * @global integer $page  Number of current page.
 * @global integer $paged Number of current page.
 *
 * @param $title
 * @param $sep
 *
 * @return string
 */
function follet_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;

	// Process title for custom taxonomies.
	if ( is_tax() ) {
		$title = get_queried_object()->name . ' ' . $sep . ' ';
	}

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'follet' ), max( $paged, $page ) );
	}

	return $title;
}
endif;

if ( ! function_exists( 'follet_setup_author' ) ) :
add_action( 'wp', 'follet_setup_author' );
/**
 * Sets the $authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since  1.0
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function follet_setup_author() {
	global $wp_query;

	if ( $wp_query instanceof WP_Query && $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
endif;

if ( ! function_exists( 'follet_the_password_form' ) ) :
add_filter( 'the_password_form', 'follet_the_password_form' );
/**
 * Format password protected post form to apply Bootstrap styles to it.
 *
 * @since  1.0
 *
 * @global WP_Post $post    Current post object.
 *
 * @param  string  $content HTML for password form.
 * @return string           Filtered HTML.
 */
function follet_the_password_form( $content ) {
	if ( get_theme_support( 'bootstrap' ) ) {
		global $post;

		$label   = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$content = '<form class="protected-post-form" action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass" method="post">
		' . __( 'This post is password protected. To view it please enter your password below:', wp_get_theme()->get( 'TextDomain' ) ) . '
		<div class="input-group"><input name="post_password" id="' . $label . '" type="password" class="form-control" placeholder="' . __( 'Password', wp_get_theme()->get( 'TextDomain' ) ) . '" /><span class="input-group-btn"><button type="submit" name="' . __( 'Submit', wp_get_theme()->get( 'TextDomain' ) ) . '" class="btn btn-primary">' . __( 'Submit', wp_get_theme()->get( 'TextDomain' ) ) . '</button></span></div>
		</form>
		';
	}

	return $content;
}
endif;
