<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read-only REST endpoints used by front-end blocks.
 *
 * GET /wp-json/crownspeakers/v1/speakers?ids=12,34
 * Returns card data (name, link, photo, tagline) for public speakers, in the order requested.
 * Used by the `favorites` block, whose saved favorites only hold IDs and a small thumbnail.
 */
class Rest_Api {

	const NAMESPACE = 'crownspeakers/v1';

	// Upper limit on IDs per request.
	const MAX_IDS = 50;

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/speakers',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_speakers' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'ids' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);
	}

	public function get_speakers( \WP_REST_Request $request ): \WP_REST_Response {
		$ids = array_slice(
			array_values( array_unique( array_filter( array_map( 'absint', explode( ',', $request['ids'] ) ) ) ) ),
			0,
			self::MAX_IDS
		);

		if ( ! $ids ) {
			return new \WP_REST_Response( array() );
		}

		// Published, not private or archived.
		$found = get_posts(
			array(
				'post_type'      => 'speaker',
				'post_status'    => 'publish',
				'post__in'       => $ids,
				'orderby'        => 'post__in',
				'posts_per_page' => count( $ids ),
				'meta_query'     => Speaker_Data::visible_meta_query(),
				'no_found_rows'  => true,
			)
		);

		$speakers = array_map(
			static function ( \WP_Post $speaker ): array {
				return array(
					'id'      => (string) $speaker->ID,
					'name'    => get_the_title( $speaker ),
					'url'     => get_permalink( $speaker ),
					'image'   => (string) get_the_post_thumbnail_url( $speaker, 'medium_large' ),
					'tagline' => Speaker_Data::tagline( $speaker->ID ),
				);
			},
			$found
		);

		return new \WP_REST_Response( $speakers );
	}
}
