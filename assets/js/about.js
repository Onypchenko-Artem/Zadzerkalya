(() => {
	const hero = document.querySelector('.about-hero');

	if (!hero) {
		return;
	}

	window.requestAnimationFrame(() => {
		hero.classList.add('is-ready');
	});
})();

(() => {
	document.querySelectorAll('[data-gallery]').forEach((gallery) => {
		const viewport = gallery.querySelector('.about-gallery__viewport');
		const track = gallery.querySelector('.about-gallery__track');
		const previous = gallery.querySelector('.about-gallery__arrow--prev');
		const next = gallery.querySelector('.about-gallery__arrow--next');

		if (!viewport || !track || !previous || !next) {
			return;
		}

		const scrollStep = () => {
			const card = track.querySelector('.about-gallery__card');
			const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;

			return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth;
		};

		const syncControls = () => {
			const maximum = viewport.scrollWidth - viewport.clientWidth;
			previous.disabled = viewport.scrollLeft <= 1;
			next.disabled = viewport.scrollLeft >= maximum - 1;
		};

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
		});

		viewport.addEventListener('scroll', syncControls, { passive: true });
		window.addEventListener('resize', syncControls);

		if ('ResizeObserver' in window) {
			new ResizeObserver(syncControls).observe(viewport);
		}

		syncControls();
	});
})();
