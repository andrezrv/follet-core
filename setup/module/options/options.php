<?php
/**
 * Follet Core.
 *
 * Manage theme options.
 *
 * @package   Follet_Core
 * @author    Andrés Villarreal <andrezrv@gmail.com>
 * @license   GPL-2.0+
 * @link      http://github.com/andrezrv/follet-core
 * @copyright 2014-2015 Andrés Villarreal
 * @since     2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'follet_add_to_author_profile' ) ) :
add_filter( 'user_contactmethods', 'follet_add_to_author_profile' );
/**
 * Add contact methods to author profile.
 *
 * This is disabled by default, since the best practice is adding the contact
 * methods through a plugin. You can enable this function with the following code:
 *
	add_action( 'follet_options_registered', 'follet_add_to_author_profile_enable' );
	function follet_add_to_author_profile() {
		follet_register_option( 'contact_methods_show', true );
	}
 *
 * @since  1.0
 *
 * @param  array $contact_methods List of current contact methods.
 * @return array                  Mixed list of contact methods.
 */
function follet_add_to_author_profile( $contact_methods ) {
	if ( follet_get_option( 'contact_methods_show' ) ) {
		$supported_contact_methods = follet_get_option( 'contact_methods' );

		foreach ( $supported_contact_methods as $key => $value ) {
			$contact_methods[ $key ] = $value;
		}
	}

	return $contact_methods;
}
endif;
