import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll(
			'.wp-block-crownspeakers-similar-speakers-carousel .similar-speakers-carousel__swiper'
		)
		.forEach((swiperElement) => {
			if (swiperElement.swiper) {
				return;
			}

			const block = swiperElement.closest(
				'.wp-block-crownspeakers-similar-speakers-carousel'
			);

			new Swiper(swiperElement, {
				modules: [Navigation],
				slidesPerView: 1,
				spaceBetween: 16,
				loop: false,
				grabCursor: true,
				watchOverflow: true,

				navigation: {
					prevEl: block?.querySelector('.similar-speakers-carousel__arrow--prev'),
					nextEl: block?.querySelector('.similar-speakers-carousel__arrow--next'),
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
		});
});
