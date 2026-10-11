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
	const item = document.querySelector('.header-nav .menu-item--services');
	const mega = document.querySelector('#services-mega');
	const link = item ? item.querySelector('a') : null;
	const header = document.querySelector('.site-header');
	const desktop = window.matchMedia('(hover: hover) and (pointer: fine) and (min-width: 1280px)');

	if (!item || !mega || !link) {
		return;
	}

	const cats = Array.from(mega.querySelectorAll('.services-mega__cat'));
	const panels = Array.from(mega.querySelectorAll('.services-mega__panel'));
	const photos = Array.from(mega.querySelectorAll('.services-mega__photo'));
	let closeTimer = 0;
	let isOpen = false;
	let closing = false;

	const activate = (target) => {
		cats.forEach((cat) => {
			cat.classList.toggle('is-active', cat.dataset.megaTarget === target);
		});
		panels.forEach((panel) => {
			panel.classList.toggle('is-active', panel.id === target);
		});
		photos.forEach((photo) => {
			photo.classList.toggle('is-active', photo.dataset.megaTarget === target);
		});
	};

	const syncOffset = () => {
		if (!header) {
			return;
		}

		mega.style.paddingTop = `${header.offsetHeight}px`;
	};

	const open = () => {
		if (!desktop.matches || closing) {
			return;
		}

		window.clearTimeout(closeTimer);
		syncOffset();
		isOpen = true;
		mega.classList.add('is-open');
		mega.setAttribute('aria-hidden', 'false');
		item.classList.add('is-open');
		link.setAttribute('aria-expanded', 'true');
		document.body.classList.add('services-mega-open');
	};

	const close = () => {
		window.clearTimeout(closeTimer);
		if (!isOpen) {
			return;
		}

		isOpen = false;
		mega.classList.remove('is-open');
		mega.setAttribute('aria-hidden', 'true');
		item.classList.remove('is-open');
		link.setAttribute('aria-expanded', 'false');
		document.body.classList.remove('services-mega-open');

		const first = cats[0];
		if (first) {
			activate(first.dataset.megaTarget);
		}
	};

	const scheduleClose = (delay = 160) => {
		window.clearTimeout(closeTimer);
		closeTimer = window.setTimeout(close, delay);
	};

	item.addEventListener('mouseenter', open);
	item.addEventListener('mouseleave', (event) => {
		const next = event.relatedTarget;

		if (next instanceof Node && mega.contains(next)) {
			return;
		}

		if (next instanceof Node && header && header.contains(next)) {
			scheduleClose(420);
			return;
		}

		scheduleClose();
	});
	mega.addEventListener('mouseenter', open);
	mega.addEventListener('mouseleave', (event) => {
		const next = event.relatedTarget;

		if (next instanceof Node && item.contains(next)) {
			return;
		}

		scheduleClose();
	});

	item.closest('.header-nav')?.addEventListener('mouseover', (event) => {
		const current = event.target.closest('.menu-item');

		if (current && current !== item) {
			close();
		}
	});

	link.addEventListener('focus', () => {
		if (closing) {
			closing = false;
			return;
		}

		if (link.matches(':focus-visible')) {
			open();
		}
	});

	cats.forEach((cat) => {
		const show = () => {
			open();
			activate(cat.dataset.megaTarget);
		};

		cat.addEventListener('mouseenter', show);
		cat.addEventListener('focus', show);
	});

	document.addEventListener('focusin', (event) => {
		if (!isOpen) {
			return;
		}

		if (item.contains(event.target) || mega.contains(event.target)) {
			return;
		}

		close();
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape' || !isOpen) {
			return;
		}

		closing = true;
		close();
		link.focus();
	});

	desktop.addEventListener('change', () => {
		if (!desktop.matches) {
			close();
		}
	});
})();

