import { useBlockProps, RichText } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

import icons from './icons.json';

const NEW_ITEM = { icon: 'heart', title: '', text: '' };

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
		dangerouslySetInnerHTML={ { __html: ( icons[ name ] || icons.heart ).svg } }
	/>
);

export default function Edit( { attributes, setAttributes } ) {
	const { title, items } = attributes;

	const blockProps = useBlockProps();

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

	const removeItem = ( index ) => {
		setAttributes( { items: items.filter( ( _, i ) => i !== index ) } );
	};

	const addItem = () => {
		setAttributes( { items: [ ...items, { ...NEW_ITEM } ] } );
	};

	return (
		<section { ...blockProps }>
			<div className="section-inner">
				<RichText
					tagName="h2"
					className="features__title"
					value={ title }
					allowedFormats={ [ 'core/italic' ] }
					onChange={ ( value ) => setAttributes( { title: value } ) }
					placeholder="Title (optional)"
				/>

				<ul className="features__list">
					{ items.map( ( item, index ) => (
						<li className="features__item" key={ index }>
							<span className="features__icon">
								<Icon name={ item.icon } />
							</span>

							<RichText
								tagName="h3"
								className="features__item-title"
								value={ item.title }
								allowedFormats={ [ 'core/italic' ] }
								onChange={ ( value ) => updateItem( index, 'title', value ) }
								placeholder="Feature title"
							/>

							<RichText
								tagName="p"
								className="features__item-text"
								value={ item.text }
								allowedFormats={ [ 'core/bold', 'core/italic', 'core/link' ] }
								onChange={ ( value ) => updateItem( index, 'text', value ) }
								placeholder="Feature description"
							/>

							<div className="features__controls">
								<div className="features__control-buttons">
									<Button
										icon="arrow-left-alt2"
										label="Move left"
										size="small"
										disabled={ index === 0 }
										onClick={ () => moveItem( index, -1 ) }
									/>
									<Button
										icon="arrow-right-alt2"
										label="Move right"
										size="small"
										disabled={ index === items.length - 1 }
										onClick={ () => moveItem( index, 1 ) }
									/>
									<Button
										icon="trash"
										label="Remove feature"
										size="small"
										isDestructive
										onClick={ () => removeItem( index ) }
									/>
								</div>
							</div>
						</li>
					) ) }
				</ul>

				<Button variant="secondary" icon="plus" onClick={ addItem }>
					Add feature
				</Button>
			</div>
		</section>
	);
}
