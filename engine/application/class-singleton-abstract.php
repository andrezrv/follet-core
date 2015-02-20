<?php
namespace Follet\Application;
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

/**
 * Follet_Singleton
 *
 * Default singleton class.
 *
 * This is a pluggable class.
 *
 * @package Follet_Core
 * @since   1.0
 */
abstract class SingletonAbstract {

	protected function __construct() {
		// Do nothing.
	}

	/**
	 * Obtain self instance of this class.
	 *
	 * @since  1.0
	 *
	 * @return SingletonAbstract Self instance of this class.
	 */
	public static function get_instance() {
		static $instances = array();

		$called_class = get_called_class();

		if ( ! isset( $instances[ $called_class ] ) ) {
			$instances[ $called_class ] = new $called_class();
		}

		return $instances[ $called_class ];
	}

	/**
	 * Obtain the value of a non-public property.
	 *
	 * @since  1.1
	 *
	 * @param  string     $property
	 *
	 * @return mixed|null
	 */
	public function __get( $property ) {
		$value = null;

		if ( property_exists( $this, $property ) ) {
			$value = $this->$property;
		}

		return $value;
	}

	/**
	 * Prevent clone() for an instance of this class.
	 *
	 * @since  1.0
	 */
	public function __clone() {
		$class = get_class( $this );
		$error = __( 'Invalid operation: you cannot clone an instance of ', wp_get_theme()->get( 'TextDomain' ) ) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

	/**
	 * Prevent unserialize() for an instance of this class.
	 *
	 * @since  1.0
	 */
	public function __wakeup() {
		$class = get_class( $this );
		$error = __( 'Invalid operation: you cannot unserialize an instance of ', wp_get_theme()->get( 'TextDomain' ) ) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

	/**
	 * Add support for get_called_class in PHP < 5.3
	 *
	 * @since  1.0
	 *
	 * @return string Class name.
	 */
	protected static function get_called_class() {
		$bt = debug_backtrace();
		$l = 0;
		do {
			$l ++;
			$lines      = file( $bt[ $l ]['file'] );
			$callerLine = $lines[ $bt[ $l ]['line'] - 1 ];
			preg_match( '/([a-zA-Z0-9\_]+)::' . $bt[ $l ]['function'] . '/', $callerLine, $matches );
		} while ( $matches[1] === 'parent' && $matches[1] );

		return $matches[1];
	}
}