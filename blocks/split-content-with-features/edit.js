import {
	useBlockProps,
	useInnerBlocksProps,
	RichText,
} from '@wordpress/block-editor';
import { Button, SelectControl } from '@wordpress/components';

// Shared with the Features block.
import icons from '../features/icons.json';

const ICON_OPTIONS = Object.entries( icons ).map( ( [ value, icon ] ) => ( {
	value,
	label: icon.label,
} ) );

// Left column text: regular paragraphs (add, remove and reorder them like any paragraph).
const ALLOWED_BLOCKS = [ 'core/paragraph', 'core/list' ];

const TEMPLATE = [
	[
		'core/paragraph',
		{
			content:
				'Crown has worked in the speaking business since 2004. Our agency was founded as a consultancy and is now a speakers bureau with a consultative approach.',
		},
	],
];

const NEW_ITEM = { icon: 'check', title: '', text: '' };

// Icon markup comes from the theme's own icons.json, not from user input.
const Icon = ( { name } ) => (
	<svg
		width="24"
		height="24"
		viewBox="0 0 24 24"
		fill="none"
		stroke="currentColor"
		strokeWidth="1.75"
		strokeLinecap="round"
		strokeLinejoin="round"
		aria-hidden="true"
		dangerouslySetInnerHTML={ { __html: ( icons[ name ] || icons.check ).svg } }
	/>
);

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, title, items } = attributes;

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'split-content-with-features__text' },
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
		}
	);

	const updateItem = ( index, field, value ) => {
		setAttributes( {
			items: items.map( ( item, i ) => ( i === index ? { ...item, [ field ]: value } : item ) ),
		} );
	};

	const moveItem = ( index, direction ) => {
		const target = index + direction;

		if ( target < 0 || target >= items.length ) {
			return;
		}

		const updated = [ ...items ];
		[ updated[ index ], updated[ target ] ] = [ updated[ target ], updated[ index ] ];
		setAttributes( { items: updated } );
	};

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<div className="split-content-with-features__content">
					<RichText
						tagName="p"
						className="split-content-with-features__eyebrow"
						value={ eyebrow }
						allowedFormats={ [] }
						onChange={ ( value ) => setAttributes( { eyebrow: value } ) }
						placeholder="Eyebrow (optional)"
					/>

					<RichText
						tagName="h2"
						className="split-content-with-features__title"
						value={ title }
						allowedFormats={ [ 'core/italic' ] }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder="Title"
					/>

					<div { ...innerBlocksProps } />
				</div>

				<div>
					<ul className="split-content-with-features__list">
						{ items.map( ( item, index ) => (
							<li className="split-content-with-features__item" key={ index }>
								<span className="split-content-with-features__icon">
									<Icon name={ item.icon } />
								</span>

								<div className="split-content-with-features__item-body">
									<RichText
										tagName="h3"
										className="split-content-with-features__item-title"
										value={ item.title }
										allowedFormats={ [ 'core/italic' ] }
										onChange={ ( value ) => updateItem( index, 'title', value ) }
										placeholder="Feature title"
									/>

									<RichText
										tagName="p"
										className="split-content-with-features__item-text"
										value={ item.text }
										allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
										onChange={ ( value ) => updateItem( index, 'text', value ) }
										placeholder="Feature description"
									/>

									<div className="split-content-with-features__controls">
										<SelectControl
											label="Icon"
											value={ item.icon }
											options={ ICON_OPTIONS }
											onChange={ ( value ) => updateItem( index, 'icon', value ) }
											__nextHasNoMarginBottom
										/>

										<Button
											icon="arrow-up-alt2"
											label="Move up"
											size="small"
											disabled={ index === 0 }
											onClick={ () => moveItem( index, -1 ) }
										/>
										<Button
											icon="arrow-down-alt2"
											label="Move down"
											size="small"
											disabled={ index === items.length - 1 }
											onClick={ () => moveItem( index, 1 ) }
										/>
										<Button
											icon="trash"
											label="Remove feature"
											size="small"
											isDestructive
											onClick={ () =>
												setAttributes( { items: items.filter( ( _, i ) => i !== index ) } )
											}
										/>
									</div>
								</div>
							</li>
						) ) }
					</ul>

					<Button
						variant="secondary"
						icon="plus"
						onClick={ () => setAttributes( { items: [ ...items, { ...NEW_ITEM } ] } ) }
					>
						Add feature
					</Button>
				</div>
			</div>
		</section>
	);
}
