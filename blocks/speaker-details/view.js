// Collapse long bios behind "Read more"; short bios show in full with no button.
document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll('.wp-block-crownspeakers-speaker-details')
		.forEach((block) => {
			const bio = block.querySelector('.speaker-details__bio');
			const button = block.querySelector('.speaker-details__more');

			if (!bio || !button) {
				return;
			}

			bio.classList.add('is-collapsed');

			if (bio.scrollHeight <= bio.clientHeight) {
				bio.classList.remove('is-collapsed');
				return;
			}

			button.hidden = false;

			button.addEventListener('click', () => {
				const isOpen = button.getAttribute('aria-expanded') === 'true';

				button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
				button.textContent = isOpen ? 'Read more' : 'Read less';
				bio.classList.toggle('is-collapsed', isOpen);
			});
		});
});
