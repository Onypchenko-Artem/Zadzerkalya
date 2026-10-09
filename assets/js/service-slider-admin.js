document.addEventListener('DOMContentLoaded', () => {
	const root = document.querySelector('[data-zdk-slider-admin]');
	if (!root) {
		return;
	}

	const list = root.querySelector('[data-zdk-slider-list]');
	const select = root.querySelector('[data-zdk-slider-select]');
	const add = root.querySelector('[data-zdk-slider-add]');
	const empty = root.querySelector('[data-zdk-slider-empty]');

	if (!list || !select || !add) {
		return;
	}

	const sync = () => {
		const used = new Set(
			[...list.querySelectorAll('input[name="zdk_slider_services[]"]')].map((input) => input.value)
		);

		[...select.options].forEach((option) => {
			if (!option.value) {
				return;
			}
			option.disabled = used.has(option.value);
		});

		const current = select.selectedOptions[0];
		if (current && current.disabled) {
			select.value = '';
		}

		if (empty) {
			empty.hidden = list.children.length > 0;
		}
	};

	const row = (id, title) => {
		const item = document.createElement('li');
		item.className = 'zdk-slider-admin__item';

		const input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'zdk_slider_services[]';
		input.value = id;

		const name = document.createElement('span');
		name.className = 'zdk-slider-admin__name';
		name.textContent = title;

		const actions = document.createElement('span');
		actions.className = 'zdk-slider-admin__actions';
		actions.innerHTML = '<button type="button" class="button button-small" data-move="up" aria-label="Вище">↑</button><button type="button" class="button button-small" data-move="down" aria-label="Нижче">↓</button><button type="button" class="button button-small" data-remove>Прибрати</button>';

		item.append(input, name, actions);
		return item;
	};

	add.addEventListener('click', () => {
		const option = select.selectedOptions[0];
		if (!option || !option.value || option.disabled) {
			return;
		}

		list.append(row(option.value, option.textContent.trim()));
		select.value = '';
		sync();
	});

	list.addEventListener('click', (event) => {
		const button = event.target.closest('button');
		const item = button ? button.closest('.zdk-slider-admin__item') : null;
		if (!button || !item) {
			return;
		}

		if (button.hasAttribute('data-remove')) {
			item.remove();
			sync();
			return;
		}

		if (button.dataset.move === 'up' && item.previousElementSibling) {
			list.insertBefore(item, item.previousElementSibling);
		}

		if (button.dataset.move === 'down' && item.nextElementSibling) {
			list.insertBefore(item.nextElementSibling, item);
		}
	});

	sync();
});
