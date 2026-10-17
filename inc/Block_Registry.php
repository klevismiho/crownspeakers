<?php

namespace CS\Theme;

if (! defined('ABSPATH')) {
	exit;
}

class Block_Registry {

	private string $blocks_dir;

	public function __construct( string $blocks_dir ) {
		$this->blocks_dir = $blocks_dir;
	}

	public function register(): void {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	public function register_blocks(): void {
		if ( ! is_dir( $this->blocks_dir ) ) {
			return;
		}

		foreach ( glob( $this->blocks_dir . '/*', GLOB_ONLYDIR ) as $block_path ) {
			if ( file_exists( $block_path . '/block.json' ) ) {
				register_block_type( $block_path );
			}
		}
	}
}
