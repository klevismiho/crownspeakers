<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme_Setup {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
	}

	public function setup() {
		add_theme_support( 'custom-logo', array(
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );

		// Load the global stylesheet inside the editor's iframe too, so blocks get the
		// theme's CSS variables (--color-primary etc.) and look the same as on the front end.
		add_editor_style( 'assets/build/css/global.css' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support('menus');
	}
}