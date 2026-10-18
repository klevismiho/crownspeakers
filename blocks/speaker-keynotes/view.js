document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll('.wp-block-crownspeakers-speaker-keynotes .speaker-keynotes__toggle')
		.forEach((toggle) => {
			toggle.addEventListener('click', () => {
				const isOpen = toggle.getAttribute('aria-expanded') === 'true';

				toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
				toggle.closest('.speaker-keynotes__item')?.classList.toggle('is-open', ! isOpen);
			});
		});
});
