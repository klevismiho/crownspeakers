import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { heading } = attributes;

	const blockProps = useBlockProps();

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<RichText
					tagName="h2"
					className="section-title"
					value={ heading }
					allowedFormats={ [ 'core/italic' ] }
					onChange={ ( value ) => setAttributes( { heading: value } ) }
					placeholder="Title"
				/>

				<p>The topic cards are shown here on the website.</p>
			</div>
		</section>
	);
}
