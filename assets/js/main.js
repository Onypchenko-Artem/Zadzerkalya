(() => {
	const toggle = document.querySelector('.nav-toggle');
	const nav = document.querySelector('.header-nav');

	if (!toggle || !nav) {
		return;
	}

	toggle.addEventListener('click', () => {
		const open = nav.classList.toggle('is-open');
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		document.body.classList.toggle('nav-open', open);
	});
})();

(() => {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	document.querySelectorAll('.faq__item').forEach((item) => {
		const summary = item.querySelector('summary');
		const panel = item.querySelector('.faq__panel');

		if (!summary || !panel) {
			return;
		}

		let animation = null;

		const run = (from, to, open) => {
			if (animation) {
				animation.cancel();
			}

			animation = panel.animate(
				{
					height: [`${from}px`, `${to}px`],
					opacity: [open ? 0 : 1, open ? 1 : 0],
				},
				{ duration: 320, easing: 'ease' }
			);

			animation.onfinish = () => {
				animation = null;
				item.open = open;
				item.classList.remove('is-collapsed');
				panel.style.removeProperty('height');
				panel.style.removeProperty('opacity');
			};
		};

		summary.addEventListener('click', (event) => {
			event.preventDefault();

			if (item.open && !item.classList.contains('is-collapsed')) {
				item.classList.add('is-collapsed');
				run(panel.offsetHeight, 0, false);
				return;
			}

			item.classList.remove('is-collapsed');
			const from = item.open ? panel.offsetHeight : 0;
			item.open = true;
			run(from, panel.offsetHeight, true);
		});
	});
})();

(() => {
	const header = document.querySelector('.site-header');

	if (!header) {
		return;
	}

	let previousY = Math.max(window.scrollY, 0);
	let ticking = false;

	const updateHeader = () => {
		const currentY = Math.max(window.scrollY, 0);
		const delta = currentY - previousY;
		const navigationOpen = document.body.classList.contains('nav-open');

		if (currentY <= 16 || delta < -4 || navigationOpen || header.contains(document.activeElement)) {
			header.classList.remove('is-hidden');
		} else if (delta > 4 && currentY > header.offsetHeight) {
			header.classList.add('is-hidden');
		}

		previousY = currentY;
		ticking = false;
	};

	window.addEventListener(
		'scroll',
		() => {
			if (!ticking) {
				window.requestAnimationFrame(updateHeader);
				ticking = true;
			}
		},
		{ passive: true }
	);
})();

(() => {
	if (typeof lottie === 'undefined' || typeof zadzerkalyaLottie === 'undefined') {
		return;
	}

	const cache = {};
	const path = zadzerkalyaLottie.path;
	const hoverMq = window.matchMedia('(hover: hover) and (min-width: 1025px) and (prefers-reduced-motion: no-preference)');

	const breakpoint = () => {
		const width = window.innerWidth;
		if (width <= 767) {
			return 'mobile';
		}
		if (width <= 1024) {
			return 'tablet';
		}
		return 'desktop';
	};

	const loadJson = async (name) => {
		if (!cache[name]) {
			cache[name] = fetch(path + name + '.json').then((response) => response.json());
		}
		return cache[name];
	};

	const initButton = (button) => {
		const variant = button.dataset.variant || 'primary';
		const container = button.querySelector('.lottie-button__anim');
		if (!container) {
			return;
		}

		let animation = null;
		let currentName = '';

		const mount = async () => {
			const name = `button-${variant}-${breakpoint()}`;
			if (name === currentName) {
				return;
			}
			currentName = name;

			if (animation) {
				animation.destroy();
				animation = null;
				container.replaceChildren();
			}

			const data = await loadJson(name);
			if (currentName !== name) {
				return;
			}

			animation = lottie.loadAnimation({
				container,
				renderer: 'svg',
				loop: true,
				autoplay: false,
				animationData: JSON.parse(JSON.stringify(data)),
			});
			animation.goToAndStop(0, true);
		};

		button.addEventListener('mouseenter', () => {
			if (animation && hoverMq.matches) {
				animation.goToAndPlay(0, true);
			}
		});

		button.addEventListener('mouseleave', () => {
			if (animation) {
				animation.goToAndStop(0, true);
			}
		});

		mount();

		let timer;
		window.addEventListener('resize', () => {
			clearTimeout(timer);
			timer = setTimeout(mount, 200);
		});
	};

	document.querySelectorAll('.lottie-button').forEach(initButton);
})();

(() => {
	document.querySelectorAll('[data-reviews]').forEach((section) => {
		const viewport = section.querySelector('.reviews__viewport');
		const track = section.querySelector('.reviews__track');
		const previous = section.querySelector('.reviews__arrow--prev');
		const next = section.querySelector('.reviews__arrow--next');

		if (!viewport || !track || !previous || !next) {
			return;
		}

		const cards = Array.from(track.querySelectorAll('.reviews__card'));

		const scrollStep = () => {
			const card = track.querySelector('.reviews__card');
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
					clone.querySelectorAll('a, button, input, select, textarea, [tabindex]').forEach((element) => {
						element.setAttribute('tabindex', '-1');
					});
				});

				before.append(beforeClone);
				after.append(afterClone);
			});

			track.prepend(before);
			track.append(after);
			jumpTo(cycleWidth());
		}

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
		});

		let scrollTimer;
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
			resizeTimer = setTimeout(() => jumpTo(cycleWidth()), 150);
		});

		cards.forEach((card) => {
			const button = card.querySelector('.reviews__more');
			if (!button) {
				return;
			}

			button.addEventListener('click', () => {
				const label = button.querySelector('span');
				if (!label) {
					return;
				}

				const expanded = card.classList.toggle('is-expanded');
				button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
				label.textContent = expanded ? button.dataset.expandedLabel : button.dataset.collapsedLabel;
			});
		});
	});
})();
