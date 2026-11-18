const zadzerkalyaNavScrollLock = (() => {
	const SCROLL_TOP_KEY = 'zadzerkalya:scroll-top';
	let lockedY = 0;
	let locked = false;

	// Override html { scroll-behavior: smooth } for programmatic jumps.
	const scrollInstant = (top) => {
		const root = document.documentElement;
		const previous = root.style.scrollBehavior;
		root.style.scrollBehavior = 'auto';
		window.scrollTo(0, top);
		root.style.scrollBehavior = previous;
	};

	const lock = () => {
		if (locked) {
			return;
		}

		locked = true;
		lockedY = window.scrollY || document.documentElement.scrollTop || 0;
		document.documentElement.classList.add('nav-open');
		document.body.classList.add('nav-open');
		document.body.style.top = `-${lockedY}px`;
	};

	const unlock = ({ restore = true } = {}) => {
		if (!locked) {
			return;
		}

		locked = false;
		document.documentElement.classList.remove('nav-open');
		document.body.classList.remove('nav-open');
		document.body.style.removeProperty('top');

		if (!restore) {
			return;
		}

		// Restoring Y with smooth scroll right before navigation makes
		// Safari/iPad open the next page scrolled to the bottom.
		scrollInstant(lockedY);
	};

	const set = (open, options) => {
		if (open) {
			lock();
			return;
		}

		unlock(options);
	};

	const markNextPageTop = () => {
		try {
			sessionStorage.setItem(SCROLL_TOP_KEY, '1');
		} catch (error) {
			// ignore quota / private mode
		}
	};

	const honorNextPageTop = () => {
		try {
			if (sessionStorage.getItem(SCROLL_TOP_KEY) !== '1') {
				return;
			}

			sessionStorage.removeItem(SCROLL_TOP_KEY);
		} catch (error) {
			return;
		}

		if ('scrollRestoration' in window.history) {
			window.history.scrollRestoration = 'manual';
		}

		scrollInstant(0);
		window.addEventListener('load', () => scrollInstant(0), { once: true });
	};

	honorNextPageTop();

	return { set, lock, unlock, markNextPageTop };
})();

(() => {
	const toggle = document.querySelector('.nav-toggle');
	const nav = document.querySelector('.header-nav');
	const desktop = window.matchMedia('(min-width: 1280px)');
	const tablet = window.matchMedia('(min-width: 768px) and (max-width: 1279px)');

	if (!toggle || !nav) {
		return;
	}

	const setOpen = (open, options) => {
		nav.classList.toggle('is-open', open);
		toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		zadzerkalyaNavScrollLock.set(open, options);
	};

	toggle.addEventListener('click', () => {
		if (document.body.classList.contains('services-mega-open')) {
			document.dispatchEvent(new CustomEvent('zadzerkalya:close-services-mega'));
			setOpen(false);
			return;
		}

		setOpen(!nav.classList.contains('is-open'));
	});

	nav.addEventListener('click', (event) => {
		const link = event.target.closest('a');

		if (desktop.matches || !link) {
			return;
		}

		if (tablet.matches && event.target.closest('.menu-item--services')) {
			return;
		}

		const leaving = Boolean(link.href)
			&& !link.getAttribute('href')?.startsWith('#')
			&& link.target !== '_blank';

		if (leaving) {
			zadzerkalyaNavScrollLock.markNextPageTop();
			setOpen(false, { restore: false });
			return;
		}

		setOpen(false);
	});

	document.addEventListener('keydown', (event) => {
		if (event.key !== 'Escape') {
			return;
		}

		if (document.body.classList.contains('services-mega-open')) {
			return;
		}

		if (nav.classList.contains('is-open')) {
			setOpen(false);
			toggle.focus();
		}
	});

	const onDesktopChange = (event) => {
		if (event.matches) {
			setOpen(false);
		}
	};

	if (typeof desktop.addEventListener === 'function') {
		desktop.addEventListener('change', onDesktopChange);
	} else if (typeof desktop.addListener === 'function') {
		desktop.addListener(onDesktopChange);
	}
})();

