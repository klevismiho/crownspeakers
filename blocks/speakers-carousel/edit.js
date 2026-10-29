import {
	useBlockProps,
	RichText,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';

import {
	PanelBody,
	Button,
	TextControl,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';

import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const {
		heading,
		buttonText,
		buttonUrl,
		logos,
		autoplay,
		showTabs,
		background,
	} = attributes;

	const blockProps = useBlockProps();

	const updateLogo = (index, field, value) => {
		const updated = logos.map((item, i) =>
			i === index ? { ...item, [field]: value } : item
		);

		setAttributes({ logos: updated });
	};

	const removeLogo = (index) => {
		setAttributes({
			logos: logos.filter((_, i) => i !== index),
		});
	};

	const addLogo = (media) => {
		setAttributes({
			logos: [
				...logos,
				{
					src: media.url,
					alt: media.alt || '',
					url: '',
				},
			],
		});
	};

	const moveLogo = (index, direction) => {
		const updated = [...logos];
		const newIndex = index + direction;

		if (newIndex < 0 || newIndex >= updated.length) {
			return;
		}

		[updated[index], updated[newIndex]] = [
			updated[newIndex],
			updated[index],
		];

		setAttributes({ logos: updated });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title="Carousel Settings" initialOpen={true}>
					<ToggleControl
						label="Autoplay"
						checked={autoplay}
						onChange={(value) =>
							setAttributes({ autoplay: value })
						}
					/>

					<ToggleControl
						label="Show tabs"
						help={
							showTabs
								? 'Featured + topic tabs.'
								: 'Featured speakers only, no tabs.'
						}
						checked={showTabs}
						onChange={(value) =>
							setAttributes({ showTabs: value })
						}
					/>

					<SelectControl
						label="Background"
						value={background}
						options={[
							{ label: 'None (white)', value: 'none' },
							{ label: 'Light gray', value: 'light' },
						]}
						onChange={(value) =>
							setAttributes({ background: value })
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>

				<PanelBody title="Logos" initialOpen={true}>
					{logos.map((logo, index) => (
						<div
							key={index}
							style={{
								marginBottom: '16px',
								paddingBottom: '16px',
								borderBottom: '1px solid #e0e0e0',
							}}
						>
							<img
								src={logo.src}
								alt={logo.alt}
								style={{
									maxWidth: '100%',
									marginBottom: '8px',
								}}
							/>

							<TextControl
								label="Link URL (optional)"
								value={logo.url}
								onChange={(value) =>
									updateLogo(index, 'url', value)
								}
							/>

							<TextControl
								label="Alt text"
								value={logo.alt}
								onChange={(value) =>
									updateLogo(index, 'alt', value)
								}
							/>

							<div
								style={{
									display: 'flex',
									gap: '8px',
									marginBottom: '8px',
								}}
							>
								<Button
									icon="arrow-up-alt2"
									label="Move up"
									variant="secondary"
									onClick={() =>
										moveLogo(index, -1)
									}
									disabled={index === 0}
								/>

								<Button
									icon="arrow-down-alt2"
									label="Move down"
									variant="secondary"
									onClick={() =>
										moveLogo(index, 1)
									}
									disabled={
										index === logos.length - 1
									}
								/>

								<Button
									isDestructive
									variant="secondary"
									onClick={() =>
										removeLogo(index)
									}
								>
									Remove
								</Button>
							</div>
						</div>
					))}

					<MediaUploadCheck>
						<MediaUpload
							onSelect={addLogo}
							allowedTypes={['image']}
							render={({ open }) => (
								<Button
									variant="primary"
									onClick={open}
								>
									Add Logo
								</Button>
							)}
						/>
					</MediaUploadCheck>
				</PanelBody>

				<PanelBody title="Button" initialOpen={false}>
					<TextControl
						label="Button URL"
						value={buttonUrl}
						onChange={(value) =>
							setAttributes({ buttonUrl: value })
						}
					/>
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<RichText
					tagName="h2"
					className="section-title"
					value={heading}
					onChange={(value) =>
						setAttributes({ heading: value })
					}
					placeholder="Enter heading…"
				/>

				<div className="logo-grid__logos">
					{logos.length === 0 && (
						<p style={{ opacity: 0.5 }}>
							Add logos via the sidebar panel.
						</p>
					)}

					{logos.map((logo, index) => (
						<div
							className="logo-grid__item"
							key={index}
						>
							<img
								src={logo.src}
								alt={logo.alt}
							/>
						</div>
					))}
				</div>

				<div className="wp-block-button">
					<RichText
						tagName="span"
						value={buttonText}
						className="wp-block-button__link wp-element-button"
						onChange={(value) =>
							setAttributes({ buttonText: value })
						}
						placeholder="Button text…"
					/>
				</div>
			</section>
		</>
	);
}