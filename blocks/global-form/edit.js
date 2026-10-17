import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<section {...blockProps}>
			Global form edits here
		</section>
	);
}