(() => {
	const item = document.querySelector('.header-nav .menu-item--services');
	const mega = document.querySelector('#services-mega');
	const link = item ? item.querySelector('a') : null;
	const header = document.querySelector('.site-header');
	const nav = document.querySelector('.header-nav');
	const toggle = document.querySelector('.nav-toggle');
	const desktop = window.matchMedia('(hover: hover) and (pointer: fine) and (min-width: 1280px)');
	const tablet = window.matchMedia('(min-width: 768px) and (max-width: 1279px)');
	const mobile = window.matchMedia('(max-width: 767px)');

	if (!item || !mega || !link) {
		return;
	}

	const all = mega.querySelector('.services-mega__all');
	const cats = Array.from(mega.querySelectorAll('.services-mega__cat'));
	const panels = Array.from(mega.querySelectorAll('.services-mega__panel'));
	const photos = Array.from(mega.querySelectorAll('.services-mega__photo'));
	let isOpen = false;

	const canOpen = () => desktop.matches || tablet.matches || mobile.matches;

	const catRows = Array.from(mega.querySelectorAll('.services-mega__cat-row'));
	const catToggles = Array.from(mega.querySelectorAll('.services-mega__cat-toggle'));

	const activate = (target) => {
		if (all) {
			all.classList.toggle('is-active', all.dataset.megaTarget === target);
		}
		cats.forEach((cat) => {
			cat.classList.toggle('is-active', cat.dataset.megaTarget === target);
		});
		catRows.forEach((row) => {
			const rowTarget = row.querySelector('[data-mega-target]')?.dataset.megaTarget;
			row.classList.toggle('is-active', rowTarget === target);
		});
		catToggles.forEach((btn) => {
			btn.setAttribute('aria-expanded', btn.dataset.megaTarget === target ? 'true' : 'false');
		});
		panels.forEach((panel) => {
			panel.classList.toggle('is-active', panel.id === target);
		});
		const allTarget = all ? all.dataset.megaTarget : '';
		const firstTarget = cats[0] ? cats[0].dataset.megaTarget : '';
		photos.forEach((photo) => {
			const show = photo.dataset.megaTarget === target
				|| (target === allTarget && photo.dataset.megaTarget === firstTarget);
			photo.classList.toggle('is-active', show);
		});
	};

	const syncOffset = () => {
		if (!header || tablet.matches || mobile.matches) {
			mega.style.paddingTop = '';
			return;
		}

		const logo = header.querySelector('.header-logo');
		const headerBottom = header.getBoundingClientRect().bottom;
		const logoBottom = logo ? logo.getBoundingClientRect().bottom : headerBottom;

		mega.style.paddingTop = `${Math.ceil(Math.max(headerBottom, logoBottom))}px`;
	};

	const open = () => {
		if (!canOpen() || isOpen) {
			return;
		}

		syncOffset();
		isOpen = true;
		mega.classList.add('is-open');
		mega.setAttribute('aria-hidden', 'false');
		item.classList.add('is-open');
		link.setAttribute('aria-expanded', 'true');
		document.body.classList.add('services-mega-open');

		if (!desktop.matches) {
			nav?.classList.remove('is-open');
			toggle?.setAttribute('aria-expanded', 'true');
			zadzerkalyaNavScrollLock.lock();
		}
	};

	const close = ({ restoreScroll = true, returnToNav = false } = {}) => {
		if (!isOpen) {
			return;
		}

		isOpen = false;
		mega.classList.remove('is-open');
		mega.setAttribute('aria-hidden', 'true');
		item.classList.remove('is-open');
		link.setAttribute('aria-expanded', 'false');
		document.body.classList.remove('services-mega-open');

		if (!desktop.matches) {
			if (returnToNav) {
				nav?.classList.add('is-open');
				toggle?.setAttribute('aria-expanded', 'true');
				zadzerkalyaNavScrollLock.lock();
			} else {
				toggle?.setAttribute('aria-expanded', 'false');
				zadzerkalyaNavScrollLock.unlock({ restore: restoreScroll });
			}
		}

		if (all) {
			activate(all.dataset.megaTarget);
		}
	};

	link.addEventListener('click', (event) => {
		if (!canOpen()) {
			return;
		}

		event.preventDefault();

		if (mobile.matches) {
			event.stopPropagation();
		}

		if (desktop.matches && isOpen) {
			close();
			return;
		}

		open();
	});

	mega.querySelector('.services-mega__close')?.addEventListener('click', () => {
		close();
		link.focus();
	});

	mega.querySelector('.services-mega__back-btn')?.addEventListener('click', () => {
		close({ returnToNav: true });
		link.focus();
	});

	document.addEventListener('zadzerkalya:close-services-mega', () => {
		close();
	});

	document.addEventListener('click', (event) => {
		if (!isOpen || !desktop.matches) {
			return;
		}

		if (item.contains(event.target)) {
			return;
		}

		if (mega.contains(event.target) && event.target.closest('a')) {
			return;
		}

		close();
	});

	const closeWithNav = () => {
		zadzerkalyaNavScrollLock.markNextPageTop();
		close({ restoreScroll: false });
		toggle?.setAttribute('aria-expanded', 'false');
	};

	cats.forEach((cat) => {
		const show = () => {
			if (!desktop.matches) {
				return;
			}

			open();
			activate(cat.dataset.megaTarget);
		};

		cat.addEventListener('mouseenter', show);
		cat.addEventListener('focus', show);
		cat.addEventListener('click', () => {
			if (!tablet.matches && !mobile.matches) {
				return;
			}

			closeWithNav();
		});
	});

	if (all) {
		const showAll = () => {
			if (!desktop.matches) {
				return;
			}

			open();
			activate(all.dataset.megaTarget);
		};

		all.addEventListener('mouseenter', showAll);
		all.addEventListener('focus', showAll);
		all.addEventListener('click', () => {
			if (!tablet.matches && !mobile.matches) {
				return;
			}

			closeWithNav();
		});
	}

	catToggles.forEach((btn) => {
		btn.addEventListener('click', () => {
			if (!tablet.matches) {
				return;
			}

			activate(btn.dataset.megaTarget);
		});
	});

	mega.querySelectorAll('.services-mega__service, .services-mega__mobile a').forEach((service) => {
		service.addEventListener('click', () => {
			if (!tablet.matches && !mobile.matches) {
				return;
			}

			closeWithNav();
		});
	});

	document.addEventListener('focusin', (event) => {
		if (!isOpen || !desktop.matches) {
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

		close();

		if (tablet.matches || mobile.matches) {
			toggle?.setAttribute('aria-expanded', 'false');
			zadzerkalyaNavScrollLock.unlock();
			toggle?.focus();
			return;
		}

		link.focus();
	});

	const onViewportChange = () => {
		if (!canOpen()) {
			close();
			return;
		}

		if (isOpen) {
			syncOffset();
		}
	};

	window.addEventListener('resize', () => {
		if (isOpen) {
			syncOffset();
		}
	});

	const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

	mega.querySelectorAll('.services-mega__acc').forEach((item) => {
		const button = item.querySelector('.services-mega__acc-toggle');
		const panel = item.querySelector('.services-mega__acc-panel');
		const buttonLabel = button?.querySelector('.screen-reader-text');
		let animation = null;
		let open = item.classList.contains('is-open');

		if (!button || !panel) {
			return;
		}

		const clearPanelStyles = () => {
			panel.style.removeProperty('height');
			panel.style.removeProperty('opacity');
			panel.style.removeProperty('overflow');
		};

		const syncToggleLabel = (next) => {
			button.setAttribute('aria-expanded', next ? 'true' : 'false');

			if (buttonLabel) {
				buttonLabel.textContent = next ? 'Згорнути список' : 'Розгорнути список';
			}
		};

		const setOpen = (next) => {
			if (animation) {
				animation.cancel();
				animation = null;
			}

			if (next === open) {
				return;
			}

			if (reduceMotion.matches) {
				open = next;
				item.classList.toggle('is-open', next);
				syncToggleLabel(next);
				clearPanelStyles();
				return;
			}

			const from = panel.getBoundingClientRect().height;

			if (next) {
				item.classList.add('is-open');
				syncToggleLabel(true);
				panel.style.height = 'auto';
				panel.style.opacity = '1';
				panel.style.overflow = 'hidden';
			} else {
				panel.style.height = `${from}px`;
				panel.style.opacity = '1';
				panel.style.overflow = 'hidden';
				item.classList.remove('is-open');
				syncToggleLabel(false);
			}

			const to = next ? Math.min(panel.scrollHeight, 382) : 0;

			panel.style.height = `${from}px`;
			void panel.offsetHeight;

			animation = panel.animate(
				{
					height: [`${from}px`, `${to}px`],
					opacity: [next && from < 1 ? 0 : 1, next ? 1 : 0],
				},
				{ duration: 300, easing: 'ease' }
			);

			open = next;

			animation.onfinish = () => {
				animation = null;
				clearPanelStyles();
			};

			animation.oncancel = () => {
				animation = null;
			};
		};

		button.addEventListener('click', () => {
			const willOpen = !open;

			mega.querySelectorAll('.services-mega__acc').forEach((other) => {
				if (other === item || !other.classList.contains('is-open')) {
					return;
				}

				other.dispatchEvent(new CustomEvent('services-mega:close'));
			});

			setOpen(willOpen);
		});

		item.addEventListener('services-mega:close', () => {
			setOpen(false);
		});
	});

	desktop.addEventListener('change', onViewportChange);
	tablet.addEventListener('change', onViewportChange);
	mobile.addEventListener('change', onViewportChange);
})();

(() => {
	const bindExclusiveFaq = () => {
		document.querySelectorAll('.faq__content').forEach((content) => {
			content.querySelectorAll('.faq__item').forEach((item) => {
				item.addEventListener('toggle', () => {
					if (!item.open) {
						return;
					}

					content.querySelectorAll('.faq__item').forEach((other) => {
						if (other !== item) {
							other.open = false;
						}
					});
				});
			});
		});
	};

	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		bindExclusiveFaq();
		return;
	}

	const controllers = new WeakMap();

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
				document.dispatchEvent(new CustomEvent('faq:frame'));
			};

			const notify = () => {
				if (!animation) {
					return;
				}

				document.dispatchEvent(new CustomEvent('faq:frame'));
				window.requestAnimationFrame(notify);
			};

			window.requestAnimationFrame(notify);
		};

		const close = () => {
			if (!item.open || item.classList.contains('is-collapsed')) {
				return;
			}

			item.classList.add('is-collapsed');
			setArrow(false);
			run(panel.offsetHeight, 0, false);
		};

		controllers.set(item, { close });

		summary.addEventListener('click', (event) => {
			event.preventDefault();

			if (item.open && !item.classList.contains('is-collapsed')) {
				close();
				return;
			}

			const content = item.closest('.faq__content');

			if (content) {
				content.querySelectorAll('.faq__item').forEach((other) => {
					if (other !== item) {
						controllers.get(other)?.close();
					}
				});
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
					|| (currentBreakpoint === 'desktop' && button.dataset.animDesktop)
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

		const isFaq = shell.classList.contains('faq-scroll');
		let scrollDistance = 0;
		let desiredDistance = 0;
		let stickyOffset = 0;
		let frame = 0;
		let reveal = 0;
		let lastLinked = 0;
		let lastScrollY = 0;
		let hasLinked = false;

		const paint = () => {
			if (!media.matches || !scrollDistance) {
				track.style.transform = '';
				reveal = 0;
				hasLinked = false;
				return;
			}

			const shellTop = shell.getBoundingClientRect().top;
			const start = shellTop + window.scrollY - stickyOffset;
			const linked = Math.min(Math.max(window.scrollY - start, 0), scrollDistance);

			// Drop stale reveal before the section pins — off-screen
			// measure() used to inflate it against the viewport.
			if (linked <= 0 && shellTop > stickyOffset + 0.5) {
				reveal = 0;
			}

			if (!hasLinked) {
				lastLinked = linked;
				lastScrollY = window.scrollY;
				hasLinked = true;
			} else {
				const linkedDelta = linked - lastLinked;
				const scrollDelta = window.scrollY - lastScrollY;
				const unsolicited = linkedDelta - Math.max(scrollDelta, 0);
				if (reveal > 0 && unsolicited > 0.5) {
					reveal = Math.max(0, reveal - unsolicited);
				}
				lastLinked = linked;
				lastScrollY = window.scrollY;
			}

			const maxProgress = Math.max(track.offsetHeight - list.clientHeight, 0);
			reveal = Math.min(reveal, Math.max(0, maxProgress - linked));
			track.style.transform = `translate3d(0, ${-(linked + reveal)}px, 0)`;
		};

		const sync = () => {
			frame = 0;
			paint();
		};

		// The answer keeps growing downward. If it would leave the visible
		// list, shift only the list — not the image — just enough to show it.
		const revealClipped = () => {
			if (!isFaq || !media.matches || !scrollDistance) {
				return;
			}

			const listRect = list.getBoundingClientRect();

			// Answers below the fold are not "clipped" — only shift the
			// track once the list actually intersects the viewport.
			if (listRect.bottom <= 0 || listRect.top >= window.innerHeight) {
				return;
			}

			const limit = Math.min(listRect.bottom, window.innerHeight - 16);
			let overflow = 0;

			track.querySelectorAll('.faq__item[open]:not(.is-collapsed)').forEach((item) => {
				overflow = Math.max(overflow, item.getBoundingClientRect().bottom - limit);
			});

			if (overflow <= 1) {
				return;
			}

			const before = reveal;
			reveal += overflow;
			paint();

			if (Math.abs(reveal - before) < 0.5) {
				reveal = before;
			}
		};

		const requestSync = () => {
			if (!frame) {
				frame = window.requestAnimationFrame(sync);
			}
		};

		// Growing the pin after the block has started to leave pulls the
		// section back down and shifts the list up. Keep the current length
		// until the section is pinned again, so an answer opens downward.
		const commitDistance = (next, force = false) => {
			if (!force && next === scrollDistance) {
				return;
			}

			const topBefore = section.getBoundingClientRect().top;
			const released = topBefore < stickyOffset - 0.5;

			if (!force && released) {
				return;
			}

			const previous = scrollDistance;
			const height = section.offsetHeight;
			scrollDistance = next;
			shell.style.setProperty('--pinned-list-height', `${height + next}px`);

			if (!force && Math.abs(section.getBoundingClientRect().top - topBefore) > 0.5) {
				scrollDistance = previous;
				shell.style.setProperty('--pinned-list-height', `${height + previous}px`);
			}
		};

		const measure = (force = false) => {
			if (!media.matches) {
				shell.classList.remove('is-scroll-driven');
				shell.style.removeProperty('--pinned-list-height');
				track.style.transform = '';
				scrollDistance = 0;
				desiredDistance = 0;
				stickyOffset = 0;
				reveal = 0;
				hasLinked = false;
				return;
			}

			shell.classList.add('is-scroll-driven');
			stickyOffset = Number.parseFloat(getComputedStyle(section).top) || 0;
			desiredDistance = Math.max(track.offsetHeight - list.clientHeight, 0);
			commitDistance(desiredDistance, force);
			revealClipped();
			requestSync();
		};

		window.addEventListener('scroll', () => {
			if (desiredDistance !== scrollDistance) {
				commitDistance(desiredDistance);
			}
			requestSync();
		}, { passive: true });

		if (isFaq) {
			document.addEventListener('faq:frame', revealClipped);
		}
		window.addEventListener('resize', () => measure(true));
		window.addEventListener('load', () => measure(true));
		media.addEventListener('change', () => measure(true));

		if ('ResizeObserver' in window) {
			const observer = new ResizeObserver(() => measure(false));
			observer.observe(section);
			observer.observe(list);
			observer.observe(track);
		}

		measure(true);
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
				root.style.removeProperty('--benefits-list-left');
				return;
			}

			const left = placeLeft - anchor.getBoundingClientRect().left;
			root.style.setProperty('--copy-left', `${left}px`);
			root.classList.add('is-copy-aligned');

			const list = root.querySelector('.benefits__list');

			if (!list) {
				return;
			}

			const pad = Number.parseFloat(getComputedStyle(root).paddingLeft) || 0;
			const listLeft = list.getBoundingClientRect().left - root.getBoundingClientRect().left - pad;
			root.style.setProperty('--benefits-list-left', `${listLeft}px`);
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
			if (!button || button.tagName === 'A') {
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
		const total = section.querySelector('.service-cta__counter-total');

		if (!viewport || !track || !previous || !next || !current) {
			return;
		}

		const items = Array.from(track.querySelectorAll('.service-cta__row'));
		if (!items.length) {
			return;
		}

		let page = 0;
		let frame = 0;
		let packedSize = '';

		const createSlide = () => {
			const slide = document.createElement('div');
			slide.className = 'service-cta__slide';
			return slide;
		};

		const slides = () => Array.from(track.children);

		const sync = () => {
			const pages = slides();
			const maxPage = Math.max(0, pages.length - 1);
			page = Math.min(maxPage, Math.max(0, page));

			if (total) {
				total.textContent = String(pages.length);
			}

			current.textContent = `${page + 1}/`;
			previous.disabled = page <= 0;
			next.disabled = page >= maxPage;
			pages.forEach((slide, index) => {
				slide.toggleAttribute('aria-hidden', index !== page);
			});
		};

		const slideOffset = (slide) => {
			const first = slides()[0];
			if (!slide || !first) {
				return 0;
			}

			return slide.offsetLeft - first.offsetLeft;
		};

		const jump = () => {
			const target = slides()[page];
			if (!target) {
				return;
			}

			const behavior = viewport.style.scrollBehavior;
			viewport.style.scrollBehavior = 'auto';
			viewport.scrollLeft = slideOffset(target);
			viewport.style.scrollBehavior = behavior;
		};

		const pack = () => {
			const available = viewport.clientHeight;
			track.replaceChildren();

			let slide = createSlide();
			track.append(slide);

			items.forEach((item) => {
				slide.append(item);
				const overflows = available >= 40 && slide.scrollHeight > available + 1;
				if (overflows && slide.childElementCount > 1) {
					slide.removeChild(item);
					slide = createSlide();
					track.append(slide);
					slide.append(item);
				}
			});

			slides().forEach((node) => {
				const alone = node.childElementCount === 1;
				const tooTall = available >= 40 && node.scrollHeight > available + 1;
				node.classList.toggle('is-overflowing', alone && tooTall);
			});

			section.classList.add('is-paged');
			sync();
			jump();
		};

		const go = (nextPage) => {
			const pages = slides();
			page = Math.min(pages.length - 1, Math.max(0, nextPage));
			sync();
			const target = pages[page];
			if (target) {
				viewport.scrollTo({ left: slideOffset(target), behavior: 'smooth' });
			}
		};

		previous.addEventListener('click', () => {
			go(page - 1);
		});

		next.addEventListener('click', () => {
			go(page + 1);
		});

		viewport.addEventListener(
			'scroll',
			() => {
				const pages = slides();
				if (!pages.length) {
					return;
				}

				let index = 0;
				let nearest = Number.POSITIVE_INFINITY;
				pages.forEach((slide, slideIndex) => {
					const distance = Math.abs(viewport.scrollLeft - slideOffset(slide));
					if (distance < nearest) {
						nearest = distance;
						index = slideIndex;
					}
				});

				if (index === page) {
					return;
				}

				page = index;
				sync();
			},
			{ passive: true }
		);

		const schedule = () => {
			cancelAnimationFrame(frame);
			frame = requestAnimationFrame(() => {
				const size = `${viewport.clientWidth}x${viewport.clientHeight}`;
				if (size === packedSize) {
					return;
				}

				packedSize = size;
				pack();
				packedSize = `${viewport.clientWidth}x${viewport.clientHeight}`;
			});
		};

		if (typeof ResizeObserver === 'function') {
			const observer = new ResizeObserver(schedule);
			observer.observe(viewport);
		} else {
			window.addEventListener('resize', schedule);
		}

		if (document.fonts && document.fonts.ready) {
			document.fonts.ready.then(schedule);
		}

		schedule();
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
			const canScroll = maxScroll > 8;
			previous.disabled = !canScroll || viewport.scrollLeft <= 8;
			next.disabled = !canScroll || maxScroll - viewport.scrollLeft <= 8;
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
	const initServiceSliders = () => {
		document.querySelectorAll('[data-service-slider]').forEach((section) => {
			const viewport = section.querySelector('.service-slider__viewport');
			const mover = section.querySelector('.service-slider__mover');
			const track = section.querySelector('.service-slider__track');
			const previous = section.querySelector('.service-slider__arrow--prev');
			const next = section.querySelector('.service-slider__arrow--next');

			if (!viewport || !mover || !track || !previous || !next) {
				return;
			}

			const originals = () => [...track.querySelectorAll('.service-slider__slide:not([data-clone])')];
			const canLoop = () => originals().length > 1;

			const buildLoop = () => {
				if (!canLoop() || track.querySelector('[data-clone]')) {
					return;
				}

				originals().forEach((slide) => {
					const clone = slide.cloneNode(true);
					clone.dataset.clone = 'after';
					clone.setAttribute('aria-hidden', 'true');
					clone.querySelectorAll('a, button, input, textarea, select').forEach((node) => {
						node.setAttribute('tabindex', '-1');
					});
					track.append(clone);
				});
			};

			const stepSize = () => {
				const slide = originals()[0];
				if (!slide) {
					return 0;
				}

				const margin = Number.parseFloat(getComputedStyle(slide).marginRight) || 0;
				return slide.getBoundingClientRect().width + margin;
			};

			const loopWidth = () => {
				const count = originals().length;
				const step = stepSize();
				return count > 1 && step > 1 ? count * step : 0;
			};

			let position = 0;
			let destination = 0;
			let origin = 0;
			let frame = 0;
			let animating = false;
			const motion = window.matchMedia('(prefers-reduced-motion: reduce)');

			const apply = () => {
				const ratio = window.devicePixelRatio || 1;
				const x = Math.round(position * ratio) / ratio;
				mover.style.transform = `translate3d(${x}px, 0, 0)`;
			};

			const contain = (value) => {
				const half = loopWidth();
				if (half < 2) {
					return value;
				}

				let next = value;
				while (next <= -half) {
					next += half;
					origin += half;
					destination += half;
				}
				while (next > 0) {
					next -= half;
					origin -= half;
					destination -= half;
				}
				return next;
			};

			const setDisabled = (button, disabled) => {
				button.classList.toggle('is-disabled', disabled);
				button.setAttribute('aria-disabled', disabled ? 'true' : 'false');
			};

			const updateState = () => {
				if (canLoop()) {
					setDisabled(previous, false);
					setDisabled(next, false);
					return;
				}

				setDisabled(previous, true);
				setDisabled(next, true);
			};

			let layingOut = false;

			const relayout = () => {
				if (layingOut) {
					return;
				}

				layingOut = true;
				if (canLoop()) {
					buildLoop();
				}

				if (!animating) {
					const half = loopWidth();
					const step = stepSize();
					if (half > 1 && step > 1) {
						const steps = Math.round(-position / step);
						position = -steps * step;
						while (position <= -half) {
							position += half;
						}
						while (position > 0) {
							position -= half;
						}
						destination = position;
						origin = position;
						apply();
					}
				}

				updateState();
				layingOut = false;
			};

			const animateTo = () => {
				origin = position;
				cancelAnimationFrame(frame);

				const finish = () => {
					position = contain(destination);
					destination = position;
					origin = position;
					animating = false;
					apply();
					updateState();
				};

				if (Math.abs(destination - position) < 1 || motion.matches) {
					finish();
					return;
				}

				const duration = 520;
				const started = performance.now();
				animating = true;

				const step = (now) => {
					const progress = Math.min(1, (now - started) / duration);
					const eased = 1 - (1 - progress) ** 3;

					if (progress < 1) {
						position = contain(origin + (destination - origin) * eased);
						apply();
						frame = requestAnimationFrame(step);
						return;
					}

					finish();
				};

				frame = requestAnimationFrame(step);
			};

			const move = (direction) => {
				const step = stepSize();
				if (step < 1 || loopWidth() < 2) {
					return;
				}

				cancelAnimationFrame(frame);
				destination = (animating ? destination : position) - direction * step;
				animateTo();
			};

			const onControlClick = (event) => {
				const arrow = event.target.closest('.service-slider__arrow');
				if (!arrow || arrow.getAttribute('aria-disabled') === 'true') {
					return;
				}

				event.preventDefault();
				const direction = arrow.classList.contains('service-slider__arrow--next') ? 1 : -1;
				move(direction);
			};

			let pointerId = null;
			let dragStartX = 0;
			let dragOrigin = 0;
			let dragDx = 0;
			let dragging = false;
			let suppressClick = false;
			let lastX = 0;
			let lastT = 0;
			let velocity = 0;

			const finishDrag = (vx) => {
				const step = stepSize();
				if (step < 1 || loopWidth() < 2) {
					destination = position;
					origin = position;
					apply();
					return;
				}

				const progress = -position / step;
				let slides = Math.round(progress);

				if (Math.abs(vx) > 0.45) {
					slides = vx < 0 ? Math.ceil(progress + 0.01) : Math.floor(progress - 0.01);
				} else if (Math.abs(dragDx) > step * 0.22) {
					slides = dragDx < 0 ? Math.ceil(progress + 0.01) : Math.floor(progress - 0.01);
				}

				destination = -slides * step;
				animateTo();
			};

			const onPointerDown = (event) => {
				if (pointerId !== null || !canLoop()) {
					return;
				}

				if (event.pointerType === 'mouse' && event.button !== 0) {
					return;
				}

				if (event.target.closest('.service-slider__arrow')) {
					return;
				}

				pointerId = event.pointerId;
				dragStartX = event.clientX;
				dragOrigin = position;
				dragDx = 0;
				dragging = false;
				suppressClick = false;
				lastX = event.clientX;
				lastT = performance.now();
				velocity = 0;

				cancelAnimationFrame(frame);
				animating = false;
				destination = position;
				origin = position;

				viewport.setPointerCapture?.(event.pointerId);
			};

			const onPointerMove = (event) => {
				if (event.pointerId !== pointerId) {
					return;
				}

				dragDx = event.clientX - dragStartX;

				if (!dragging) {
					if (Math.abs(dragDx) < 8) {
						return;
					}

					dragging = true;
					suppressClick = true;
					viewport.classList.add('is-dragging');
				}

				const now = performance.now();
				const dt = now - lastT;
				if (dt > 0) {
					velocity = (event.clientX - lastX) / dt;
				}
				lastX = event.clientX;
				lastT = now;

				position = contain(dragOrigin + dragDx);
				destination = position;
				origin = position;
				apply();
			};

			const onPointerUp = (event) => {
				if (event.pointerId !== pointerId) {
					return;
				}

				const wasDragging = dragging;
				const vx = velocity;

				pointerId = null;
				dragging = false;
				viewport.classList.remove('is-dragging');

				if (viewport.hasPointerCapture?.(event.pointerId)) {
					viewport.releasePointerCapture(event.pointerId);
				}

				if (!wasDragging) {
					return;
				}

				finishDrag(vx);
			};

			const onClickCapture = (event) => {
				if (!suppressClick) {
					return;
				}

				suppressClick = false;
				event.preventDefault();
				event.stopPropagation();
			};

			previous.addEventListener('click', onControlClick);
			next.addEventListener('click', onControlClick);
			viewport.addEventListener('pointerdown', onPointerDown);
			viewport.addEventListener('pointermove', onPointerMove);
			viewport.addEventListener('pointerup', onPointerUp);
			viewport.addEventListener('pointercancel', onPointerUp);
			viewport.addEventListener('click', onClickCapture, true);
			window.addEventListener('resize', relayout);

			if (window.ResizeObserver) {
				const observer = new ResizeObserver(relayout);
				observer.observe(viewport);
			}

			relayout();
			window.addEventListener('load', relayout);
		});
	};

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initServiceSliders);
	} else {
		initServiceSliders();
	}
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
	const sections = document.querySelectorAll('.service-specialists');
	if (!sections.length) {
		return;
	}

	const mobile = window.matchMedia('(max-width: 767px)');

	const equalizeCards = (section) => {
		const cards = [...section.querySelectorAll('.service-specialists__card')];

		cards.forEach((card) => {
			card.style.minHeight = '';
		});

		if (!mobile.matches || cards.length < 2) {
			return;
		}

		const maxHeight = Math.max(...cards.map((card) => card.offsetHeight));
		cards.forEach((card) => {
			card.style.minHeight = `${maxHeight}px`;
		});
	};

	const equalizeAll = () => {
		sections.forEach(equalizeCards);
	};

	equalizeAll();
	window.addEventListener('load', equalizeAll);
	window.addEventListener('resize', equalizeAll);
	if (document.fonts) {
		document.fonts.ready.then(equalizeAll);
	}
})();

