(() => {
	const config = window.zadzerkalyaCookieConfig || {};
	const consentName = config.consent || 'zadzerkalya_consent';
	const visitorName = config.visitor || 'zadzerkalya_visitor';
	const maxAge = Number(config.maxAge) || 60 * 60 * 24 * 365;
	const popup = document.querySelector('[data-cookies-popup]');
	const listeners = new Set();

	const read = (name) => {
		const match = document.cookie.match(new RegExp(`(?:^|; )${name}=([^;]*)`));
		return match ? decodeURIComponent(match[1]) : '';
	};

	const write = (name, value, age) => {
		const secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = `${name}=${encodeURIComponent(value)}; Path=/; Max-Age=${age}; SameSite=Lax${secure}`;
	};

	const remove = (name) => write(name, '', 0);

	const level = () => {
		const value = read(consentName);
		return value === 'all' || value === 'necessary' ? value : '';
	};

	const emit = (value) => {
		const detail = { level: value };
		document.dispatchEvent(new CustomEvent('zadzerkalya:consent', { detail }));
		listeners.forEach((fn) => fn(detail));
	};

	const rememberVisitor = () => {
		if (read(visitorName)) {
			return;
		}

		const id = window.crypto && typeof window.crypto.randomUUID === 'function'
			? window.crypto.randomUUID()
			: `${Date.now().toString(16)}-${Math.random().toString(16).slice(2)}`;

		write(visitorName, `${id}.${Date.now()}`, maxAge);
	};

	const apply = (value) => {
		write(consentName, value, maxAge);

		if (value === 'all') {
			rememberVisitor();
		} else {
			remove(visitorName);
		}

		emit(value);
	};

	const SHOW_AFTER = 2000;
	let showTimer = 0;

	const hide = () => {
		window.clearTimeout(showTimer);
		document.documentElement.classList.remove('cookies-pending');
		document.body.classList.remove('cookies-popup-open');
	};

	const choose = (value) => {
		apply(value);
		hide();
	};

	window.zadzerkalyaCookies = {
		get: level,
		allows(category) {
			if (category === 'necessary') {
				return true;
			}

			return category === 'optional' && level() === 'all';
		},
		onChange(fn) {
			listeners.add(fn);
			return () => listeners.delete(fn);
		},
		acceptAll: () => choose('all'),
		acceptNecessary: () => choose('necessary'),
	};

	const current = level();

	if (current) {
		hide();
		if (current === 'all') {
			rememberVisitor();
		}
		emit(current);
		return;
	}

	if (!popup) {
		return;
	}

	const dialog = popup.querySelector('.cookies-popup__dialog');
	const focusable = () => [...popup.querySelectorAll('button, [href], [tabindex]:not([tabindex="-1"])')]
		.filter((node) => !node.hasAttribute('disabled') && node.getClientRects().length);

	popup.querySelector('[data-cookies-all]')?.addEventListener('click', () => choose('all'));
	popup.querySelectorAll('[data-cookies-necessary]').forEach((node) => {
		node.addEventListener('click', () => choose('necessary'));
	});

	document.addEventListener('keydown', (event) => {
		if (!document.documentElement.classList.contains('cookies-pending')) {
			return;
		}

		if (event.key === 'Escape') {
			event.preventDefault();
			choose('necessary');
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

	const show = () => {
		if (level()) {
			return;
		}

		document.documentElement.classList.add('cookies-pending');
		document.body.classList.add('cookies-popup-open');
		dialog?.focus();
	};

	showTimer = window.setTimeout(show, SHOW_AFTER);
})();
