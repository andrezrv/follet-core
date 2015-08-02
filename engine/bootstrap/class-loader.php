<?php
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
namespace Follet\Bootstrap;

/**
 * Class Loader
 *
 * Autoload a given PHP library recursively.
 *
 * This class deals with class dependency by implementing the
 * `spl_autoload_register()` function before files are included.
 *
 * @package Follet_Core
 * @since   2.0
 */
class Loader {
    /**
     * Absolute path to the library being loaded.
     *
     * It can be either a directory or a single file.
     *
     * @since 2.0
     *
     * @var   string
     */
    private $path = '';

    /**
     * Base folder of main namespace.
     *
     * Used for autoloader functionality.
     *
     * @since 2.0
     *
     * @var   string
     */
    private $root_directory = '';

    /**
     * List of files and directories excluded from loading.
     *
     * @since 2.0
     *
     * @var   array
     */
    private $exclude = array();

    /**
     * List of files loaded by the current instance.
     *
     * @since 2.0
     *
     * @var   array
     */
    private $library = array();

    /**
     * Load library and store it as a list of files.
     *
     * @since  2.0
     *
     * @param string $path
     * @param string $root_directory
     * @param array  $exclude
     */
    public function __construct( $path, $root_directory = '', array $exclude = null ) {
        try {
            /**
             * Obtain object values and load PHP library from the given path.
             *
             * @since 2.0
             */
            $this->path           = $path;
            $this->root_directory = $root_directory;
            $this->exclude        = $exclude;
            $this->library        = $this->get_library();

        } catch ( \Exception $e ) {
            /**
             * Catch errors and display them.
             *
             * @since 2.0
             */
            wp_die( $e->getMessage() );
        }
    }

    /**
     * Load a PHP library given a file or a folder.
     *
     * @since  2.0
     *
     * @uses   self::autoload_register();
     *
     * @return array List of loaded files.
     *
     * @throws \Exception When $path is not valid.
     */
    protected function get_library() {
        $library    = array();
        $path       = $this->path;

        /**
         * If the given path is a directory, implement `autoload_register()`
         * to deal correctly with class dependencies, and then load all PHP
         * files recursively.
         *
         * @since 2.0
         */
        if ( is_dir( $path ) ) {
            /**
             * Implement custom autoload to avoid implementing classes with
             * undeclared dependencies.
             *
             * @since 2.0
             */
            $this->autoload_register();

            /**
             * Iterate path and load only PHP files recursively.
             *
             * @since 2.0
             */
            $directory_iterator = new \RecursiveDirectoryIterator( $path, \RecursiveDirectoryIterator::SKIP_DOTS );
            $recursive_iterator = new \RecursiveIteratorIterator( $directory_iterator );


            foreach ( $recursive_iterator as $filename => $file ) {
                if ( ! empty( $this->exclude ) && in_array( $dir = dirname( $filename ), $this->exclude ) ) {
                    $exclude = dirname( $filename );
                }

                if ( ! empty( $exclude ) && false !== stripos( $filename, $exclude ) ) {
                    continue;
                }

                if ( stristr( $filename , '.php' ) !== false ) {
                    require_once( $filename );
                    $library[] = $filename;
                }
            }

        } elseif ( ( is_file( $path ) ) && ( is_readable( $path ) ) ) {
            if ( ! empty( $this->exclude ) && in_array( dirname( $path ), $this->exclude ) ) {
                return null;
            }

            /**
             * Load a single PHP file.
             *
             * @since 2.0
             */
            if ( stristr( $path , '.php' ) !== false ) {
                require_once( $path );
                $library[] = $path;
            }
        } else {
            throw new \Exception( sprintf( __( 'The specified library could not be found at %s', 'follet' ), '<strong>' . $path . '</strong>' ) );
        }

        return $library;
    }

    /**
     * Register autoload functionality.
     *
     * @since 2.0
     *
     * @uses  spl_autoload_register()
     */
    protected function autoload_register() {
        /**
         * Allow deactivating autoload register.
         *
         * Keep in mind that setting this filter to false could break things.
         *
         * @since 2.0
         */
        if ( ! apply_filters( __CLASS__ . '\\use_autoload_register', true ) ) {
            return;
        }

        spl_autoload_register( apply_filters( __CLASS__ . '\\autoload_callback', array( $this, 'autoload' ) ) );
    }

    /**
     * Load a PHP file given a fully-qualified class name.
     *
     * @since 2.0
     *
     * @uses  Loader::__construct()
     *
     * @param string $class_name Fully-qualified name of class to be loaded.
     */
    protected function autoload( $class_name ) {
        $path = $this->path;

        // Segment class by namespace separators.
        $segments = explode( '\\', $class_name );

        // Obtain number of class segments.
        $count = count( $segments );

        // Modify name of first segment using our base path.
        if ( $count > 1 ) {
            $segments[0] = $this->root_directory;
        }

        // Re-format name of segments with our custom naming strategy.
        foreach ( $segments as $key => $value ) {
            $segments[ $key ] = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $value ) );
        }

        // Add prefix and extension for our files.
        $segments[ ( $count - 1 ) ] = 'class-' . $segments[ $count - 1 ] . '.php';

        // Obtain provisional name of file.
        $file_name = implode( '/', $segments );

        // If the class file doesn't exist, try an interface instead.
        if ( ! file_exists( $path . $file_name ) ) {
            $file_name = str_replace( 'class-', 'interface-', $file_name );
        }

        // Load the file if it exists.
        if ( file_exists( $path . $file_name ) ) {
            /**
             * Use this same method recursively to load just the required
             * PHP file.
             *
             * @since 2.0
             */
            new self( $path . $file_name );
        }
    }
}