(() => {
	const sections = document.querySelectorAll('.service-benefits');
	if (!sections.length) {
		return;
	}

	const desktop = window.matchMedia('(min-width: 1280px)');
	const mobile = window.matchMedia('(max-width: 767px)');

	const equalizeCards = (section) => {
		const cards = [...section.querySelectorAll('.service-benefits__card')];

		cards.forEach((card) => {
			card.style.minHeight = '';
		});

		if (!mobile.matches || cards.length < 2) {
			return;
		}

		const maxHeight = Math.max(...cards.map((card) => card.offsetHeight));
		cards.forEach((card) => {
			card.style.minHeight = `${maxHeight}px`;
		});
	};

	const align = (section) => {
		const aside = section.querySelector('.service-benefits__aside');
		const foot = section.querySelector('.service-benefits__aside-foot');
		const button = aside ? aside.querySelector('.lottie-button') : null;
		const rows = [...section.querySelectorAll('.service-benefits__rows > .service-benefits__row')];
		const cards = section.querySelectorAll('.service-benefits__card');
		const secondRow = rows[1] || rows[0];

		equalizeCards(section);

		if (!aside || !foot || !button || !secondRow) {
			return;
		}

		if (!desktop.matches) {
			aside.style.marginTop = '';
			foot.style.marginTop = '';
			section.style.paddingBottom = '';
			return;
		}

		aside.style.marginTop = '0px';
		foot.style.marginTop = '0px';
		section.style.paddingBottom = '';

		// >3 cards: pin aside top to the second card row.
		// ≤3 cards: pin button bottom to that row's bottom edge.
		if (cards.length > 3) {
			const delta = secondRow.getBoundingClientRect().top - aside.getBoundingClientRect().top;
			aside.style.marginTop = `${Math.max(0, delta)}px`;
			return;
		}

		const delta = secondRow.getBoundingClientRect().bottom - button.getBoundingClientRect().bottom;
		foot.style.marginTop = `${delta}px`;
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

(() => {
	const toc = document.querySelector('.policy__toc');
	if (!toc) {
		return;
	}

	const items = [...toc.querySelectorAll('a[href^="#"]')]
		.map((link) => {
			const id = decodeURIComponent(link.getAttribute('href').slice(1));
			return { link, heading: id ? document.getElementById(id) : null };
		})
		.filter((item) => item.heading);

	if (!items.length || !('IntersectionObserver' in window)) {
		return;
	}

	const setActive = (id) => {
		items.forEach(({ link, heading }) => {
			const active = heading.id === id;
			link.classList.toggle('is-active', active);
			if (active) {
				link.setAttribute('aria-current', 'true');
				const tocRect = toc.getBoundingClientRect();
				const linkRect = link.getBoundingClientRect();
				const linkTop = linkRect.top - tocRect.top + toc.scrollTop;
				const linkBottom = linkTop + linkRect.height;
				if (linkTop < toc.scrollTop) {
					toc.scrollTop = linkTop;
				} else if (linkBottom > toc.scrollTop + toc.clientHeight) {
					toc.scrollTop = linkBottom - toc.clientHeight;
				}
			} else {
				link.removeAttribute('aria-current');
			}
		});
	};

	const visible = new Map();
	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					visible.set(entry.target.id, entry.boundingClientRect.top);
				} else {
					visible.delete(entry.target.id);
				}
			});

			if (!visible.size) {
				return;
			}

			const [id] = [...visible.entries()].sort((a, b) => a[1] - b[1])[0];
			setActive(id);
		},
		{
			rootMargin: '-15% 0px -70% 0px',
			threshold: 0,
		}
	);

	items.forEach(({ heading }) => observer.observe(heading));
})();

