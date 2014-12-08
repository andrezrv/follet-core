<?php
if ( ! function_exists( 'follet_navigate_posts_link_attributes' ) ) :
add_filter( 'next_posts_link_attributes', 'follet_navigate_posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'follet_navigate_posts_link_attributes' );
/**
 * Apply Bootstrap classes to posts navigation.
 *
 * @since  1.0
 *
 * @param  string $attr HTML properties for links.
 * @return string       Filtered HTML.
 */
function follet_navigate_posts_link_attributes( $attr ) {
	if ( get_theme_support( 'bootstrap' ) ) {
		$attr = $attr . ' class="btn btn-primary btn-lg"';
	}

	return $attr;
}
endif;

if ( ! function_exists( 'follet_nav_menu_primary_items' ) ) :
add_filter( 'wp_nav_menu_objects', 'follet_nav_menu_primary_items' );
/**
 * Apply Bootstrap classes to menu items.
 *
 * @since  1.0
 *
 * @param  array $items Menu items.
 * @return array        Filtered items.
 */
function follet_nav_menu_primary_items( $items ) {
	if ( get_theme_support( 'bootstrap' ) ) {
		$transformed_items = array();

		$previous_item = false;

		foreach ( $items as $id => $item ) {
			$transformed_items[ $id ] = $item;

			if ( in_array( 'menu-item-has-children', $transformed_items[ $id ]->classes ) ) {
				if ( is_object( $previous_item )
				     && ( in_array( 'dropdown', $previous_item->classes )
				          || in_array( 'dropdown-submenu', $previous_item->classes )
					)
				) {
					$transformed_items[ $id ]->classes[] = 'dropdown-submenu';
				} elseif ( ! $transformed_items[ $id ]->menu_item_parent ) {
					$transformed_items[ $id ]->classes[] = 'dropdown';
					$transformed_items[ $id ]->title .= ' <b class="icon icon-arrow-down"></b>';
				} else {
					$transformed_items[ $id ]->classes[] = 'dropdown-submenu';
				}
			}

			if ( in_array( 'current-menu-item', $transformed_items[ $id ]->classes ) ) {
				$transformed_items[ $id ]->classes[] = 'active';
			}

			$previous_item = $transformed_items[ $id ];
		}
	} else {
		$transformed_items = $items;
	}

	return $transformed_items;
}
endif;

if ( ! function_exists( 'follet_nav_menu_link_attributes' ) ) :
add_filter( 'nav_menu_link_attributes', 'follet_nav_menu_link_attributes', 10, 3 );
/**
 * Apply Bootstrap classes to menu links.
 *
 * @since  1.0
 *
 * @param  array  $atts Menu link attributes.
 * @param  object $item Menu item.
 *
 * @return array        Filtered menu link attributes.
 */
function follet_nav_menu_link_attributes( $atts, $item ) {
	if ( get_theme_support( 'bootstrap' )
	     && is_array( $item->classes )
	     && ! empty( $item->classes )
	     && in_array( 'dropdown', $item->classes )
	) {
		$atts['class']       = 'dropdown-toggle';
		$atts['data-toggle'] = 'dropdown';
	}

	return $atts;
}
endif;

if ( ! function_exists( 'follet_page_menu_args' ) ) :
add_filter( 'wp_page_menu_args', 'follet_page_menu_args' );
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @since  1.0
 *
 * @param  array $args Configuration arguments.
 * @return array
 */
function follet_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}
endif;
