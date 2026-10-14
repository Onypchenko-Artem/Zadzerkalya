(() => {
	const feeds = document.querySelectorAll('.articles-feed');

	if (!feeds.length) {
		return;
	}

	const hashId = window.location.hash.replace(/^#/, '');
	const hashTarget = hashId ? document.getElementById(hashId) : null;

	if (hashTarget && [...feeds].some((feed) => feed.contains(hashTarget))) {
		const root = document.documentElement;
		const scrollToArticles = () => {
			root.style.scrollBehavior = 'auto';
			hashTarget.scrollIntoView({ block: 'start' });
			root.style.scrollBehavior = '';
		};

		if ('scrollRestoration' in window.history) {
			window.history.scrollRestoration = 'manual';
		}

		scrollToArticles();
		window.addEventListener('load', scrollToArticles, { once: true });
	}

	const isPlainClick = (event) => !(event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0);

	feeds.forEach((feed) => {
		feed.addEventListener('click', (event) => {
			const link = event.target.closest('.articles-feed__more a');

			if (!link || !feed.contains(link) || !isPlainClick(event)) {
				return;
			}

			event.preventDefault();
			loadMore(feed, link);
		});
	});

	const loadMore = async (feed, link) => {
		if (link.dataset.loading === '1') {
			return;
		}

		const list = feed.querySelector('.articles-feed__list');
		const url = link.href;

		if (!list) {
			window.location.assign(url);
			return;
		}

		link.dataset.loading = '1';
		link.setAttribute('aria-busy', 'true');

		try {
			const response = await fetch(url, {
				headers: { 'X-Requested-With': 'fetch' },
			});

			if (!response.ok) {
				throw new Error('feed');
			}

			const html = await response.text();
			const doc = new DOMParser().parseFromString(html, 'text/html');
			const rows = [...doc.querySelectorAll('.articles-feed__list .articles-feed__row')];

			if (!rows.length) {
				throw new Error('feed');
			}

			rows.forEach((row) => {
				list.append(row);

				if (typeof window.zadzerkalyaInitButtons === 'function') {
					window.zadzerkalyaInitButtons(row);
				}
			});

			const slider = feed.querySelector('.articles-feed__slider');
			const nextSlider = doc.querySelector('.articles-feed__slider');

			if (slider && nextSlider) {
				slider.replaceWith(nextSlider);
			}

			const more = feed.querySelector('.articles-feed__more');
			const nextMore = doc.querySelector('.articles-feed__more a');

			if (more && nextMore) {
				link.href = nextMore.href;
			} else if (more) {
				more.remove();
			}
		} catch (error) {
			window.location.assign(url);
		} finally {
			if (link.isConnected) {
				delete link.dataset.loading;
				link.removeAttribute('aria-busy');
			}
		}
	};
})();
