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

		const scrollStep = () => {
			const card = track.querySelector('.reviews__card');
			const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
			return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth;
		};

		const updateControls = () => {
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

		viewport.addEventListener('scroll', updateControls, { passive: true });
		window.addEventListener('resize', updateControls);
		updateControls();

		section.querySelectorAll('.reviews__more').forEach((button) => {
			button.addEventListener('click', () => {
				const card = button.closest('.reviews__card');
				const label = button.querySelector('span');
				if (!card || !label) {
					return;
				}

				const expanded = card.classList.toggle('is-expanded');
				button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
				label.textContent = expanded ? button.dataset.expandedLabel : button.dataset.collapsedLabel;
			});
		});
	});
})();
