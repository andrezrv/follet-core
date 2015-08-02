<?php
namespace Follet\Application;

abstract class ModuleAbstract extends SingletonAbstract implements ModuleInterface, EventSubscriberInterface {
	protected $config = null;

	/**
	 * @var   PluginAPIManager
	 *
	 * @since 1.1
	 */
	protected $api_manager = null;

	protected function __construct( Config $config, PluginAPIManager $api_manager ) {
		$this->config = $config;
		$this->api_manager = $api_manager;
	}

	/**
	 * Register internal events.
	 *
	 * @since 1.1
	 */
	public function register() {
		/**
		 * Let the API Manager object register our events.
		 *
		 * @since 1.1
		 */
		$this->api_manager->register( $this );
	}

	/**
	 * Remove module hooks.
	 *
	 * @since 1.1
	 */
	function unregister() {}

	function get_actions() {
		return array();
	}

	function get_filters() {
		return array();
	}
}