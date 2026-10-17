<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Assets {

	private string $manifest_path;

	public function __construct() {
		$this->manifest_path = CS_THEME_DIR . '/assets/build/.vite/manifest.json';

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets() {
		if ( ! file_exists( $this->manifest_path ) ) {
			return;
		}

		$manifest = json_decode( file_get_contents( $this->manifest_path ), true );
		$entry    = $manifest['assets/src/js/main.js'] ?? null;

		if ( ! $entry ) {
			return;
		}

		if ( ! empty( $entry['css'] ) ) {
			foreach ( $entry['css'] as $index => $css_file ) {
				wp_enqueue_style(
					'heru-main-' . $index,
					CS_THEME_URI . '/assets/build/' . $css_file,
					array(),
					null
				);
			}
		}

		wp_enqueue_script(
			'heru-main',
			CS_THEME_URI . '/assets/build/' . $entry['file'],
			array(),
			null,
			true
		);

		if (! is_admin()) {
			wp_enqueue_script(
				'heru-animations',
				CS_THEME_URI . '/assets/src/js/animations.js',
				array(),
				filemtime(CS_THEME_DIR . '/assets/src/js/animations.js'),
				true
			);
			wp_enqueue_style(
				'heru-animations',
				CS_THEME_URI . '/assets/src/css/animations.css',
				array(),
				null
			);
		}
		
	}
}