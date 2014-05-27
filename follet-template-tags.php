<?php
/**
 * Standard template tags for Follet themes.
 *
 * All the functions in this file can be plugged or hooked in.
 *
 * @package Follet_Core
 * @since   1.0
 */

/**
 * Add a hook for custom actions before loading this file.
 */
do_action( 'follet_before_template_tags' );

if ( ! function_exists( 'follet_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @return void
 * @since  1.0
 */
function follet_posted_on() {

	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<section class="author vcard"><a class="url fn n" href="%1$s">%3$s%2$s</a></section>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() ),
		apply_filters( 'follet_posted_on_icon_user', '<span class="icon icon-user"></span>&nbsp;' )
	);
	$author = apply_filters( 'follet_post_author_section', $author );
	echo $author;

	$time = sprintf( '<section class="time created" title="%2$s">%3$s%1$s</section>',
		$time_string,
		esc_html( get_the_date( 'l, F j, Y g:i a' ) ),
		apply_filters( 'follet_posted_on_icon_date', '<span class="icon icon-date"></span>&nbsp;' )
	);
	$time = apply_filters( 'follet_post_time_section', $time );
	echo $time;

	ob_start();
	comments_number(
		__( '0 Comments', 'follet_theme' ),
		__( '1 Comment', 'follet_theme' ),
		__( '% Comments', 'follet_theme' )
	);
	$comments_number = ob_get_contents();
	$comments_number = apply_filters( 'follet_comments_number', $comments_number );
	ob_end_clean();

	$comments_link = sprintf( '<section class="comments-link"><a href="%1$s">%3$s%2$s</a></section>',
		esc_url( get_comments_link() ),
		esc_html( $comments_number ),
		apply_filters( 'follet_posted_on_icon_comments', '<span class="icon icon-comments"></span> ' )
	);
	$comments_link = html_entity_decode( $comments_link );
	$comments_link = apply_filters( 'follet_post_comments_section', $comments_link );
	echo $comments_link;

}
endif;

if ( ! function_exists( 'follet_categorized_blog' ) ) :
/**
 * Check if a blog has more than one category.
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @return boolean Returns true if this blog has more than one category.
 * @since  1.0
 */
