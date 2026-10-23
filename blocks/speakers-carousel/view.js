
import Swiper from 'swiper';
import { Autoplay, Navigation } from 'swiper/modules';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll(
			'.wp-block-crownspeakers-speakers-carousel .speakers-carousel__swiper'
		)
		.forEach((swiperElement) => {
			if (swiperElement.swiper) {
				return;
			}

			const block = swiperElement.closest(
				'.wp-block-crownspeakers-speakers-carousel'
			);

			const autoplay = block?.dataset.autoplay === 'true';

			// Every slide, with the tabs it belongs to (`data-tabs`), kept so tabs can swap them in and out.
			const allSlides = Array.from(swiperElement.querySelectorAll('.speakers-carousel__item'));
			const tabs = block ? Array.from(block.querySelectorAll('.speakers-carousel__tab')) : [];
			const slidesFor = (tab) =>
				allSlides.filter((slide) => (slide.dataset.tabs || '').split(' ').includes(tab));

			// Start with only the active tab's speakers.
			const activeTab = swiperElement.dataset.activeTab;

			if (activeTab) {
				const activeSlides = slidesFor(activeTab);

				allSlides
					.filter((slide) => !activeSlides.includes(slide))
					.forEach((slide) => slide.remove());
			}

			const swiper = new Swiper(swiperElement, {
				modules: [Autoplay, Navigation],
				slidesPerView: 1,
				spaceBetween: 16,
				loop: false,
				grabCursor: true,
				watchOverflow: true,

				autoplay: autoplay
					? {
							delay: 3000,
							disableOnInteraction: false,
						}
					: false,

				navigation: {
					prevEl: block?.querySelector('.speakers-carousel__arrow--prev'),
					nextEl: block?.querySelector('.speakers-carousel__arrow--next'),
				},

				breakpoints: {
					480: {
						slidesPerView: 2,
						spaceBetween: 16,
					},
					768: {
						slidesPerView: 3,
						spaceBetween: 24,
					},
					1024: {
						slidesPerView: 4,
						spaceBetween: 32,
					},
				},
			});

			tabs.forEach((tabButton) => {
				tabButton.addEventListener('click', () => {
					const tab = tabButton.dataset.tab;

					tabs.forEach((button) => {
						const isActive = button === tabButton;

						button.classList.toggle('is-active', isActive);
						button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
					});

					// Swap the slides in the DOM (Swiper's appendSlide needs its Manipulation module), then re-measure.
					swiper.wrapperEl.replaceChildren(...slidesFor(tab));
					swiper.update();
					swiper.slideTo(0, 0);

					// Re-sync favorite buttons on the slides that were just put back.
					document.dispatchEvent(new CustomEvent('cs:favorites-refresh'));
				});
			});
		});
});
