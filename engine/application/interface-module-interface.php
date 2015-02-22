<?php
namespace Follet\Application;

interface ModuleInterface {
	/**
	 * Setup module hooks.
	 *
	 * @since 1.1
	 */
	function register();

	/**
	 * Remove module hooks.
	 *
	 * @since 1.1
	 */
	function unregister();
}
