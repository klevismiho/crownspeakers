
import Swiper from 'swiper';
import { Autoplay } from 'swiper/modules';

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

			new Swiper(swiperElement, {
				modules: [Autoplay],
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