(() => {
	if (!document.querySelector('[data-service-field]')) {
		return;
	}

	const targetOf = (trigger) => {
		if (trigger.hasAttribute('data-contact-popup-open')) {
			return document.querySelector('[data-contact-popup]');
		}

		if (!trigger.hash || trigger.pathname !== window.location.pathname) {
			return null;
		}

		try {
			return document.querySelector(trigger.hash);
		} catch {
			return null;
		}
	};

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('a[href*="#"], [data-contact-popup-open]');
		const target = trigger ? targetOf(trigger) : null;
		if (!target) {
			return;
		}

		const fields = target.matches('[data-service-field]') ? [target] : target.querySelectorAll('[data-service-field]');
		fields.forEach((field) => {
			field.value = trigger.dataset.service ?? field.defaultValue;
		});
	});
})();

(() => {
	const popup = document.querySelector('[data-contact-popup]');
	if (!popup) {
		return;
	}

	const dialog = popup.querySelector('.contact-popup__dialog');
	const submit = popup.querySelector('.lottie-button--primary');
	const labelEl = submit?.querySelector('.lottie-button__label');
	const sourceField = popup.querySelector('[name="zadzerkalya_form_source"]');
	const eventField = popup.querySelector('[name="zadzerkalya_event_title"]');
	let lastFocus = null;

	const focusable = () => [...popup.querySelectorAll('button, [href], input, textarea, select, [tabindex]:not([tabindex="-1"])')]
		.filter((node) => !node.closest('.hp-field') && !node.hasAttribute('disabled') && node.getClientRects().length);

	const formatLabel = (value) => String(value)
		.split(/\r\n|\n|\r/)
		.map((line) => line.trim())
		.filter(Boolean)
		.map((line) => line.replace(/</g, '&lt;').replace(/>/g, '&gt;'))
		.join('<br>');

	const applyTrigger = (trigger) => {
		if (!trigger) {
			return;
		}

		if (labelEl && trigger.dataset.contactPopupLabel) {
			labelEl.innerHTML = formatLabel(trigger.dataset.contactPopupLabel);
		}

		if (sourceField && trigger.dataset.contactPopupSource) {
			sourceField.value = trigger.dataset.contactPopupSource;
		}

		if (eventField && Object.prototype.hasOwnProperty.call(trigger.dataset, 'contactPopupEvent')) {
			eventField.value = trigger.dataset.contactPopupEvent || '';
		}
	};

	const open = (trigger) => {
		applyTrigger(trigger);
		lastFocus = document.activeElement;
		popup.hidden = false;
		document.body.classList.add('contact-popup-open');
		popup.querySelector('.contact-popup__close')?.focus();
	};

	const close = () => {
		if (popup.hidden) {
			return;
		}

		popup.hidden = true;
		document.body.classList.remove('contact-popup-open');
		if (lastFocus && typeof lastFocus.focus === 'function') {
			lastFocus.focus();
		}
	};

	document.querySelectorAll('[data-contact-popup-open]').forEach((button) => {
		button.addEventListener('click', (event) => {
			event.preventDefault();
			open(button);
		});
	});

	popup.querySelectorAll('[data-contact-popup-close]').forEach((node) => {
		node.addEventListener('click', close);
	});

	document.addEventListener('keydown', (event) => {
		if (popup.hidden) {
			return;
		}

		if (event.key === 'Escape') {
			event.preventDefault();
			close();
			return;
		}

		if (event.key !== 'Tab' || !dialog) {
			return;
		}

		const items = focusable();
		if (!items.length) {
			return;
		}

		const first = items[0];
		const last = items[items.length - 1];
		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	});

	if (popup.hasAttribute('data-open')) {
		open();
	}
})();

