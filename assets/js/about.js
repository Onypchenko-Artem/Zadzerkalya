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

		const cards = Array.from(track.querySelectorAll('.about-gallery__card'));

		const scrollStep = () => {
			const card = track.querySelector('.about-gallery__card');
			const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;

			return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth;
		};

		const cycleWidth = () => cards.length * scrollStep();

		const jumpTo = (left) => {
			viewport.style.scrollBehavior = 'auto';
			viewport.scrollLeft = left;
			viewport.style.removeProperty('scroll-behavior');
		};

		if (cards.length > 1) {
			const before = document.createDocumentFragment();
			const after = document.createDocumentFragment();

			cards.forEach((card) => {
				const beforeClone = card.cloneNode(true);
				const afterClone = card.cloneNode(true);

				[beforeClone, afterClone].forEach((clone) => {
					clone.setAttribute('aria-hidden', 'true');
				});

				before.append(beforeClone);
				after.append(afterClone);
			});

			track.prepend(before);
			track.append(after);
			jumpTo(cycleWidth());
		} else {
			previous.disabled = true;
			next.disabled = true;
		}

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
		});

		const normalizePosition = () => {
			if (cards.length <= 1) {
				return;
			}

			const width = cycleWidth();

			if (viewport.scrollLeft < width - 1) {
				jumpTo(viewport.scrollLeft + width);
			} else if (viewport.scrollLeft >= width * 2 - 1) {
				jumpTo(viewport.scrollLeft - width);
			}
		};

		let scrollTimer;
		viewport.addEventListener(
			'scroll',
			() => {
				clearTimeout(scrollTimer);
				scrollTimer = setTimeout(normalizePosition, 120);
			},
			{ passive: true }
		);

		if ('onscrollend' in window) {
			viewport.addEventListener('scrollend', normalizePosition);
		}

		let resizeTimer;
		window.addEventListener('resize', () => {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(() => {
				if (cards.length > 1) {
					jumpTo(cycleWidth());
				}
			}, 150);
		});
	});
})();