(() => {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	document.querySelectorAll('.faq__item').forEach((item) => {
		const summary = item.querySelector('summary');
		const panel = item.querySelector('.faq__panel');
		const arrow = item.querySelector('.faq__toggle');
		const faq = item.closest('.faq');

		if (!summary || !panel) {
			return;
		}

		if (faq) {
			faq.classList.add('faq--scripted');
		}

		if (arrow && item.open && !item.classList.contains('is-collapsed')) {
			arrow.classList.add('is-flipped');
		}

		let animation = null;
		let arrowTimer = 0;

		const setArrow = (flipped) => {
			if (!arrow) {
				return;
			}

			if (arrow.classList.contains('is-flipped') === flipped) {
				return;
			}

			window.clearTimeout(arrowTimer);
			arrow.classList.add('is-hidden');
			arrowTimer = window.setTimeout(() => {
				arrow.classList.toggle('is-flipped', flipped);
				window.requestAnimationFrame(() => {
					arrow.classList.remove('is-hidden');
				});
			}, 150);
		};

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
				setArrow(false);
				run(panel.offsetHeight, 0, false);
				return;
			}

			item.classList.remove('is-collapsed');
			const from = item.open ? panel.offsetHeight : 0;
			item.open = true;
			setArrow(true);
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
			if (width <= 1279) {
				return 'tablet';
			}
			return 'desktop';
		};

		const initButton = (button) => {
			if (button.dataset.lottieReady === '1') {
				return;
			}

			const variant = button.dataset.variant || 'primary';
			const container = button.querySelector('.lottie-button__anim');
			if (!container) {
				return;
			}

			button.dataset.lottieReady = '1';

			let animation = null;
			let currentName = '';

			const mount = () => {
				const currentBreakpoint = breakpoint();
				const mobileRows = button.dataset.mobileRows || '1';
				const name = button.dataset.anim
					|| (variant === 'primary' && currentBreakpoint === 'mobile'
						? `button-primary-mobile-${mobileRows}-rows`
						: `button-${variant}-${currentBreakpoint}`);
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
					rendererSettings: {
						preserveAspectRatio: 'none',
					},
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

		const initButtons = (root = document) => {
			root.querySelectorAll('.lottie-button').forEach(initButton);
		};

		window.zadzerkalyaInitButtons = initButtons;
		initButtons();
	};

	boot();
	window.addEventListener('load', boot);
})();

(() => {
	const media = window.matchMedia('(min-width: 768px)');

	document.querySelectorAll('[data-pinned-list]').forEach((shell) => {
		const section = shell.querySelector('[data-scroll-section]');
		const list = shell.querySelector('[data-scroll-list]');
		const track = list ? list.querySelector('[data-scroll-track]') : null;

		if (!section || !list || !track) {
			return;
		}

		let scrollDistance = 0;
		let stickyOffset = 0;
		let frame = 0;

		const sync = () => {
			frame = 0;

			if (!media.matches || !scrollDistance) {
				track.style.transform = '';
				return;
			}

			const start = shell.getBoundingClientRect().top + window.scrollY - stickyOffset;
			const progress = Math.min(Math.max(window.scrollY - start, 0), scrollDistance);
			track.style.transform = `translate3d(0, ${-progress}px, 0)`;
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
				track.style.transform = '';
				scrollDistance = 0;
				stickyOffset = 0;
				return;
			}

			shell.classList.add('is-scroll-driven');
			scrollDistance = Math.max(track.offsetHeight - list.clientHeight, 0);
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
			observer.observe(track);
		}

		measure();
	});
})();

(() => {
	const place = document.querySelector('.header-place');
	const targets = [
		{
			root: document.querySelector('.benefits'),
			anchor: document.querySelector('.benefits .benefits__item'),
		},
		{
			root: document.querySelector('.services-showcase'),
			anchor: document.querySelector('.services-showcase__heading'),
		},
		{
			root: document.querySelector('.help'),
			anchor: document.querySelector('.help__consultation'),
		},
	].filter((target) => target.root && target.anchor);

	if (!place || !targets.length) {
		return;
	}

	const align = () => {
		const hidden = getComputedStyle(place).display === 'none';
		const placeLeft = place.getBoundingClientRect().left;

		targets.forEach(({ root, anchor }) => {
			if (hidden) {
				root.classList.remove('is-copy-aligned');
				root.style.removeProperty('--copy-left');
				return;
			}

			const left = placeLeft - anchor.getBoundingClientRect().left;
			root.style.setProperty('--copy-left', `${left}px`);
			root.classList.add('is-copy-aligned');
		});
	};

	align();
	window.addEventListener('resize', align);
	window.addEventListener('load', align);

	if (document.fonts) {
		document.fonts.ready.then(align);
	}

	if ('ResizeObserver' in window) {
		const observer = new ResizeObserver(align);
		observer.observe(place);
		targets.forEach(({ anchor }) => observer.observe(anchor));
	}
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
	document.querySelectorAll('[data-service-cta]').forEach((section) => {
		const viewport = section.querySelector('.service-cta__viewport');
		const track = section.querySelector('.service-cta__track');
		const previous = section.querySelector('.service-cta__arrow--prev');
		const next = section.querySelector('.service-cta__arrow--next');
		const current = section.querySelector('.service-cta__counter-current');

		if (!viewport || !track || !previous || !next || !current) {
			return;
		}

		const slides = Array.from(track.querySelectorAll('.service-cta__slide'));

		const scrollStep = () => {
			const slide = slides[0];
			const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
			return slide ? slide.getBoundingClientRect().width + gap : viewport.clientWidth;
		};

		const updateState = () => {
			const step = scrollStep();
			const maxScroll = viewport.scrollWidth - viewport.clientWidth;
			const index = step ? Math.round(viewport.scrollLeft / step) : 0;
			const safeIndex = Math.min(slides.length - 1, Math.max(0, index));

			current.textContent = `${safeIndex + 1}/`;
			previous.disabled = viewport.scrollLeft <= 1;
			next.disabled = viewport.scrollLeft >= maxScroll - 1;

			slides.forEach((slide, slideIndex) => {
				slide.toggleAttribute('aria-hidden', slideIndex !== safeIndex);
			});
		};

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
		});

		viewport.addEventListener('scroll', updateState, { passive: true });
		window.addEventListener('resize', updateState);
		updateState();
	});
})();

