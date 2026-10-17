import Swiper from 'swiper';
import { Autoplay } from 'swiper/modules';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.logo-grid__swiper').forEach((swiperElement) => {
		const block = swiperElement.closest('.wp-block-crownspeakers-logo-slider');
		const autoplay = block?.dataset.autoplay === 'true';

		new Swiper(swiperElement, {
			modules: [Autoplay],
			loop: true,
			freeMode: true,
			grabCursor: true,
			slidesPerView: 2,
			spaceBetween: 16,
			autoplay: autoplay ? { delay: 3000, disableOnInteraction: false } : false,
			breakpoints: {
				768: { slidesPerView: 3, spaceBetween: 24 },
				1024: { slidesPerView: 4, spaceBetween: 32 },
				1280: { slidesPerView: 6, spaceBetween: 32 },
			},
		});
	});
});