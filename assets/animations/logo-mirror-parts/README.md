# Лого і слоган окремо — Lottie

Та сама анімація, що в цільному лого «Задзеркалля», розділена на дві незалежні частини, кожна — окремими файлами для десктопа, планшета й мобільного:

- **лого** — дзеркало, мама й дитина промальовуються лінією, дзеркало осипається уламками, дотик чолом, серце, «задзеркалля»;
- **слоган** — «Ми віримо в кожну дитину»: слова по черзі виїжджають знизу з легким нахилом.

Кожен файл запускається й розміщується окремо (свій відступ, вирівнювання, момент старту). Подивитись: `demo.html` (відкривається подвійним кліком; потрібен інтернет для lottie-web).

## Файли

| Файл | Кадр | Тривалість | Що всередині |
|---|---|---|---|
| `logo-only-desktop.json` | 462×485 | 5,82 с | лише лого, десктоп (≥ 1280 px) |
| `logo-only-tablet.json` | 221×234 | 5,82 с | лише лого, планшет (768–1279 px) |
| `logo-only-mobile.json` | 71×76 | 5,82 с | лише лого, мобільний (< 768 px) |
| `tagline-only-desktop.json` | 557×76 | 1,77 с | лише слоган, десктоп (≥ 1280 px) |
| `tagline-only-tablet.json` | 356×48 | 1,77 с | лише слоган, планшет (768–1279 px) |
| `tagline-only-mobile.json` | 361×32 | 1,77 с | лише слоган, мобільний (< 768 px) |
| `manifest.json` | — | — | розміри, тривалість, вага, позиції з макета |

- **Лого і слоган — по файлу на кожен брейкпоінт.** Кадр лого = блок лого з макета Figma для цього екрана; кадр слогана — рядок тексту на всю ширину блоку, текст по центру. Анімація не виходить за межі свого кадру. Фінал лого — затверджений: дзеркало повертається, лого як у Figma.
- Слоган починається одразу з першого кадру. У цільній анімації він з'являється на **4,05 с** — щоб відтворити цільну анімацію, запустіть слоган із цією затримкою після старту лого (див. приклад нижче).
- Кожен JSON лого ≈ 1 МБ (≈ 125 КБ gzip), слоган ≈ 50 КБ (≈ 12 КБ gzip). Увімкніть стиснення JSON на сервері.
- Літери й слоган — контури, шрифт підключати не треба. Текст для скрінрідера — через `aria-label` на контейнерах.

## Підключення (lottie-web 5.x, renderer `svg`)

```html
<div class="zl-logo" role="img" aria-label="Задзеркалля"></div>
<div class="zl-tagline" role="img" aria-label="Ми віримо в кожну дитину"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
<script>
  const bp = matchMedia('(min-width:1280px)').matches ? 'desktop' : matchMedia('(min-width:768px)').matches ? 'tablet' : 'mobile';
  const opts = (el, file) => ({ container: el, renderer: 'svg', loop: false, autoplay: false, path: '/animations/' + file });
  const logo = lottie.loadAnimation(opts(document.querySelector('.zl-logo'), `logo-only-${bp}.json`));
  const tagline = lottie.loadAnimation(opts(document.querySelector('.zl-tagline'), `tagline-only-${bp}.json`));
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  Promise.all([logo, tagline].map((a) => new Promise((r) => a.addEventListener('DOMLoaded', r)))).then(() => {
    if (reduce) { [logo, tagline].forEach((a) => a.goToAndStop(a.totalFrames - 1, true)); return; }   // без руху — одразу фінал
    logo.play();
    setTimeout(() => tagline.play(), 4050);   // як у цільній анімації; затримку можна змінити або запускати слоган окремо
  });
</script>
```

```css
.zl-logo    { width: 100%; max-width: 462px; aspect-ratio: 462 / 485; margin-inline: auto; }
.zl-tagline { width: 100%; max-width: 557px; aspect-ratio: 557 / 76;  margin-inline: auto; }
.zl-logo svg, .zl-tagline svg { display: block; }                 /* без відступу знизу, як у рядкового елемента */
@media (max-width: 1279px) { .zl-logo { max-width: 221px; aspect-ratio: 221 / 234; } .zl-tagline { max-width: 356px; aspect-ratio: 356 / 48; } }
@media (max-width: 767px)  { .zl-logo { max-width: 71px;  aspect-ratio: 71 / 76; }   .zl-tagline { max-width: 361px; aspect-ratio: 361 / 32; } }
```

- **Рендерер** — тільки `svg`: `canvas` не підтримує маски, на яких побудовані промальовування й уламки лого.
- **Перший кадр лого** — порожнє дзеркало; **останній** — фінальне лого. Слоган на першому кадрі порожній, на останньому — повний рядок.
- **Як у макеті:** відстань від низу контейнера лого до верху контейнера слогана — десктоп ≈ 163 px, планшет ≈ 201 px, мобільний 16 px. Точні позиції в кадрі макета — `manifest.json`: `logo[].offsetInWhole` (x, y) і `tagline[].offsetInWhole.y`. На планшеті лого в макеті стоїть на ≈ 5 px лівіше від центру — з `margin-inline: auto` воно буде точно по центру.

Цільна версія (лого й слоган в одному файлі) — в окремому архіві `logo-mirror.zip`.
