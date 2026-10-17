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

	const all = mega.querySelector('.services-mega__all');
	const cats = Array.from(mega.querySelectorAll('.services-mega__cat'));
	const panels = Array.from(mega.querySelectorAll('.services-mega__panel'));
	const photos = Array.from(mega.querySelectorAll('.services-mega__photo'));
	let isOpen = false;

	const activate = (target) => {
		if (all) {
			all.classList.toggle('is-active', all.dataset.megaTarget === target);
		}
		cats.forEach((cat) => {
			cat.classList.toggle('is-active', cat.dataset.megaTarget === target);
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
		if (!header) {
			return;
		}

		mega.style.paddingTop = `${header.offsetHeight}px`;
	};

	const open = () => {
		if (!desktop.matches || isOpen) {
			return;
		}

		syncOffset();
		isOpen = true;
		mega.classList.add('is-open');
		mega.setAttribute('aria-hidden', 'false');
		item.classList.add('is-open');
		link.setAttribute('aria-expanded', 'true');
		document.body.classList.add('services-mega-open');
	};

	const close = () => {
		if (!isOpen) {
			return;
		}

		isOpen = false;
		mega.classList.remove('is-open');
		mega.setAttribute('aria-hidden', 'true');
		item.classList.remove('is-open');
		link.setAttribute('aria-expanded', 'false');
		document.body.classList.remove('services-mega-open');

		if (all) {
			activate(all.dataset.megaTarget);
		}
	};

	link.addEventListener('click', (event) => {
		if (!desktop.matches) {
			return;
		}

		event.preventDefault();

		if (isOpen) {
			close();
			return;
		}

		open();
	});

	mega.querySelector('.services-mega__close')?.addEventListener('click', () => {
		close();
		link.focus();
	});

	document.addEventListener('click', (event) => {
		if (!isOpen) {
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

	cats.forEach((cat) => {
		const show = () => {
			open();
			activate(cat.dataset.megaTarget);
		};

		cat.addEventListener('mouseenter', show);
		cat.addEventListener('focus', show);
	});

	if (all) {
		const showAll = () => {
			open();
			activate(all.dataset.megaTarget);
		};

		all.addEventListener('mouseenter', showAll);
		all.addEventListener('focus', showAll);
	}

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
	const initServiceSliders = () => {
		document.querySelectorAll('[data-service-slider]').forEach((section) => {
			const viewport = section.querySelector('.service-slider__viewport');
			const track = section.querySelector('.service-slider__track');
			const previous = section.querySelector('.service-slider__arrow--prev');
			const next = section.querySelector('.service-slider__arrow--next');

			if (!viewport || !track || !previous || !next) {
				return;
			}

			let end = track.querySelector('.service-slider__end');
			if (!end) {
				end = document.createElement('div');
				end.className = 'service-slider__end';
				end.setAttribute('aria-hidden', 'true');
				track.append(end);
			}

			const slides = () => [...track.querySelectorAll('.service-slider__slide')];

			const placeEnd = () => {
				const first = slides()[0];
				const extra = first ? Math.max(0, viewport.clientWidth - first.getBoundingClientRect().width) : 0;
				const width = `${Math.round(extra)}px`;
				if (end.style.width !== width) {
					end.style.flex = `0 0 ${width}`;
					end.style.width = width;
				}
			};

			const slideStart = (slide) => viewport.scrollLeft + slide.getBoundingClientRect().left - viewport.getBoundingClientRect().left;

			const nearestIndex = () => {
				const items = slides();
				const left = viewport.scrollLeft;
				let index = 0;

				items.forEach((slide, i) => {
					if (slideStart(slide) <= left + 2) {
						index = i;
					}
				});

				return index;
			};

			const setDisabled = (button, disabled) => {
				button.classList.toggle('is-disabled', disabled);
				button.setAttribute('aria-disabled', disabled ? 'true' : 'false');
			};

			const updateState = () => {
				const items = slides();
				const index = nearestIndex();
				const maxScroll = viewport.scrollWidth - viewport.clientWidth;
				setDisabled(previous, index <= 0 || maxScroll <= 1);
				setDisabled(next, items.length < 2 || index >= items.length - 1 || maxScroll <= 1);
			};

			const relayout = () => {
				placeEnd();
				updateState();
			};

			let frame = 0;
			let animating = false;
			let index = 0;
			const motion = window.matchMedia('(prefers-reduced-motion: reduce)');

			const animateTo = (target) => {
				const maxScroll = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
				const destination = Math.max(0, Math.min(target, maxScroll));
				const start = viewport.scrollLeft;
				const distance = destination - start;

				cancelAnimationFrame(frame);

				if (Math.abs(distance) < 1 || motion.matches) {
					animating = false;
					viewport.scrollLeft = destination;
					updateState();
					return;
				}

				const duration = 520;
				const started = performance.now();
				animating = true;

				const step = (now) => {
					const progress = Math.min(1, (now - started) / duration);
					const eased = 1 - (1 - progress) ** 3;
					viewport.scrollLeft = start + distance * eased;

					if (progress < 1) {
						frame = requestAnimationFrame(step);
						return;
					}

					animating = false;
					viewport.scrollLeft = destination;
					updateState();
				};

				frame = requestAnimationFrame(step);
			};

			const scrollToIndex = (nextIndex) => {
				const items = slides();
				const clamped = Math.max(0, Math.min(nextIndex, items.length - 1));
				const slide = items[clamped];
				if (!slide) {
					return;
				}

				index = clamped;
				animateTo(slideStart(slide));
			};

			const onControlClick = (event) => {
				const arrow = event.target.closest('.service-slider__arrow');
				if (!arrow || arrow.getAttribute('aria-disabled') === 'true') {
					return;
				}

				event.preventDefault();
				const direction = arrow.classList.contains('service-slider__arrow--next') ? 1 : -1;
				const base = animating ? index : nearestIndex();
				scrollToIndex(base + direction);
			};

			previous.addEventListener('click', onControlClick);
			next.addEventListener('click', onControlClick);
			viewport.addEventListener('scroll', () => {
				if (!animating) {
					updateState();
				}
			}, { passive: true });
			window.addEventListener('resize', relayout);

			if (window.ResizeObserver) {
				const observer = new ResizeObserver(relayout);
				observer.observe(viewport);
			}

			relayout();
			index = nearestIndex();
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
	const popup = document.querySelector('[data-contact-popup]');
	if (!popup) {
		return;
	}

	const dialog = popup.querySelector('.contact-popup__dialog');
	let lastFocus = null;

	const focusable = () => [...popup.querySelectorAll('button, [href], input, textarea, select, [tabindex]:not([tabindex="-1"])')]
		.filter((node) => !node.closest('.hp-field') && !node.hasAttribute('disabled') && node.getClientRects().length);

	const open = () => {
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
			open();
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
