import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';

export default function Edit({ attributes, setAttributes }) {

	const { menuId } = attributes;

	const menus = useSelect((select) => {
		return select('core').getEntityRecords(
			'taxonomy',
			'nav_menu',
			{
				per_page: -1
			}
		);
	}, []);


	const options = [
		{
			label: 'Select menu',
			value: 0
		},
		...(menus || []).map((menu) => ({
			label: menu.name,
			value: menu.id
		}))
	];


	return (
		<>
			<InspectorControls>
				<PanelBody title="Navigation Settings">
					<SelectControl
						label="Menu"
						value={menuId}
						options={options}
						onChange={(value) =>
							setAttributes({
								menuId: Number(value)
							})
						}
					/>
				</PanelBody>
			</InspectorControls>


			<div {...useBlockProps()}>
				Navigation preview
			</div>
		</>
	);
}