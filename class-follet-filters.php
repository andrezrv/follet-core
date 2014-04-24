<?php
if ( ! class_exists( 'Follet_Filters' ) ) :
/**
 * Follet_Filters
 *
 * Manages HTML filters, specially for Bootstrap features.
 *
 * This is a pluggable class.
 *
 * @package Follet_Core
 * @since   1.0
 */
class Follet_Filters extends Follet {

	/**
	 * Instance of Follet.
	 * @var Follet
	 */
	protected $follet;

	/**
	 * Execute actions and add necessary filters.
	 */
	protected function __construct() {

		// Process actions before the ones here.
		do_action( 'follet_filters' );

		add_filter( 'next_posts_link_attributes',     array( $this, 'navigate_posts_link_attributes' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'navigate_posts_link_attributes' ) );
		add_filter( 'wp_nav_menu_objects',            array( $this, 'nav_menu_primary_items' ), 10, 2 );
		add_filter( 'nav_menu_link_attributes',       array( $this, 'nav_menu_link_attributes' ), 10, 3 );
		add_filter( 'the_password_form',              array( $this, 'the_password_form' ) );
		add_filter( 'post_thumbnail_html',            array( $this, 'thumbnail_class' ), 10, 3 );
		add_filter( 'wp_page_menu_args',              array( $this, 'page_menu_args' ) );
		add_filter( 'body_class',                     array( $this, 'body_classes' ) );
		add_filter( 'wp_title',                       array( $this, 'wp_title' ), 10, 2 );
		add_action( 'wp',                             array( $this, 'setup_author' ) );

		// Process actions after the ones here.
		do_action( 'follet_after_filters' );

	}

	/**
	 * Apply Bootstrap classes to posts navigation.
	 *
	 * @param  string $attr HTML properties for links.
	 * @return string       Filtered HTML.
	 * @since  1.0
	 */
	function navigate_posts_link_attributes( $attr ) {
		if ( get_theme_support( 'bootstrap' ) ) {
			$attr = $attr . ' class="btn btn-primary btn-lg"';
		}
		return $attr;
	}

	/**
	 * Apply Bootstrap classes to menu items.
	 *
	 * @param  array $items Menu items.
	 * @param  array $args  (unused)
	 * @return array        Filtered items.
	 * @since  1.0
	 */
	function nav_menu_primary_items( $items, $args ) {
		if ( get_theme_support( 'bootstrap' ) ) {
			$transformed_items = array();
			$previous_item = false;
			foreach ( $items as $id => $item ) {
				$transformed_items[$id] = $item;
				if ( in_array( 'menu-item-has-children', $transformed_items[$id]->classes ) ) {
					if (    is_object( $previous_item )
						&& (    in_array( 'dropdown', $previous_item->classes )
						 	 || in_array( 'dropdown-submenu', $previous_item->classes )
						)
					) {
						$transformed_items[$id]->classes[] = 'dropdown-submenu';
					} elseif ( ! $transformed_items[$id]->menu_item_parent ) {
						$transformed_items[$id]->classes[] = 'dropdown';
						$transformed_items[$id]->title .= ' <b class="icon icon-arrow-down"></b>';
					} else {
						$transformed_items[$id]->classes[] = 'dropdown-submenu';
					}
				}
				if ( in_array( 'current-menu-item', $transformed_items[$id]->classes ) ) {
					$transformed_items[$id]->classes[] = 'active';
				}
				$previous_item = $transformed_items[$id];
			}
		} else {
			$transformed_items = $items;
		}
		return $transformed_items;
	}

	/**
	 * Apply Bootstrap classes to menu links.
	 *
	 * @param  array  $atts Menu link attributes.
	 * @param  object $item Menu item.
	 * @return array        Filtered menu link attributes.
	 * @since  1.0
	 */
	function nav_menu_link_attributes( $atts, $item ) {
		if (   get_theme_support( 'bootstrap' )
			&& is_array( $item->classes )
			&& !empty( $item->classes )
			&& in_array( 'dropdown', $item->classes)
		) {
			$atts['class'] = 'dropdown-toggle';
			$atts['data-toggle'] = 'dropdown';
		}
		return $atts;
	}

	/**
	 * Format password protected post form to apply Bootstrap styles to it.
	 *
	 * @param  string $content HTML for password form.
	 * @return string          Filtered HTML.
	 * @since  1.0
	 */
	function the_password_form( $content ) {
		if ( get_theme_support( 'bootstrap' ) ) {
			global $post;
			$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
			$content = '<form class="protected-post-form" action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass" method="post">
			' . __( 'This post is password protected. To view it please enter your password below:', wp_get_theme()->get( 'TextDomain' ) ) . '
			<div class="input-group"><input name="post_password" id="' . $label . '" type="password" class="form-control" placeholder="' . __( 'Password', wp_get_theme()->get( 'TextDomain' ) ) . '" /><span class="input-group-btn"><button type="submit" name="' . __( 'Submit', wp_get_theme()->get( 'TextDomain' ) ) . '" class="btn btn-primary">' . __( 'Submit', wp_get_theme()->get( 'TextDomain' ) ) . '</button></span></div>
			</form>
			';
		}
		return $content;
	}

	/**
	 * Add support for Vertical Featured Images.
	 *
	 * {@link http://johnregan3.wordpress.com/2014/01/02/adding-support-for-vertical-featured-images-in-wordpress-themes/}
	 *
	 * @param $html              string  Default unfiltered content.
	 * @param $post_id           integer ID of the post.
	 * @param $post_thumbnail_id integer ID of the thumbnail.
	 * @return                   string  Filtered HTML
	 * @since  1.0
	 */
	function thumbnail_class( $html, $post_id, $post_thumbnail_id ) {

		$post_id = $post_id ? $post_id : get_the_ID();

		$image_data = wp_get_attachment_image_src( $post_thumbnail_id , 'large' );

		//Get the image width and height from the data provided by wp_get_attachment_image_src()
		$width = $image_data[1];
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

	/**
	 * The following methods act independently of the theme templates. According
	 * to _s, some of the functionality here could be eventually replaced by
	 * core features.
	 */

	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @param  array $args Configuration arguments.
	 * @return array
	 * @since  1.0
	 */
	function page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param  array $classes Classes for the body element.
	 * @return array          Filtered classes.
	 * @since  1.0
	 */
	function body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		if ( is_singular() ) {
			global $post;
			array_unshift( $classes, 'author-' . get_userdata( $post->post_author )->user_nicename );
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


	/**
	 * Set HTML title.
	 *
	 * @param $title
	 * @param $sep
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	function wp_title( $title, $sep ) {
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


	/**
	 * Sets the authordata global when viewing an author archive.
	 *
	 * This provides backwards compatibility with
	 * http://core.trac.wordpress.org/changeset/25574
	 *
	 * It removes the need to call the_post() and rewind_posts() in an author
	 * template to print information about the author.
	 *
	 * @global WP_Query $wp_query WordPress Query object.
	 * @return void
	 * @since  1.0
	 */
	function setup_author() {
		global $wp_query;

		if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
			$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
		}
	}

}
endif;
