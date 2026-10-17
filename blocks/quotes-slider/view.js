import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

import 'swiper/css';

document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll(
			'.wp-block-crownspeakers-quotes-slider .quotes-slider__swiper'
		)
		.forEach((swiperElement) => {
			if (swiperElement.swiper) {
				return;
			}

			const block = swiperElement.closest(
				'.wp-block-crownspeakers-quotes-slider'
			);

			new Swiper(swiperElement, {
				modules: [Pagination],
				slidesPerView: 1,
				spaceBetween: 32,
				autoHeight: true,
				grabCursor: true,
				watchOverflow: true,

				pagination: {
					el: block?.querySelector('.quotes-slider__pagination'),
					clickable: true,
				},
			});
		});
});
