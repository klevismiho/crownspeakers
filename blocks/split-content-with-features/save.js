import { InnerBlocks } from '@wordpress/block-editor';

// The paragraphs are saved as inner blocks; render.php outputs them as $content.
export default function save() {
	return <InnerBlocks.Content />;
}
