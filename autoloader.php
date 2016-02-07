<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !function_exists( 'carawebs_class_autoloader' ) ) {

	function carawebs_class_autoloader( $classname ) {

	    $class = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower( $classname ) ) );

	    // create the actual filepath
	    $filePath = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $class . '.php';

	    // check if the file exists
	    if( file_exists( $filePath ) )  {

	        // require once on the file
	        require_once ( $filePath );

	    }

	}

	spl_autoload_register( 'carawebs_class_autoloader' );

}
