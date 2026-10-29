import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { heading } = attributes;

	const blockProps = useBlockProps();

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<RichText
					tagName="h2"
					className="team__title"
					value={ heading }
					allowedFormats={ [ 'core/italic' ] }
					onChange={ ( value ) => setAttributes( { heading: value } ) }
					placeholder="Title (optional)"
				/>

				<p className="team__editor-note">
					Team members (Team → All Team Members) are shown here on the website, in their Order.
				</p>
			</div>
		</section>
	);
}
