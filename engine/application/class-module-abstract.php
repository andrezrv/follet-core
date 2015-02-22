<?php
namespace Follet\Application;

abstract class ModuleAbstract extends SingletonAbstract implements ModuleInterface {
	/**
	 * Setup module hooks.
	 *
	 * @since 1.1
	 */
	function register() {}

	/**
	 * Remove module hooks.
	 *
	 * @since 1.1
	 */
	function unregister() {}
}