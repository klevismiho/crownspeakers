<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Team members: name (title), photo (featured image), bio (content), order (page attributes).
 * Job title and LinkedIn are ACF fields (acf-json/group_cs_team_member.json).
 *
 * No front-end pages of their own: team members are shown through blocks.
 */
class Post_Type_Team {

	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'enter_title_here', [ $this, 'title_placeholder' ], 10, 2 );
	}

	public function register_post_type() {

		$labels = [
			'name'                  => 'Team',
			'singular_name'         => 'Team Member',
			'menu_name'             => 'Team',
			'all_items'             => 'All Team Members',
			'add_new'               => 'Add New',
			'add_new_item'          => 'Add New Team Member',
			'edit_item'             => 'Edit Team Member',
			'new_item'              => 'New Team Member',
			'view_item'             => 'View Team Member',
			'search_items'          => 'Search Team Members',
			'not_found'             => 'No team members found',
			'not_found_in_trash'    => 'No team members found in Trash',
			'featured_image'        => 'Photo',
			'set_featured_image'    => 'Set photo',
			'remove_featured_image' => 'Remove photo',
			'use_featured_image'    => 'Use as photo',
		];

		register_post_type( 'team', [
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'hierarchical'        => false,
			'menu_position'       => 21,
			'menu_icon'           => 'dashicons-groups',

			'supports'            => [
				'title',
				'editor',
				'thumbnail',
				'page-attributes',
			],
		] );
	}

	/**
	 * "Full name" instead of "Add title" in the editor.
	 */
	public function title_placeholder( string $placeholder, \WP_Post $post ): string {
		return 'team' === $post->post_type ? 'Full name' : $placeholder;
	}
}
