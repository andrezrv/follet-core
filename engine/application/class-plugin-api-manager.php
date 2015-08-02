<?php
namespace Follet\Application;

/**
 * PluginAPIManager handles registering actions and hooks with the
 * WordPress Plugin API.
 */
class PluginAPIManager {
	/**
	 * Registers an object with the WordPress Plugin API.
	 *
	 * @param mixed $object
	 */
	public function register( $object ) {
		if ( $object instanceof ActionSubscriberInterface ) {
			$this->register_actions( $object );
		}
		if ( $object instanceof FilterSubscriberInterface ) {
			$this->register_filters( $object );
		}
	}

	/**
	 * Register an object with a specific action hook.
	 *
	 * @param ActionSubscriberInterface $object
	 * @param string $name
	 * @param mixed $parameters
	 */
	private function register_action( ActionSubscriberInterface $object, $name, $parameters ) {
		if ( is_string( $parameters ) ) {
			add_action( $name, array( $object, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			add_action( $name, array(
				$object,
				$parameters[0]
			), isset( $parameters[1] ) ? $parameters[1] : 10, isset( $parameters[2] ) ? $parameters[2] : 1 );
		}
	}

	/**
	 * Register an object with all its action hooks.
	 *
	 * @param ActionSubscriberInterface $object
	 */
	private function register_actions( ActionSubscriberInterface $object ) {
		$actions = $object->get_actions();

		if ( empty( $actions ) ) {
			return;
		}

		foreach ( $actions as $name => $parameters ) {
			$this->register_action( $object, $name, $parameters );
		}
	}

	/**
	 * Register an object with a specific filter hook.
	 *
	 * @param FilterSubscriberInterface $object
	 * @param string $name
	 * @param mixed $parameters
	 */
	private function register_filter( FilterSubscriberInterface $object, $name, $parameters ) {
		if ( is_string( $parameters ) ) {
			add_filter( $name, array( $object, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			add_filter( $name, array(
				$object,
				$parameters[0]
			), isset( $parameters[1] ) ? $parameters[1] : 10, isset( $parameters[2] ) ? $parameters[2] : 1 );
		}
	}

	/**
	 * Register an object with all its filter hooks.
	 *
	 * @param FilterSubscriberInterface $object
	 */
	private function register_filters( FilterSubscriberInterface $object ) {
		$filters = $object->get_filters();

		if ( empty( $filters ) ) {
			return;
		}

		foreach ( $filters as $name => $parameters ) {
			$this->register_filter( $object, $name, $parameters );
		}
	}
}