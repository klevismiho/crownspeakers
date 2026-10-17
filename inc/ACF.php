<?php

namespace CS\Theme;

if (! defined('ABSPATH')) {
	exit;
}

class ACF
{
	public function __construct()
	{
		add_filter('acf/settings/save_json', [$this, 'save_json']);
		add_filter('acf/settings/load_json', [$this, 'load_json']);
	}

	/**
	 * Save ACF field groups as JSON files
	 */
	public function save_json($path)
	{
		return get_template_directory() . '/acf-json';
	}

	/**
	 * Load ACF field groups from JSON files
	 */
	public function load_json($paths)
	{
		$paths[] = get_template_directory() . '/acf-json';

		return $paths;
	}
}