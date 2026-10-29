import {
	useBlockProps,
	useInnerBlocksProps,
	RichText,
} from '@wordpress/block-editor';

// Right column: regular paragraphs (add, remove and reorder them like any paragraph).
const ALLOWED_BLOCKS = [ 'core/paragraph', 'core/list' ];

const TEMPLATE = [
	[ 'core/paragraph', { placeholder: 'Write a paragraph…' } ],
];

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, title } = attributes;

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'split-content__text' },
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
		}
	);

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<div className="split-content__heading">
					<RichText
						tagName="p"
						className="split-content__eyebrow"
						value={ eyebrow }
						allowedFormats={ [] }
						onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
						placeholder="Eyebrow (optional)"
					/>

					<RichText
						tagName="h2"
						className="split-content__title"
						value={ title }
						allowedFormats={ [ 'core/italic' ] }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder="Title"
					/>
				</div>

				<div { ...innerBlocksProps } />
			</div>
		</section>
	);
}
