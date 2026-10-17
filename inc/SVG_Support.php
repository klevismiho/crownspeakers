<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SVG_Support {

	public function __construct() {
		add_filter( 'upload_mimes', array( $this, 'allow_svg_upload' ) );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'fix_svg_filetype' ), 10, 4 );
	}

	public function allow_svg_upload( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}

	public function fix_svg_filetype( $data, $file, $filename, $mimes ) {
		$filetype = wp_check_filetype( $filename, $mimes );

		if ( 'svg' === $filetype['ext'] ) {
			$data['ext']             = 'svg';
			$data['type']            = 'image/svg+xml';
			$data['proper_filename'] = $filename;
		}

		return $data;
	}
}