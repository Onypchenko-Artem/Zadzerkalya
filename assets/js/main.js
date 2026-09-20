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