(() => {
	const prefix = '+38 ';

	const nationalDigits = (value) => {
		let digits = String(value).replace(/\D/g, '');
		if (digits.startsWith('38')) {
			digits = digits.slice(2);
		}
		return digits.slice(0, 10);
	};

	const formatPhone = (digits) => {
		if (!digits) {
			return prefix;
		}

		let result = `+38 (${digits.slice(0, 3)}`;
		if (digits.length < 3) {
			return result;
		}

		result += ')';
		if (digits.length === 3) {
			return `${result} `;
		}

		result += ` ${digits.slice(3, 6)}`;
		if (digits.length <= 6) {
			return result;
		}

		result += `-${digits.slice(6, 8)}`;
		if (digits.length <= 8) {
			return result;
		}

		return `${result}-${digits.slice(8, 10)}`;
	};

	const digitsBeforeCaret = (value, caret) => nationalDigits(value.slice(0, caret)).length;

	const caretAfterDigits = (formatted, count) => {
		if (count <= 0) {
			return prefix.length;
		}

		let seen = 0;
		for (let index = prefix.length; index < formatted.length; index += 1) {
			if (/\d/.test(formatted[index])) {
				seen += 1;
				if (seen >= count) {
					return index + 1;
				}
			}
		}

		return formatted.length;
	};

	document.querySelectorAll('input[name="zadzerkalya_phone"]').forEach((input) => {
		const paint = () => {
			const digits = nationalDigits(input.value);

			if (!digits && document.activeElement !== input) {
				input.value = '';
				return;
			}

			const caret = input.selectionStart ?? input.value.length;
			const before = digitsBeforeCaret(input.value, caret);
			const next = formatPhone(digits);

			if (input.value !== next) {
				input.value = next;
			}

			if (document.activeElement === input) {
				const pos = caretAfterDigits(next, Math.min(before, digits.length));
				input.setSelectionRange(pos, pos);
			}
		};

		input.addEventListener('focus', () => {
			if (!nationalDigits(input.value)) {
				input.value = prefix;
			}

			window.requestAnimationFrame(() => {
				const pos = Math.max(input.selectionStart ?? prefix.length, prefix.length);
				input.setSelectionRange(pos, pos);
			});
		});

		input.addEventListener('blur', () => {
			if (!nationalDigits(input.value)) {
				input.value = '';
			}
		});

		input.addEventListener('beforeinput', (event) => {
			const end = input.selectionEnd ?? 0;
			const deleting = event.inputType === 'deleteContentBackward' || event.inputType === 'deleteContentForward';

			if (deleting && end <= prefix.length) {
				event.preventDefault();
				input.setSelectionRange(prefix.length, prefix.length);
			}
		});

		const writeDigits = (digits, caretDigits) => {
			const next = formatPhone(digits);
			input.value = next;
			const pos = caretAfterDigits(next, caretDigits);
			input.setSelectionRange(pos, pos);
		};

		input.addEventListener('keydown', (event) => {
			const start = input.selectionStart ?? 0;
			const end = input.selectionEnd ?? 0;
			const value = input.value;

			if ((event.key === 'ArrowLeft' || event.key === 'Home') && start <= prefix.length && end <= prefix.length && !event.shiftKey && !event.metaKey && !event.ctrlKey) {
				event.preventDefault();
				input.setSelectionRange(prefix.length, prefix.length);
				return;
			}

			if (start !== end || (event.key !== 'Backspace' && event.key !== 'Delete')) {
				return;
			}

			if (event.key === 'Backspace' && start <= prefix.length) {
				event.preventDefault();
				input.setSelectionRange(prefix.length, prefix.length);
				return;
			}

			if (event.key === 'Delete' && start < prefix.length) {
				event.preventDefault();
				input.setSelectionRange(prefix.length, prefix.length);
				return;
			}

			const digits = nationalDigits(value);
			const index = digitsBeforeCaret(value, start);
			const onDigit = event.key === 'Backspace'
				? /\d/.test(value[start - 1] || '')
				: /\d/.test(value[start] || '');

			if (onDigit) {
				return;
			}

			event.preventDefault();
			if (event.key === 'Backspace') {
				writeDigits(digits.slice(0, Math.max(0, index - 1)) + digits.slice(index), Math.max(0, index - 1));
				return;
			}

			writeDigits(digits.slice(0, index) + digits.slice(index + 1), index);
		});

		input.addEventListener('input', paint);
		input.addEventListener('change', paint);
	});
})();

