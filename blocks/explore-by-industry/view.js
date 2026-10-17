import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll(
			'.wp-block-crownspeakers-explore-by-industry .explore-by-industry__swiper'
		)
		.forEach((swiperElement) => {
			if (swiperElement.swiper) {
				return;
			}

			const block = swiperElement.closest(
				'.wp-block-crownspeakers-explore-by-industry'
			);

			new Swiper(swiperElement, {
				modules: [Navigation],
				slidesPerView: 1.2,
				spaceBetween: 16,
				grabCursor: true,
				watchOverflow: true,

				navigation: {
					prevEl: block?.querySelector('.explore-by-industry__arrow--prev'),
					nextEl: block?.querySelector('.explore-by-industry__arrow--next'),
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
						spaceBetween: 28,
					},
					1320: {
						slidesPerView: 5,
						spaceBetween: 28,
					},
				},
			});
		});
});
