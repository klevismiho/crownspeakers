<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Type_Testimonial {

	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	public function register_post_type() {

		$labels = [
			'name'               => 'Testimonials',
			'singular_name'      => 'Testimonial',
			'menu_name'          => 'Testimonials',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Testimonial',
			'edit_item'          => 'Edit Testimonial',
			'new_item'           => 'New Testimonial',
			'view_item'          => 'View Testimonial',
			'search_items'       => 'Search Testimonials',
			'not_found'          => 'No testimonials found',
			'not_found_in_trash' => 'No testimonials found in Trash',
		];

		register_post_type( 'testimonial', [
			'labels'        => $labels,
			'public'        => true,
			'show_in_rest'  => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-format-quote',

			'supports'      => [
				'title',
				'editor',
				'thumbnail',
				'excerpt',
			],

			'rewrite' => [
				'slug' => 'testimonials',
			],
		] );
	}
}