(() => {
	const logo = document.querySelector('[data-header-logo]');
	if (!logo) {
		return;
	}

	const base = logo.dataset.animBase || '';
	const logoEl = logo.querySelector('[data-header-logo-anim]');
	if (!base || !logoEl) {
		return;
	}

	const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
	let booted = false;
	let logoAnim = null;
	let currentBp = '';
	let finished = false;
	let playing = false;

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

	const whenReady = (animation) => new Promise((resolve, reject) => {
		animation.addEventListener('DOMLoaded', resolve);
		animation.addEventListener('data_failed', reject);
	});

	const destroy = () => {
		if (logoAnim) {
			logoAnim.destroy();
			logoAnim = null;
		}
		logoEl.replaceChildren();
	};

	const showFinal = () => {
		if (!logoAnim) {
			return;
		}
		logoAnim.goToAndStop(logoAnim.totalFrames - 1, true);
		finished = true;
		playing = false;
	};

	const mount = async () => {
		const bp = breakpoint();
		if (bp === currentBp && logoAnim) {
			return true;
		}

		currentBp = bp;
		destroy();
		finished = false;
		playing = false;

		logoAnim = lottie.loadAnimation({
			container: logoEl,
			renderer: 'svg',
			loop: false,
			autoplay: false,
			path: `${base}logo-only-${bp}.json`,
			rendererSettings: {
				preserveAspectRatio: 'xMidYMid meet',
			},
		});

		try {
			await whenReady(logoAnim);
		} catch (error) {
			destroy();
			logo.classList.remove('is-animated');
			return false;
		}

		logoAnim.goToAndStop(0, true);
		if (reduce.matches) {
			showFinal();
		}
		logo.classList.add('is-animated');
		return true;
	};

	const play = async () => {
		if (playing || finished || typeof lottie === 'undefined') {
			return;
		}

		const ready = await mount();
		if (!ready || finished || reduce.matches || !logoAnim) {
			return;
		}

		playing = true;
		logoAnim.goToAndPlay(0, true);
		logoAnim.addEventListener('complete', () => {
			finished = true;
			playing = false;
		});
	};

	const boot = () => {
		if (booted || typeof lottie === 'undefined') {
			return;
		}
		booted = true;
		play();

		let timer;
		window.addEventListener('resize', () => {
			window.clearTimeout(timer);
			timer = window.setTimeout(async () => {
				if (!logo.classList.contains('is-animated') || breakpoint() === currentBp || playing) {
					return;
				}
				const ready = await mount();
				if (ready) {
					showFinal();
				}
			}, 200);
		});
	};

	boot();
	window.addEventListener('load', boot);
})();

