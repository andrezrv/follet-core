<?php
if ( ! class_exists( 'Follet_Singleton' ) ) :
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
abstract class Follet_Singleton {

	protected function __construct() {
		// Do nothing.
	}

	/**
	 * Obtain self instance of this class.
	 *
	 * @return Follet_Singleton Self instance of this class.
	 * @since  1.0
	 */
	final public static function get_instance() {

		static $instances = array();

		$called_class = get_called_class();

		if ( ! isset( $instances[$called_class] ) ) {
			$instances[$called_class] = new $called_class();
		}

		return $instances[$called_class];
	}

	/**
	 * Prevent clone() for an instance of this class.
	 *
	 * @return void
	 * @since  1.0
	 */
	public function __clone() {
		$class = get_class( $this );
		$error = __(
			'Invalid operation: you cannot clone an instance of ',
			wp_get_theme()->get( 'TextDomain' )
		) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

	/**
	 * Prevent unserialize() for an instance of this class.
	 *
	 * @return void
	 * @since  1.0
	 */
	public function __wakeup() {
		$class = get_class( $this );
		$error = __(
			'Invalid operation: you cannot unserialize an instance of ',
			wp_get_theme()->get( 'TextDomain' )
		) . $class;
		trigger_error( $error, E_USER_ERROR );
	}

}
endif;

if ( ! function_exists( 'get_called_class' ) ) :
/**
 * Add support for get_called_class in PHP < 5.3
 *
 * @return string Class name.
 * @since  1.o
 */
function get_called_class() {
	$bt = debug_backtrace();
	$l = 0;
	do {
		$l++;
		$lines = file($bt[$l]['file']);
		$callerLine = $lines[$bt[$l]['line']-1];
		preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l]['function'].'/', $callerLine, $matches);
	} while ($matches[1] === 'parent' && $matches[1]);

	return $matches[1];
}
endif;
