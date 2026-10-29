// Speakers grid toolbar: "Refine results" panel, instant sort, and clean URLs (empty fields left out).
document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.wp-block-crownspeakers-speakers-grid .speakers-grid__toolbar').forEach((form) => {
		const toggle = form.querySelector('.speakers-grid__refine');
		const panel = form.querySelector('.speakers-grid__panel');

		if (toggle && panel) {
			toggle.addEventListener('click', () => {
				const open = toggle.getAttribute('aria-expanded') !== 'true';

				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
				panel.hidden = !open;

				if (open) {
					panel.querySelector('input, select')?.focus();
				}
			});

			form.addEventListener('keydown', (event) => {
				if (event.key === 'Escape' && !panel.hidden) {
					toggle.click();
					toggle.focus();
				}
			});
		}

		// Changing the sort applies it straight away.
		form.querySelectorAll('[data-auto-submit]').forEach((select) => {
			select.addEventListener('change', () => form.requestSubmit());
		});

		// Leave empty fields (and the default sort) out of the URL.
		form.addEventListener('submit', () => {
			form.querySelectorAll('input[name], select[name]').forEach((field) => {
				const isDefaultSort = field.name === 'sort' && field.value === 'relevance';

				if (!field.value || isDefaultSort) {
					field.disabled = true;
				}
			});
		});

		// Re-enable the fields if the page is shown again from the back/forward cache.
		window.addEventListener('pageshow', () => {
			form.querySelectorAll('[name]').forEach((field) => {
				field.disabled = false;
			});
		});
	});
});
