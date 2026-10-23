<?php

namespace CS\Theme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The WPForms "Event Inquiry" form (import file: setup/wpforms-event-inquiry.json).
 * Used by the `global-form` section and the `favorites` popup.
 */
class Inquiry_Form {

	// WPForms → All Forms → ID. Update after importing the form on another environment.
	const FORM_ID = 27446;

	/**
	 * The form ID, or 0 if WPForms is inactive or the form doesn't exist.
	 */
	public static function id(): int {
		if ( ! function_exists( 'wpforms_display' ) || 'wpforms' !== get_post_type( self::FORM_ID ) ) {
			return 0;
		}

		return self::FORM_ID;
	}

	/**
	 * Output the form without its title and description.
	 */
	public static function display(): void {
		if ( self::id() ) {
			wpforms_display( self::FORM_ID, false, false );
		}
	}
}
