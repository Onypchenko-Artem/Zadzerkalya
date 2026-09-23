(() => {
	const hero = document.querySelector('.about-hero');

	if (!hero) {
		return;
	}

	window.requestAnimationFrame(() => {
		hero.classList.add('is-ready');
	});
})();
