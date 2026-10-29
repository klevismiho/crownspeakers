<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CS_THEME_DIR', get_template_directory() );
define( 'CS_THEME_URI', get_template_directory_uri() );

spl_autoload_register( function ( $class ) {
	$prefix   = 'CS\\Theme\\';
	$base_dir = CS_THEME_DIR . '/inc/';

	if ( strncmp( $prefix, $class, strlen( $prefix ) ) !== 0 ) {
		return;
	}

	$relative_class = substr( $class, strlen( $prefix ) );
	$file            = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require $file;
	}
} );

new Theme_Setup();
new SVG_Support();
new Assets();

$block_registry = new Block_Registry(
    CS_THEME_DIR . '/assets/build/blocks'
);
$block_registry->register();

new Block_Category();
new Rest_Api();
Speaker_Data::register_hooks();
new Post_Type_Testimonial();
new Post_Type_Team();

new Performance();

new ACF();

