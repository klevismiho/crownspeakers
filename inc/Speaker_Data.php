<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single place that knows where speaker data is stored.
 *
 * Speaker fields are plain post meta saved by the speaker plugin (repeaters are
 * stored as serialized arrays, not ACF rows), so everything is read with
 * get_post_meta(). Blocks call these methods instead of reading meta directly.
 */
class Speaker_Data {

	/**
	 * Raw meta value (unserialized by WordPress).
	 */
	private static function meta( int $speaker_id, string $key ) {
		return get_post_meta( $speaker_id, $key, true );
	}

	/**
	 * Meta value as an array of rows (for repeater-style fields).
	 */
	private static function rows( int $speaker_id, string $key ): array {
		$value = self::meta( $speaker_id, $key );

		return is_array( $value ) ? array_values( $value ) : array();
	}

	/**
	 * Term names for a taxonomy, or an empty array.
	 */
	private static function term_names( int $speaker_id, string $taxonomy ): array {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$terms = get_the_terms( $speaker_id, $taxonomy );

		return ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'name' ) : array();
	}

	/**
	 * Array of [ first, last ]; falls back to splitting the post title.
	 */
	public static function name_parts( int $speaker_id ): array {
		$first = trim( (string) self::meta( $speaker_id, 'speaker_fname' ) );
		$last  = trim( (string) self::meta( $speaker_id, 'speaker_lname' ) );

		if ( $first ) {
			return array( $first, $last );
		}

		$parts = explode( ' ', get_the_title( $speaker_id ), 2 );

		return array( $parts[0], $parts[1] ?? '' );
	}

	/**
	 * Short one-liner: excerpt, then brief bio, then the first highlight.
	 */
	public static function tagline( int $speaker_id ): string {
		if ( has_excerpt( $speaker_id ) ) {
			return get_the_excerpt( $speaker_id );
		}

		$brief_bio = trim( wp_strip_all_tags( (string) self::meta( $speaker_id, 'speaker_brief_bio' ) ) );

		if ( $brief_bio ) {
			return $brief_bio;
		}

		return self::highlights( $speaker_id )[0] ?? '';
	}

	public static function categories( int $speaker_id ): array {
		return self::term_names( $speaker_id, 'speaker_category' );
	}

	public static function travels_from( int $speaker_id ): string {
		return trim( (string) self::meta( $speaker_id, 'speaker_traveling_from' ) );
	}

	/**
	 * Fee range from the `speaker_fee_ranges` taxonomy (if it exists).
	 */
	public static function fee( int $speaker_id ): string {
		return implode( ', ', self::term_names( $speaker_id, 'speaker_fee_ranges' ) );
	}

	/**
	 * No field for this yet.
	 */
	public static function is_exclusive( int $speaker_id ): bool {
		return false;
	}

	/**
	 * Bullet highlights (`speaker_descriptions` repeater).
	 */
	public static function highlights( int $speaker_id ): array {
		$highlights = array();

		foreach ( self::rows( $speaker_id, 'speaker_descriptions' ) as $row ) {
			$text = trim( (string) ( $row['speaker_description'] ?? '' ) );

			if ( $text ) {
				$highlights[] = $text;
			}
		}

		return $highlights;
	}

	/**
	 * Testimonials: [ [ 'quote' => '', 'name' => '' ], … ].
	 * `name` is a single field (e.g. "CEO, J. Hilburn"); there is no separate title.
	 */
	public static function testimonials( int $speaker_id ): array {
		$testimonials = array();

		foreach ( self::rows( $speaker_id, 'speaker_testimonies' ) as $row ) {
			$quote = trim( (string) ( $row['speaker_testimony'] ?? '' ) );

			if ( $quote ) {
				$testimonials[] = array(
					'quote' => $quote,
					'name'  => trim( (string) ( $row['speaker_testimony_name'] ?? '' ) ),
				);
			}
		}

		return $testimonials;
	}

	/**
	 * Books: [ [ 'image' => url, 'url' => link, 'title' => '' ], … ].
	 * There is no title field yet, so `title` is always empty.
	 */
	public static function books( int $speaker_id ): array {
		$books = array();

		foreach ( self::rows( $speaker_id, 'speaker_books' ) as $row ) {
			$image = trim( (string) ( $row['speaker_book'] ?? '' ) );
			$link  = trim( (string) ( $row['speaker_book_link'] ?? '' ) );

			if ( $image || $link ) {
				$books[] = array(
					'image' => $image,
					'url'   => $link,
					'title' => '',
				);
			}
		}

		return $books;
	}

	/**
	 * Keynotes: [ [ 'title' => '', 'description' => '' ], … ].
	 */
	public static function keynotes( int $speaker_id ): array {
		$keynotes = array();

		foreach ( self::rows( $speaker_id, 'speaker_programs' ) as $row ) {
			$title = trim( (string) ( $row['speaker_program_title'] ?? '' ) );

			if ( $title ) {
				$keynotes[] = array(
					'title'       => $title,
					'description' => trim( (string) ( $row['speaker_program_description'] ?? '' ) ),
				);
			}
		}

		return $keynotes;
	}

	/**
	 * Videos: [ [ 'title' => '', 'url' => '', 'thumbnail' => url ], … ].
	 */
	public static function videos( int $speaker_id ): array {
		$videos = array();

		foreach ( self::rows( $speaker_id, 'speaker_videos' ) as $row ) {
			$url = trim( (string) ( $row['speaker_video'] ?? '' ) );

			if ( $url ) {
				$videos[] = array(
					'title'     => trim( (string) ( $row['speaker_video_title'] ?? '' ) ),
					'url'       => $url,
					'thumbnail' => self::video_thumbnail( $url ),
				);
			}
		}

		return $videos;
	}

	/**
	 * Thumbnail URL for a Vimeo/YouTube link via oEmbed, cached for a week.
	 */
	public static function video_thumbnail( string $url ): string {
		$cache_key = 'cs_video_thumb_' . md5( $url );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$data      = _wp_oembed_get_object()->get_data( $url, array( 'width' => 960 ) );
		$thumbnail = ( $data && ! empty( $data->thumbnail_url ) ) ? (string) $data->thumbnail_url : '';

		// Cache failures too (as an empty string) so a broken link isn't refetched on every view.
		set_transient( $cache_key, $thumbnail, $thumbnail ? WEEK_IN_SECONDS : DAY_IN_SECONDS );

		return $thumbnail;
	}

	/**
	 * Taxonomy behind the "speaker type" search filter (hero form). Not confirmed yet:
	 * if it doesn't exist, the filter is left out of the form and ignored by the grid.
	 */
	const TYPE_TAXONOMY = 'speaker_type';

	/**
	 * Search filters read from the URL: `?topic=` (speaker_category slug) and `?type=` (TYPE_TAXONOMY slug).
	 * Each one maps to its taxonomy.
	 */
	public static function filters(): array {
		return array(
			'topic' => 'speaker_category',
			'type'  => self::TYPE_TAXONOMY,
		);
	}

	/**
	 * Terms to offer in a search dropdown, A–Z, only those with speakers.
	 *
	 * @return \WP_Term[]
	 */
	public static function filter_terms( string $taxonomy ): array {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'orderby'    => 'name',
				'parent'     => 0,
			)
		);

		return is_wp_error( $terms ) ? array() : $terms;
	}

	/**
	 * Selected filter values from the URL, e.g. [ 'topic' => 'business' ]. Unknown taxonomies are skipped.
	 */
	public static function selected_filters(): array {
		$selected = array();

		foreach ( self::filters() as $param => $taxonomy ) {
			if ( empty( $_GET[ $param ] ) || ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$selected[ $param ] = sanitize_title( wp_unslash( $_GET[ $param ] ) );
		}

		return $selected;
	}

	/**
	 * tax_query clauses for the selected filters (combined with AND).
	 */
	public static function filters_tax_query(): array {
		$clauses = array();
		$filters = self::filters();

		foreach ( self::selected_filters() as $param => $slug ) {
			$clauses[] = array(
				'taxonomy' => $filters[ $param ],
				'field'    => 'slug',
				'terms'    => $slug,
			);
		}

		return $clauses;
	}

	/**
	 * Icon (inner SVG markup for a 24×24 stroke icon) for a topic, picked by matching its name.
	 * Used by explore-by-industry and topics-grid. First match wins; a compass is the fallback.
	 */
	public static function topic_icon( string $name ): string {
		$icons = array(
		'tech|\bai\b|artificial|digital|cyber|futur|innovation' => '<rect x="7" y="7" width="10" height="10" rx="1.5" /><rect x="10" y="10" width="4" height="4" /><path d="M10 4v3M14 4v3M10 17v3M14 17v3M4 10h3M4 14h3M17 10h3M17 14h3" />',
		'health|medic|wellness|wellbeing|well-being|mental|life science' => '<path d="M12 20s-7.5-4.5-7.5-10A4.5 4.5 0 0 1 12 7a4.5 4.5 0 0 1 7.5 3c0 5.5-7.5 10-7.5 10z" /><path d="M12 10.5v4M10 12.5h4" />',
		'financ|insur|bank|econom|invest|government|politic' => '<path d="M4 9.5 12 5l8 4.5M5 9.5h14M6.5 9.5v7M10 9.5v7M14 9.5v7M17.5 9.5v7M4 19h16" />',
		'educat|school|learn|universit|academ' => '<path d="m3 9.5 9-4.5 9 4.5-9 4.5-9-4.5z" /><path d="M7 11.5V16c0 1.2 2.2 2.5 5 2.5s5-1.3 5-2.5v-4.5M21 9.5V14" />',
		'retail|consumer|customer|hospitality|food' => '<path d="M6 8h12l-1 12H7L6 8z" /><path d="M9 8V7a3 3 0 0 1 6 0v1" />',
		'sales|marketing|growth|business|entrepreneur|strategy' => '<path d="M4 19h16M6 15l4-4 3 3 5-6" /><path d="M15 8h3v3" />',
		'leader|management|executive|team' => '<path d="M6 21V4M6 4h11l-2 3.5L17 11H6" />',
		'sport|athlet|adventure|olymp' => '<path d="M8 4h8v5a4 4 0 0 1-8 0V4zM8 6H5a3 3 0 0 0 3 3M16 6h3a3 3 0 0 1-3 3M12 13v4M8.5 20h7" />',
		'diversity|inclusion|dei\b|equity|culture|women' => '<circle cx="9" cy="8" r="3" /><circle cx="16.5" cy="9.5" r="2.5" /><path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 7-3.7" />',
		'motivat|inspir|resilien|change|celebrit|entertain' => '<path d="m12 3 2.6 5.6 6 .7-4.5 4.1 1.2 6L12 16.4 6.7 19.4l1.2-6L3.4 9.3l6-.7z" />',
		'environment|sustainab|climate|energy|science' => '<path d="M5 19c0-8 5-13 14-14 0 9-5 14-13 14z" /><path d="M5 19 13 11" />',
		);

		foreach ( $icons as $pattern => $icon ) {
			if ( preg_match( '/' . $pattern . '/i', $name ) ) {
				return $icon;
			}
		}

		// Compass.
		return '<circle cx="12" cy="12" r="8.5" /><path d="m15.5 8.5-2 5-5 2 2-5z" />';
	}

	/**
	 * meta_query clause for speakers with "Feature speaker" checked (`feature_speaker` = 1).
	 */
	public static function featured_meta_query(): array {
		return array(
			'key'   => 'feature_speaker',
			'value' => '1',
		);
	}

	/**
	 * meta_query clause that hides private and archived speakers.
	 */
	public static function visible_meta_query(): array {
		return array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array(
					'key'     => 'private_speaker',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'private_speaker',
					'value'   => '1',
					'compare' => '!=',
				),
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'archived_speaker',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'archived_speaker',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		);
	}
}
