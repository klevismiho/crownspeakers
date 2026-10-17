import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	Button,
	TextControl,
	CheckboxControl,
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const {
		title,
		description,
		subtitle,
		buttons,
		statistics,
	} = attributes;

	const blockProps = useBlockProps();

	const updateStatistic = (index, field, value) => {
		const updated = statistics.map((item, i) =>
			i === index ? { ...item, [field]: value } : item
		);

		setAttributes({ statistics: updated });
	};

	const updateButton = (index, field, value) => {
		const updated = buttons.map((btn, i) =>
			i === index ? { ...btn, [field]: value } : btn
		);

		setAttributes({ buttons: updated });
	};

	const addButton = () => {
		setAttributes({
			buttons: [
				...buttons,
				{
					text: 'New Button',
					url: '#',
					outlined: false,
				},
			],
		});
	};

	const removeButton = (index) => {
		setAttributes({
			buttons: buttons.filter((_, i) => i !== index),
		});
	};

	return (
		<>
			<section {...blockProps}>
				Hero edits here
			</section>
		</>
	);
}