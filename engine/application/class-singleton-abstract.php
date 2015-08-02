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
 * @since     2.0
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
	/**
	 * Let other classes implement their own constructors.
	 *
	 * @since  1.0
	 */
	protected function __construct() {
		// Do nothing.
	}

	/**
	 * Obtain self instance of this class.
	 *
	 * @since  1.0
	 *
	 * @param  mixed             $param Unique and nullable parameter to initialize class.
	 *
	 * @return SingletonAbstract        Self instance of this class.
	 */
	public static function get_instance( $param = null ) {
		static $instances = array();

		$called_class = get_called_class();

		if ( ! isset( $instances[ $called_class ] ) ) {
			// Obtain number of arguments passed to the current method.
			$num_args = func_num_args();

			if ( $num_args > 1 ) {
				/**
				 * Play around with eval() if this method receives more than one
				 * parameter, so child objects can implement constructors using any
				 * number of parameters.
				 *
				 * @since 2.0
				 */
				// Initialize empty array to store our parameters.
				$args = array();

				// Initialize empty string to refer to our parameters.
				$params = '';

				// Obtain arguments as array element and string.
				for ( $i = 0; $i < $num_args; $i++ ) {
					$args[] = func_get_arg( $i ); // Get each argument passed.
					$params .= '$args[' . $i . '],';
				}

				// Remove additional commas.
				$params = ! empty( $args ) ? trim( $params, ',' ) : '';

				// Create object call to be evaluated.
				$new_class = 'return new ' . $called_class . '( ' . $params . ' );';

				// Instance class with any number of arguments.
				$instances[ $called_class ] = eval( $new_class );
			} else {
				/**
				 * If we have only one parameter (including null), just use that one
				 * to instantiate the class.
				 *
				 * @since 2.0
				 */
				$instances[ $called_class ] = new $called_class( $param );
			}
		}

		return $instances[ $called_class ];
	}

	/**
	 * Obtain the value of a non-public property.
	 *
	 * @since  2.0
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
}
