import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<section { ...blockProps }>
			Contact: contact details and the event inquiry form (shown on the website).
		</section>
	);
}
