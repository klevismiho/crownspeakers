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
