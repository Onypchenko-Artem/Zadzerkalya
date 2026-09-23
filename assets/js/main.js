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
	let started = false;

	const boot = () => {
		if (started || typeof lottie === 'undefined' || typeof zadzerkalyaButtonAnims === 'undefined') {
			return;
		}
		started = true;

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

		const initButton = (button) => {
			const variant = button.dataset.variant || 'primary';
			const container = button.querySelector('.lottie-button__anim');
			if (!container) {
				return;
			}

			let animation = null;
			let currentName = '';

			const mount = () => {
				const name = `button-${variant}-${breakpoint()}`;
				const data = zadzerkalyaButtonAnims[name];
				if (!data || name === currentName) {
					return;
				}
				currentName = name;

				if (animation) {
					animation.destroy();
					animation = null;
					container.replaceChildren();
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
	};

	boot();
	window.addEventListener('load', boot);
})();

(() => {
	const media = window.matchMedia('(min-width: 768px)');

	document.querySelectorAll('[data-pinned-list]').forEach((shell) => {
		const section = shell.querySelector('[data-scroll-section]');
		const list = shell.querySelector('[data-scroll-list]');

		if (!section || !list) {
			return;
		}

		let scrollDistance = 0;
		let stickyOffset = 0;
		let frame = 0;

		const sync = () => {
			frame = 0;

			if (!media.matches || !scrollDistance) {
				return;
			}

			const start = shell.getBoundingClientRect().top + window.scrollY - stickyOffset;
			const progress = Math.min(Math.max(window.scrollY - start, 0), scrollDistance);
			list.scrollTop = progress;
		};

		const requestSync = () => {
			if (!frame) {
				frame = window.requestAnimationFrame(sync);
			}
		};

		const measure = () => {
			if (!media.matches) {
				shell.classList.remove('is-scroll-driven');
				shell.style.removeProperty('--pinned-list-height');
				list.scrollTop = 0;
				scrollDistance = 0;
				stickyOffset = 0;
				return;
			}

			shell.classList.add('is-scroll-driven');
			scrollDistance = Math.max(list.scrollHeight - list.clientHeight, 0);
			stickyOffset = Number.parseFloat(getComputedStyle(section).top) || 0;
			shell.style.setProperty(
				'--pinned-list-height',
				`${section.offsetHeight + scrollDistance}px`
			);
			requestSync();
		};

		window.addEventListener('scroll', requestSync, { passive: true });
		window.addEventListener('resize', measure);
		window.addEventListener('load', measure);
		media.addEventListener('change', measure);

		if ('ResizeObserver' in window) {
			const observer = new ResizeObserver(measure);
			observer.observe(section);
			observer.observe(list);
			Array.from(list.children).forEach((item) => observer.observe(item));
		}

		measure();
	});
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

(() => {
	const fitLoveLine = () => {
		document.querySelectorAll('.numbers__love').forEach((root) => {
			const path = root.querySelector('#numbers-love-path');
			const text = root.querySelector('text');
			const textPath = root.querySelector('textPath');
			const svg = text?.ownerSVGElement;

			if (!path || !text || !textPath || !svg?.getClientRects().length) {
				return;
			}

			const count = textPath.getNumberOfChars();
			const fontSize = Number.parseFloat(getComputedStyle(text).fontSize);
			if (!count || !fontSize) {
				return;
			}

			const pathLength = path.getTotalLength();
			text.style.letterSpacing = '0em';
			const natural = textPath.getComputedTextLength();
			if (!natural) {
				return;
			}

			const apply = (gaps) => {
				text.style.letterSpacing = `${((pathLength - natural) / gaps) / fontSize}em`;
			};

			const endpointGap = () => {
				const end = textPath.getEndPositionOfChar(count - 1);
				const target = path.getPointAtLength(pathLength);
				return Math.hypot(end.x - target.x, end.y - target.y);
			};

			apply(count);
			const gapByCount = endpointGap();

			if (gapByCount > 1 && count > 1) {
				apply(count - 1);
				if (endpointGap() > gapByCount) {
					apply(count);
				}
			}
		});
	};

	if (document.fonts?.ready) {
		document.fonts.ready.then(fitLoveLine);
	} else {
		fitLoveLine();
	}

	window.addEventListener('resize', fitLoveLine);
})();
