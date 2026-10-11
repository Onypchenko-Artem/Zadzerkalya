(() => {
	const feed = document.querySelector('.articles-feed[data-ajax-filters]');

	if (!feed) {
		return;
	}

	const filters = feed.querySelector('.articles-feed__filters');

	if (!filters) {
		return;
	}

	let request = null;

	const syncFilters = (doc) => {
		const nextFilters = [...doc.querySelectorAll('.articles-feed__filters .articles-filter')];
		const currentFilters = [...filters.querySelectorAll('.articles-filter')];

		currentFilters.forEach((item, index) => {
			const active = Boolean(nextFilters[index]?.classList.contains('is-active'));

			item.classList.toggle('is-active', active);

			if (active) {
				item.setAttribute('aria-current', 'page');
			} else {
				item.removeAttribute('aria-current');
			}
		});
	};

	const load = async (url, push) => {
		const articles = feed.querySelector('.articles-feed__articles');

		if (!articles) {
			window.location.assign(url);
			return;
		}

		if (request) {
			request.abort();
		}

		const controller = new AbortController();
		request = controller;
		articles.classList.add('is-loading');
		articles.setAttribute('aria-busy', 'true');

		try {
			const response = await fetch(url, {
				signal: controller.signal,
				headers: { 'X-Requested-With': 'fetch' },
			});

			if (!response.ok) {
				throw new Error('feed');
			}

			const html = await response.text();
			const doc = new DOMParser().parseFromString(html, 'text/html');
			const next = doc.querySelector('.articles-feed__articles');

			if (!next) {
				throw new Error('feed');
			}

			articles.replaceWith(next);
			syncFilters(doc);

			if (typeof window.zadzerkalyaInitButtons === 'function') {
				window.zadzerkalyaInitButtons(feed);
			}

			if (push && new URL(url, window.location.href).href !== window.location.href) {
				window.history.pushState({ articlesFeed: true }, '', url);
			}
		} catch (error) {
			if (error.name === 'AbortError') {
				return;
			}

			window.location.assign(url);
		} finally {
			if (request === controller) {
				request = null;
				const current = feed.querySelector('.articles-feed__articles');

				if (current) {
					current.classList.remove('is-loading');
					current.removeAttribute('aria-busy');
				}
			}
		}
	};

	filters.addEventListener('click', (event) => {
		const link = event.target.closest('a.articles-filter');

		if (!link || !filters.contains(link)) {
			return;
		}

		if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
			return;
		}

		event.preventDefault();
		load(link.href, true);
	});

	window.addEventListener('popstate', () => {
		load(window.location.href, false);
	});
})();
