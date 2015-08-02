<?php
namespace Follet\Application;

class Config extends SingletonAbstract {
	protected $app_name = '';
	protected $app_dir = '';
	protected $app_engine_dir = '';
	protected $app_lib_dir = '';
	protected $prefix = '';

	function __construct( array $values ) {
		if ( ! empty( $values ) ) {
			foreach ( $values as $key => $value ) {
				if ( is_string( $value ) && property_exists( $this, $key ) ) {
					$this->$key = $value;
				}
			}
		}
	}
}
