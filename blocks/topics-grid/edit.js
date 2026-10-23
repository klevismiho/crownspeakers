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
					placeholder="Title (optional)"
				/>

				<p className="topics-grid__editor-note">
					All speaker topics are shown here as a grid on the website.
				</p>
			</div>
		</section>
	);
}
