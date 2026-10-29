import {
	useBlockProps,
	useInnerBlocksProps,
	RichText,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	MediaPlaceholder,
} from '@wordpress/block-editor';
import { PanelBody, Button, TextareaControl } from '@wordpress/components';

// Right column text: regular paragraphs (add, remove and reorder them like any paragraph).
const ALLOWED_BLOCKS = [ 'core/paragraph', 'core/list' ];

const TEMPLATE = [
	[
		'core/paragraph',
		{
			content:
				'Based in the United States, the business has grown to work with speakers and clients around the world. The approach remains personal: listen carefully, understand the vision and give useful advice.',
		},
	],
	[
		'core/paragraph',
		{
			content:
				'That means helping corporations, associations and meeting planners find a speaker whose experience is relevant to the people in the room.',
		},
	],
];

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, title, imageId, imageUrl, imageAlt } = attributes;

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'our-story__text' },
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
		}
	);

	const selectImage = ( media ) => {
		setAttributes( {
			imageId: media?.id || 0,
			imageUrl: media?.sizes?.large?.url || media?.url || '',
			imageAlt: media?.alt || '',
		} );
	};

	const removeImage = () => {
		setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } );
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Image">
					{ imageUrl && (
						<img src={ imageUrl } alt="" style={ { width: '100%', height: 'auto', marginBottom: '12px' } } />
					) }

					<MediaUploadCheck>
						<MediaUpload
							onSelect={ selectImage }
							allowedTypes={ [ 'image' ] }
							value={ imageId }
							render={ ( { open } ) => (
								<Button variant="secondary" onClick={ open }>
									{ imageUrl ? 'Replace image' : 'Choose image' }
								</Button>
							) }
						/>
					</MediaUploadCheck>

					{ imageUrl && (
						<>
							<TextareaControl
								label="Alt text (describe the image)"
								value={ imageAlt }
								onChange={ ( value ) => setAttributes( { imageAlt: value } ) }
								__nextHasNoMarginBottom
							/>
							<Button variant="link" isDestructive onClick={ removeImage }>
								Remove image
							</Button>
						</>
					) }
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="section-inner">
					<div className="our-story__media">
						<RichText
							tagName="p"
							className="our-story__eyebrow"
							value={ eyebrow }
							allowedFormats={ [] }
							onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
							placeholder="Eyebrow (optional)"
						/>

						{ imageUrl ? (
							<img className="our-story__image" src={ imageUrl } alt={ imageAlt } />
						) : (
							<MediaPlaceholder
								icon="format-image"
								labels={ { title: 'Image', instructions: 'Upload an image or pick one from the Media Library.' } }
								accept="image/*"
								allowedTypes={ [ 'image' ] }
								onSelect={ selectImage }
							/>
						) }
					</div>

					<div className="our-story__content">
						<RichText
							tagName="h2"
							className="our-story__title"
							value={ title }
							allowedFormats={ [ 'core/italic' ] }
							onChange={ ( value ) => setAttributes( { title: value } ) }
							placeholder="Title"
						/>

						<div { ...innerBlocksProps } />
					</div>
				</div>
			</section>
		</>
	);
}
