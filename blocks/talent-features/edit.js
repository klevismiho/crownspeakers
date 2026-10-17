import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<section {...blockProps}>
			Talent features edits here
		</section>
	);
}