function follet_categorized_blog() {
	if ( false === ( $follet_categories_list = get_transient( 'follet_categories_list' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$follet_categories_list = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$follet_categories_list = count( $follet_categories_list );

		set_transient( 'follet_categories_list', $follet_categories_list );
	}

	if ( '1' != $follet_categories_list ) {
		// This blog has more than 1 category so follet_categorized_blog should return true.
		return true;
	}
	// This blog has only 1 category so follet_categorized_blog should return false.
	return false;
}
endif;

if ( ! function_exists( 'follet_category_transient_flusher' ) ) :
add_action( 'edit_category', 'follet_category_transient_flusher' );
add_action( 'save_post',     'follet_category_transient_flusher' );
/**
 * Flush out the transients used in follet_categorized_blog().
 *
 * @return void
 * @since  1.0
 */
function follet_category_transient_flusher() {
	delete_transient( 'follet_categories_list' );
}
endif;

if ( ! function_exists( 'follet_link_pages' ) ) :
/**
 * Returns HTML for paged posts pagination.
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @return void
 * @since  1.0
 */
function follet_link_pages() {

	$bootstrap = _follet_bootstrap_active();
	$before = $bootstrap ? ' class="links-container btn-group"' : ' class="links-container"';

	add_filter( 'wp_link_pages_link', '_follet_modify_link_pages', 10, 2 );

	wp_link_pages( array(
		'before'           => sprintf(
			'<div class="page-links"><div%1$s><span%2$s>%3$s</span>',
			apply_filters( 'follet_link_pages_atts', $before ),
			$bootstrap ? ' class="pagination-text btn"' : 'class="pagination-text"',
			__( 'Pages', wp_get_theme()->get( 'TextDomain' ) )
		),
		'after'            => '</div></div>',
		'link_before'      => '<span>',
		'link_after'       => '</span>',
		'nextpagelink'     => __( 'Next page', wp_get_theme()->get( 'TextDomain' ) ) . ' &raquo;',
		'previouspagelink' => '&laquo; ' . __( 'Previous page', wp_get_theme()->get( 'TextDomain' ) ),
	) );

	// We only need to use the former filter here.
	remove_filter( 'wp_link_pages_link', '_follet_modify_link_pages' );

}
endif;

if ( ! function_exists( 'follet_edit_post_link' ) ) :
/**
 * Shortener for edit_post_link().
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @since  1.0
 */
function follet_edit_post_link() {

	if ( current_user_can( 'edit_posts' ) ) {
		$link = sprintf( '<section class="edit-link"><a href="%1$s">%3$s%2$s</a></section>',
			esc_url( get_edit_post_link() ),
			esc_html( __( 'Edit', 'follet_theme' ) ),
			apply_filters( 'follet_edit_post_icon', '<span class="icon icon-edit"></span>&nbsp;' )
		);
		$link = apply_filters( 'follet_edit_post_link', $link );
		echo $link;
	}

}
endif;

if ( ! function_exists( 'follet_continue_reading' ) ) :
/**
 * Return a continue reading kind of text.
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @param  boolean $display If false, the text will be printed.
 * @param  boolean $excerpt Whether the text is gonna be used next to an excerpt.
 *
 * @return string           Text for "continue reading".
 * @since  1.0
 */
function follet_continue_reading( $display = false, $excerpt = false ) {

	$bootstrap = _follet_bootstrap_active();

	// Create link in case this is gonna be used next to an excerpt.
	if ( $excerpt ) {
		add_filter( 'follet_continue_reading', '_follet_continue_reading_excerpt_link' );
	}

	$continue_reading = sprintf(
		__( '%1$sContinue reading %2$s', 'follet' ),
		sprintf( '<span class="meta-nav%s">', $bootstrap ? ' btn btn-primary btn-lg btn-block' : '' ),
		sprintf( '%s</span>', apply_filters(
			'follet_continue_reading_icon', '&nbsp;<span class="icon icon-next"></span>'
		) )
	);

	$continue_reading = apply_filters( 'follet_continue_reading', $continue_reading );

	if ( $display ) {
		echo $continue_reading;
	}

	return $continue_reading;

}
endif;

if ( ! function_exists( 'follet_comment_form' ) ) :
/**
 * Print a modified comment form.
 *
 * Take on this function by declaring it before this file is loaded.
 *
 * @return void
 * @since  1.0
 */
function follet_comment_form() {

	$req = get_option( 'require_name_email' );
	$bootstrap = _follet_bootstrap_active();

	$fields =  array(
		'author' => '<div class="comment-form-element name"><label for="author">' . __( 'Name:', wp_get_theme()->get( 'TextDomain' ) ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><div' . ( $bootstrap ? ' class="input-group"' : '' ) . '><span' . ( $bootstrap ? ' class="input-group-addon"' : '' ) . '>@</span><input id="author" name="author" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true"' . ' required /></div></div>',
		'email'  => '<div class="comment-form-element email"><label for="email">' . __( 'Email Address:', wp_get_theme()->get( 'TextDomain' ) ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><div' . ( $bootstrap ? ' class="input-group"' : '' ) . '><span' . ( $bootstrap ? ' class="input-group-addon glyphicon glyphicon-envelope"' : '' ) . '></span><input id="email" name="email" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-required="true"' . ' required /></div></div>',
		'url'    => '<div class="comment-form-element url"><label for="url">' . __( 'Your Website:', wp_get_theme()->get( 'TextDomain' ) ) . '</label><div' . ( $bootstrap ? ' class="input-group"' : '' ) . '><span' . ( $bootstrap ? ' class="input-group-addon glyphicon glyphicon-link"' : '' ) . '></span><input id="url" name="url" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" aria-required="true"' . ' /></div></div>',
	);

	$comments_args = array(
		'fields'        => $fields,
		'comment_field' => '<div class="comment-form-element comment"><label for="comment">' . 'Comment' . '</label><textarea id="comment" name="comment" class="' . ( $bootstrap ? 'col-sm-12 ' : '' ) . 'form-control" rows="5" aria-required="true"></textarea></div>',
	);

	$comments_args = apply_filters( 'follet_comment_form_args', $comments_args );

	ob_start();

	comment_form( $comments_args );

	$form = ob_get_contents();

	ob_clean();

	$form = $bootstrap ? str_replace( 'id="submit"', 'id="submit" class="btn btn-primary"', $form ) : $form;
	$form = apply_filters( 'follet_comment_form', $form );

	echo $form;

}
endif;

if ( ! function_exists( 'follet_list_comments' ) ) :
/**
 * Get a slightly modified list of comments.
 *
 * @uses   wp_list_comments()
 *
 * @param  array $args  Arguments for list of comments.
 * @return void
 * @since  1.0
 */
function follet_list_comments( $args ) {
	ob_start();
	wp_list_comments( $args );
	$comments = ob_get_contents();
	ob_end_clean();
	$comments = str_replace( '<footer', '<header', $comments );
	$comments = str_replace( '</footer', '</header', $comments );
	$comments = apply_filters( 'follet_list_comments', $comments );
	echo $comments;
}
endif;

if ( ! function_exists( 'follet_breadcrumb' ) ) :
/**
 * Get breadcrumbs for the current page.
 *
 * @param  array  $args Arguments for the breadcrumb.
 * @return mixed        If $args['display'] is set to false, the output will be returned instead of echoed.
 */
function follet_breadcrumb( $args = array() ) {

    global $post;

    // Restrict the list type to  be <ul> or <ol>.
    $list_type = ( isset( $args['list_type'] ) && in_array( $args['list_type'], array( 'ol', 'ul' ) ) )
               ? $args['list_type'] : 'ol';

    $args = shortcode_atts( array(
		'list_type'   => $list_type,
		'list_id'     => 'breadcrumbs',
		'list_atts'   => 'class="breadcrumb"',
		'show_home'   => true,
		'home_text'   => __( 'Home', wp_get_theme()->get( 'TextDomain' ) ),
		'home_atts'   => 'class="home"',
		'separator'   => '',
		'active_atts' => 'class="active"',
		'display'     => true,
    ), $args );

    extract( $args );

    // Start list.
    $output = '<' . $list_type . ' id="' . $list_id . '" ' . $list_atts . '>';

    if ( ! is_home() ) {

    	if ( $show_home ) {
	        $output .= '<li ' . $home_atts . '><a href="' . home_url() . '">' . $home_text . '</a></li>';
    	}

        if ( is_category() || is_singular( array( 'post', 'attachment' ) ) ) {

    		$category = get_the_category();
        	if ( ! is_attachment() ) {
	            $link = '<li><a href="' . get_category_link( $category[0]->cat_ID ) . '" title="' . $category[0]->cat_name . '">' . $category[0]->cat_name . '</a></li>';
	            $output .= apply_filters( 'follet_breadcrumb_category_link', $link, $category, $args, $post );
        	}

            if ( is_single() ) {
                $active = '<li ' . $active_atts . '>' . get_the_title() . '</li>';
	            $output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );
            }

        } elseif ( is_singular() ) {

            if ( is_page() && $post->post_parent ) {

                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse( $ancestors ); // Order properly.

                foreach ( $ancestors as $ancestor ) {
                    $link = '<li><a href="' . get_permalink( $ancestor ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
                    $output .= apply_filters( 'follet_breadcrumb_post_link', $link, $ancestor, $args, $post );
                    $output .= apply_filters( 'follet_breadcrumb_separator', $separator, $ancestor, $args, $post );
                }

            } elseif ( ! is_page() ) {
            	global $post;
				if ( $post_type_name = get_post_type_object( $post_type = get_post_type() )->labels->name ) {
					$link = '<li><a href="' . get_post_type_archive_link( $post_type ) . '" title="' . $post_type_name . '">' . $post_type_name . '</a></li>';
					$output .= apply_filters( 'follet_breadcrumb_post_type_link', $link, $args, $post );
				}
            	$args = array( 'public' => true, '_builtin' => false );
				$taxonomies = get_taxonomies( $args, 'names', 'and' );
				$terms = wp_get_object_terms( $post->ID, $taxonomies );
				if ( is_array( $terms) && ! empty( $terms) ) {
					$link = '<li><a href="' . get_term_link( $terms[0] ) . '" title="' . $terms[0]->name . '">' . $terms[0]->name . '</a></li>';
					$output .= apply_filters( 'follet_breadcrumb_category_link', $link, $terms, $args, $post );
				}
			}
            $active = '<li ' . $active_atts . '>' . get_the_title() . '</li>';
            $output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

        } elseif ( is_tag() ) {

	    	$active = '<li ' . $active_atts . '>' . single_tag_title( '', false ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

        } elseif ( is_tax() ) {

	    	$active = '<li ' . $active_atts . '>' . get_queried_object()->name . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( is_day() ) {

	    	$active = '<li ' . $active_atts . '>' . sprintf( __( 'Archive for %s',  wp_get_theme()->get( 'TextDomain' ) ), get_the_time( 'F jS, Y' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( is_month() ) {

	    	$active = '<li ' . $active_atts . '>' . sprintf( __( 'Archive for %s',  wp_get_theme()->get( 'TextDomain' ) ), get_the_time( 'F, Y' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    }
	    elseif ( is_year() ) {

	    	$active = '<li ' . $active_atts . '>' . sprintf( __( 'Archive for %s',  wp_get_theme()->get( 'TextDomain' ) ), get_the_time( 'Y' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( is_author() ) {

	    	$active = '<li ' . $active_atts . '>' . __( 'Author Archive',  wp_get_theme()->get( 'TextDomain' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {

	    	$active = '<li ' . $active_atts . '>' . __( 'Blog Archives',  wp_get_theme()->get( 'TextDomain' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( is_search() ) {

	    	$active = '<li ' . $active_atts . '>' . __( 'Search Results',  wp_get_theme()->get( 'TextDomain' ) ) . '</li>';
	    	$output .= apply_filters( 'follet_breadcrumb_post_active', $active, $args, $post );

	    } elseif ( is_archive() ) {
			if ( $title = get_queried_object()->labels->name ) {
				$active = '<li ' . $active_atts . '>' . $title . '</li>';
			} else {
				$active = '<li ' . $active_atts . '>' . _e( 'Archives', wp_get_theme()->get( 'TextDomain' ) ) . '</li>';
			}
			$output .= apply_filters( 'follet_breadcrumb_archive', $active, $args, $post );
		}

    }

    $output .= '</ol>';

    $output = apply_filters( 'follet_breadcrumb', $output, $post );

    if ( $display ) {
	    echo $output;
    } else {
    	return $output;
    }

}
endif;

if ( ! function_exists( 'follet_microdata' ) ) :
/**
 * Get microdata string for a given section.
 *
 * @param  string  $section Name of the section.
 * @param  boolean $display If set to false, the output will be returned instead of echoed.
 * @return mixed            Microdata string, in case $display is set to false.
 * @since  1.0
 */
function follet_microdata( $section, $display = true ) {
	if ( ! $section ) {
		return;
	}
	switch ( $section ) {
		case 'body':
			$microdata = 'itemtype="http://schema.org/WebPage" itemscope="itemscope"';
			break;
		case 'navigation':
			$microdata = 'itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope"';
			break;
		case 'header':
			$microdata = 'itemtype="http://schema.org/WPHeader" itemscope="itemscope"';
			break;
		case 'footer':
			$microdata = 'itemtype="http://schema.org/WPFooter" itemscope="itemscope"';
			break;
		case 'content':
			$microdata = 'itemtype="http://schema.org/Blog" itemscope="" itemprop="mainContentOfPage"';
			break;
		case 'post':
			$microdata = 'itemprop="blogPost" itemtype="http://schema.org/BlogPosting" itemscope="itemscope"';
			break;
		case 'page':
			$microdata = 'itemtype="http://schema.org/CreativeWork" itemscope="itemscope"';
			break;
		case 'sidebar':
			$microdata = 'itemtype="http://schema.org/WPSideBar" itemscope="itemscope"';
			break;
		case 'comment':
			$microdata = 'itemtype="http://schema.org/UserComments" itemscope="itemscope" itemprop="comment"';
			break;
		case 'entry-title':
			$microdata = 'itemprop="headline"';
			break;
		case 'entry-summary':
			$microdata = 'itemprop="description"';
			break;
		case 'entry-content':
			$microdata = 'itemprop="articleBody"';
			break;
		case 'entry-author':
			$microdata = 'itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="author"';
			break;
		case 'comments-link':
			$microdata = 'itemprop="discussionURL"';
			break;
		case 'entry-url':
			$microdata = 'itemprop="url"';
			break;
		case 'cat-links':
			$microdata = 'itemprop="articleSection"';
			break;
		case 'tag-links':
			$microdata = 'itemprop="keywords"';
			break;
		case 'permalink':
			$microdata = 'itemprop="articleURL"';
			break;
		case 'comment-author':
			$microdata = 'itemtype="http://schema.org/Person" itemscope="itemscope" itemprop="creator"';
			break;
		case 'comment-time':
			$microdata = 'itemprop="commentTime"';
			break;
		case 'comment-content':
			$microdata = 'itemprop="commentText"';
			break;
		case 'comment-reply':
			$microdata = 'itemprop="replyToUrl"';
			break;
		case 'url':
			$microdata = 'itemprop="url"';
			break;
		case 'image':
			$microdata = 'itemprop="image"';
			break;
		default:
			$microdata = '';
			break;
	}
	$microdata = apply_filters( 'follet_microdata', $microdata, $section );

	if ( $display ) {
		echo $microdata;
	} else {
		return $microdata;
	}

}
endif;

if ( ! function_exists( 'follet_container_class' ) ) :
/**
 * Apply default class for containers.
 *
 * @param  string  $location Place where the class is being applied.
 * @param  boolean $display  If set to false, the output will be returned instead of echoed.
 * @return mixed             Class string, in case $display is set to false.
 */
function follet_container_class( $location, $display = true ) {
	$class = 'container';
	$class = apply_filters( 'follet_container_class', $class, $location );
	if ( $display ) {
		echo $class;
	} else {
		return $class;
	}
}
endif;

if ( ! function_exists( 'follet_post_classes' ) ) :
/**
 * Add custom HTML classes for post.
 *
 * @return string HTML classes.
 */
function follet_post_classes() {
	global $post;
	$array[] = 'author-' . get_userdata( $post->post_author )->user_nicename;
	$classes = implode( ' ', $array );
	$classes = apply_filters( 'follet_post_classes', $classes, $array, $post );
	return $classes;
}
endif;

/**
 * Add a hook for custom actions before loading the next file.
 */
do_action( 'follet_after_template_tags' );