(() => {
	const brand = document.querySelector('[data-footer-brand]');
	if (!brand) {
		return;
	}

	const base = brand.dataset.animBase || '';
	const logoEl = brand.querySelector('[data-footer-logo]');
	const taglineEl = brand.querySelector('[data-footer-tagline]');
	if (!base || !logoEl || !taglineEl) {
		return;
	}

	const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
	const taglineDelay = 4050;
	let booted = false;
	let logoAnim = null;
	let taglineAnim = null;
	let currentBp = '';
	let taglineTimer = 0;
	let finished = false;
	let playing = false;

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

	const whenReady = (animation) => new Promise((resolve, reject) => {
		animation.addEventListener('DOMLoaded', resolve);
		animation.addEventListener('data_failed', reject);
	});

	const destroy = () => {
		window.clearTimeout(taglineTimer);
		if (logoAnim) {
			logoAnim.destroy();
			logoAnim = null;
		}
		if (taglineAnim) {
			taglineAnim.destroy();
			taglineAnim = null;
		}
		logoEl.replaceChildren();
		taglineEl.replaceChildren();
	};

	const showFinal = () => {
		if (!logoAnim || !taglineAnim) {
			return;
		}
		logoAnim.goToAndStop(logoAnim.totalFrames - 1, true);
		taglineAnim.goToAndStop(taglineAnim.totalFrames - 1, true);
		finished = true;
		playing = false;
	};

	const mount = async () => {
		const bp = breakpoint();
		if (bp === currentBp && logoAnim && taglineAnim) {
			return true;
		}

		currentBp = bp;
		destroy();
		finished = false;
		playing = false;
		brand.classList.add('is-pending');

		logoAnim = lottie.loadAnimation({
			container: logoEl,
			renderer: 'svg',
			loop: false,
			autoplay: false,
			path: `${base}logo-only-${bp}.json`,
		});
		taglineAnim = lottie.loadAnimation({
			container: taglineEl,
			renderer: 'svg',
			loop: false,
			autoplay: false,
			path: `${base}tagline-only-${bp}.json`,
		});

		try {
			await Promise.all([whenReady(logoAnim), whenReady(taglineAnim)]);
		} catch (error) {
			destroy();
			brand.classList.remove('is-pending', 'is-animated');
			return false;
		}

		brand.classList.add('is-animated');
		if (reduce.matches) {
			showFinal();
		}
		return true;
	};

	const play = async () => {
		if (playing || finished || typeof lottie === 'undefined') {
			return;
		}

		const ready = await mount();
		if (!ready || finished || reduce.matches || !logoAnim || !taglineAnim) {
			return;
		}

		playing = true;
		logoAnim.goToAndPlay(0, true);
		taglineTimer = window.setTimeout(() => {
			if (taglineAnim && !finished) {
				taglineAnim.play();
			}
		}, taglineDelay);
		logoAnim.addEventListener('complete', () => {
			finished = true;
			playing = false;
		});
	};

	const boot = () => {
		if (booted || typeof lottie === 'undefined') {
			return;
		}
		booted = true;

		const observer = new IntersectionObserver((entries) => {
			if (entries.some((entry) => entry.isIntersecting)) {
				observer.disconnect();
				play();
			}
		}, { rootMargin: '240px 0px', threshold: 0.2 });

		observer.observe(brand);

		let timer;
		window.addEventListener('resize', () => {
			window.clearTimeout(timer);
			timer = window.setTimeout(async () => {
				if (!brand.classList.contains('is-animated') || breakpoint() === currentBp || playing) {
					return;
				}
				const ready = await mount();
				if (ready) {
					showFinal();
				}
			}, 200);
		});
	};

	boot();
	window.addEventListener('load', boot);
})();

(() => {
	const params = new URLSearchParams(window.location.search);
	if (!params.get('contact') || params.get('contact_popup')) {
		return;
	}

	const target = document.getElementById(params.get('contact_form') || 'contact-form');
	if (!target) {
		return;
	}

	const reveal = () => target.scrollIntoView({ block: 'start' });
	reveal();
	window.addEventListener('load', reveal, { once: true });
})();
