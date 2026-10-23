import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<section { ...blockProps }>
			Favorites: on the website, this shows the visitor’s saved speakers.
		</section>
	);
}
