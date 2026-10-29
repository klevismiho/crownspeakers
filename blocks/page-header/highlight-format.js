/**
 * "Highlight" text format: wraps the selected words in <span class="is-highlight">,
 * which the theme shows in gold (e.g. the second line of a Page Header title).
 *
 * Registered once, here. Any block can offer it by adding 'crownspeakers/highlight'
 * to a RichText's allowedFormats.
 */
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextToolbarButton } from '@wordpress/block-editor';

export const HIGHLIGHT_FORMAT = 'crownspeakers/highlight';

const HighlightButton = ( { isActive, value, onChange } ) => (
	<RichTextToolbarButton
		icon="star-filled"
		title="Highlight (gold)"
		isActive={ isActive }
		onClick={ () => onChange( toggleFormat( value, { type: HIGHLIGHT_FORMAT } ) ) }
	/>
);

registerFormatType( HIGHLIGHT_FORMAT, {
	title: 'Highlight (gold)',
	tagName: 'span',
	className: 'is-highlight',
	edit: HighlightButton,
} );