(() => {
	document.querySelectorAll('[data-recommend]').forEach((section) => {
		const viewport = section.querySelector('.recommend__viewport');
		const track = section.querySelector('.recommend__track');
		const previous = section.querySelector('.recommend__arrow--prev');
		const next = section.querySelector('.recommend__arrow--next');

		if (!viewport || !track || !previous || !next) {
			return;
		}

		const scrollStep = () => {
			const card = track.querySelector('.article-card');
			const gap = Number.parseFloat(getComputedStyle(track).columnGap) || 0;
			return card ? card.getBoundingClientRect().width + gap : viewport.clientWidth;
		};

		const updateState = () => {
			const maxScroll = viewport.scrollWidth - viewport.clientWidth;
			previous.disabled = viewport.scrollLeft <= 1;
			next.disabled = viewport.scrollLeft >= maxScroll - 1;
		};

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
		});

		viewport.addEventListener('scroll', updateState, { passive: true });
		window.addEventListener('resize', updateState);
		window.addEventListener('load', updateState);
		updateState();
	});
})();

(() => {
	document.querySelectorAll('[data-service-slider]').forEach((section) => {
		const viewport = section.querySelector('.service-slider__viewport');
		const previous = section.querySelector('.service-slider__arrow--prev');
		const next = section.querySelector('.service-slider__arrow--next');

		if (!viewport || !previous || !next) {
			return;
		}

		const updateState = () => {
			const maxScroll = viewport.scrollWidth - viewport.clientWidth;
			previous.disabled = viewport.scrollLeft <= 1;
			next.disabled = maxScroll <= 1 || viewport.scrollLeft >= maxScroll - 1;
		};

		previous.addEventListener('click', () => {
			viewport.scrollBy({ left: -viewport.clientWidth, behavior: 'smooth' });
		});

		next.addEventListener('click', () => {
			viewport.scrollBy({ left: viewport.clientWidth, behavior: 'smooth' });
		});

		viewport.addEventListener('scroll', updateState, { passive: true });
		window.addEventListener('resize', updateState);
		updateState();
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

(() => {
	document.querySelectorAll('[data-copy-url]').forEach((button) => {
		let hideTimer = 0;

		button.addEventListener('click', async () => {
			const url = button.getAttribute('data-copy-url');
			if (!url) {
				return;
			}

			const wrap = button.closest('.event-article__share-copy');
			const toast = wrap?.querySelector('.event-article__share-toast');
			const srLabel = button.querySelector('.screen-reader-text');
			const original = srLabel ? srLabel.textContent : '';
			const copied = button.getAttribute('data-copied') || 'Посилання скопійовано';

			try {
				if (navigator.clipboard?.writeText) {
					await navigator.clipboard.writeText(url);
				} else {
					const input = document.createElement('input');
					input.value = url;
					document.body.appendChild(input);
					input.select();
					document.execCommand('copy');
					input.remove();
				}

				if (toast) {
					toast.textContent = copied;
				}

				if (srLabel) {
					srLabel.textContent = copied;
				}

				wrap?.classList.add('is-copied');
				button.classList.add('is-copied');

				window.clearTimeout(hideTimer);
				hideTimer = window.setTimeout(() => {
					wrap?.classList.remove('is-copied');
					button.classList.remove('is-copied');
					if (srLabel && original) {
						srLabel.textContent = original;
					}
				}, 2200);
			} catch (error) {
				window.prompt('', url);
			}
		});
	});
})();

(() => {
	const sections = document.querySelectorAll('.service-benefits');
	if (!sections.length) {
		return;
	}

	const desktop = window.matchMedia('(min-width: 1280px)');

	const align = (section) => {
		const aside = section.querySelector('.service-benefits__aside');
		const row = section.querySelector('.service-benefits__row:nth-child(2)');

		if (!aside) {
			return;
		}

		if (!desktop.matches || !row) {
			aside.style.marginTop = '';
			return;
		}

		const offset = row.getBoundingClientRect().top - section.getBoundingClientRect().top;
		aside.style.marginTop = `${Math.max(0, Math.round(offset))}px`;
	};

	const alignAll = () => {
		sections.forEach(align);
	};

	alignAll();
	window.addEventListener('load', alignAll);
	window.addEventListener('resize', alignAll);
	if (document.fonts) {
		document.fonts.ready.then(alignAll);
	}
})();
