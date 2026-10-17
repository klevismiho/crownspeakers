
import { useBlockProps } from '@wordpress/block-editor';

export default function save() {
	return (
		<section { ...useBlockProps.save() }>
			<h2>Trusted By Industry Experts.</h2>
		</section>
	);
}
