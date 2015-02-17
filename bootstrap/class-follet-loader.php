<?php
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

if ( ! class_exists( 'Follet_Loader' ) ) :
/**
 * Class Follet_Loader
 *
 * Auto-load a given PHP library recursively.
 *
 * @package Follet_Core
 * @since   1.1
 */
class Follet_Loader {
    /**
     * List of files loaded by the current instance.
     *
     * @since 1.1
     *
     * @var   array
     */
    private $library = array();

    /**
     * Load library and store it as a list of files.
     *
     * @since 1.1
     *
     * @param string $path
     */
    public function __construct( $path ) {
        if ( $library = self::load_library( $path ) ) {
            $this->library = $library;
        }
    }

    /**
     * Load a PHP library given a file or a folder.
     *
     * @since  1.1
     *
     * @param  string $path Name of folder or php file.
     *
     * @return array
     */
    public static function load_library( $path = '' ) {
        $library = array();

        if ( is_dir( $path ) ) {
            $directory_iterator = new RecursiveDirectoryIterator( $path );
            $recursive_iterator = new RecursiveIteratorIterator( $directory_iterator );

            foreach ( $recursive_iterator as $filename => $file ) {
                if( stristr( $filename , '.php' ) !== false ) {
                    require_once( $filename );
                    $library[] = $filename;
                }
            }
        } elseif ( ( is_file( $path ) ) && ( is_readable( $path ) ) ) {
            if ( stristr( $path , '.php' ) !== false ) {
                require_once( $path );
                $library[] = $filename;
            }
        }

        return $library;
    }
}
endif;
