import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, title, subtitle } = attributes;

	const blockProps = useBlockProps();

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<RichText
					tagName="p"
					className="page-header__eyebrow"
					value={ eyebrow }
					allowedFormats={ [] }
					onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
					placeholder="Eyebrow text (optional)"
				/>

				<RichText
					tagName="h1"
					className="page-header__title"
					value={ title }
					allowedFormats={ [ 'core/italic' ] }
					onChange={ ( value ) => setAttributes( { title: value } ) }
					placeholder="Page title (empty = the page’s own title)"
				/>

				<RichText
					tagName="p"
					className="page-header__subtitle"
					value={ subtitle }
					allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
					onChange={ ( value ) => setAttributes( { subtitle: value } ) }
					placeholder="Paragraph (optional)"
				/>
			</div>
		</section>
	);
}
