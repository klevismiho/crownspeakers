import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll(
			'.wp-block-crownspeakers-speaker-quotes-carousel .speaker-quotes-carousel__swiper'
		)
		.forEach((swiperElement) => {
			if (swiperElement.swiper) {
				return;
			}

			const block = swiperElement.closest(
				'.wp-block-crownspeakers-speaker-quotes-carousel'
			);

			new Swiper(swiperElement, {
				modules: [Pagination],
				slidesPerView: 1,
				slidesPerGroup: 1,
				spaceBetween: 0,
				grabCursor: true,
				watchOverflow: true,

				pagination: {
					el: block?.querySelector('.speaker-quotes-carousel__pagination'),
					clickable: true,
				},

				// Slide a full page at a time so each bar is one page.
				breakpoints: {
					768: {
						slidesPerView: 2,
						slidesPerGroup: 2,
					},
					1024: {
						slidesPerView: 3,
						slidesPerGroup: 3,
					},
				},
			});
		});
});
