// Lottie (bodymovin 5.x) для hover-анімації кнопок «скетч» — компонент button, Figma 6102:16846.
// Джерело: button-sketch.figma.json (експорт через Figmosha). Запуск: node build.js
//
// Анімація — механіка barrowstreetnurseryschool.org (.cta-btn, @keyframes frameMoveXY):
// кожен із трьох контурів дзеркалиться навколо центру своїх меж жорсткими кроками
// scale(1,1) → (-1,-1) → (-1,1) → (1,-1), по 187.5ms, цикл 0.75s. Кадр 0 = вигляд з Figma.
// Текст у JSON не входить — лишається живим HTML поверх анімації.
const fs = require("fs");
const path = require("path");

const FR = 48;       // 0.75s = 36 кадрів, крок = 9 кадрів = 187.5ms
const OP = 36;
const STEP = 9;
const PAD = 6;       // полотно ширше за кнопку: криві контури виходять за межі на 1–2px, lottie-web обрізає по полотну
const r3 = (n) => Math.round(n * 1000) / 1000;
const rgb = (hex) => [1, 3, 5].map((i) => r3(parseInt(hex.slice(i, i + 2), 16) / 255));

const data = JSON.parse(fs.readFileSync(path.join(__dirname, "button-sketch.figma.json"), "utf8"));

function slug(props) {
  const bp = props.Breakpoint.toLowerCase().replace(/\s+/g, "-");
  return `button-${props.Variant.toLowerCase()}-${bp}`;
}

function parsePath(d, dx, dy) {
  const t = d.match(/[MCZ]|-?\d*\.?\d+(?:e[-+]?\d+)?/g);
  const v = [], inT = [], outT = [];
  let i = 0, closed = false;
  const pt = () => [parseFloat(t[i++]) + dx, parseFloat(t[i++]) + dy];
  while (i < t.length) {
    const c = t[i++];
    if (c === "M") { v.push(pt()); inT.push([0, 0]); outT.push([0, 0]); }
    else if (c === "C") {
      const c1 = pt(), c2 = pt(), p = pt(), prev = v[v.length - 1];
      outT[outT.length - 1] = [c1[0] - prev[0], c1[1] - prev[1]];
      v.push(p); inT.push([c2[0] - p[0], c2[1] - p[1]]); outT.push([0, 0]);
    } else if (c === "Z") closed = true;
    else throw new Error("Unsupported path command: " + c);
  }
  const last = v.length - 1;
  if (closed && Math.hypot(v[last][0] - v[0][0], v[last][1] - v[0][1]) < 1e-3) {
    inT[0] = inT[last];
    v.pop(); inT.pop(); outT.pop();
  }
  return { c: closed, v, i: inT, o: outT };
}

// Точні межі кривої (як transform-box: fill-box у CSS) — щільна вибірка кожного сегмента.
function bounds(shape) {
  let x0 = Infinity, y0 = Infinity, x1 = -Infinity, y1 = -Infinity;
  const n = shape.v.length, segs = shape.c ? n : n - 1;
  for (let k = 0; k < segs; k++) {
    const a = shape.v[k], b = shape.v[(k + 1) % n];
    const p1 = [a[0] + shape.o[k][0], a[1] + shape.o[k][1]];
    const p2 = [b[0] + shape.i[(k + 1) % n][0], b[1] + shape.i[(k + 1) % n][1]];
    for (let s = 0; s <= 40; s++) {
      const t = s / 40, u = 1 - t;
      const x = u * u * u * a[0] + 3 * u * u * t * p1[0] + 3 * u * t * t * p2[0] + t * t * t * b[0];
      const y = u * u * u * a[1] + 3 * u * u * t * p1[1] + 3 * u * t * t * p2[1] + t * t * t * b[1];
      x0 = Math.min(x0, x); y0 = Math.min(y0, y); x1 = Math.max(x1, x); y1 = Math.max(y1, y);
    }
  }
  return { cx: (x0 + x1) / 2, cy: (y0 + y1) / 2 };
}

const round = (shape) => ({ c: shape.c, v: shape.v.map((p) => p.map(r3)), i: shape.i.map((p) => p.map(r3)), o: shape.o.map((p) => p.map(r3)) });
const tr = () => ({ ty: "tr", p: { a: 0, k: [0, 0] }, a: { a: 0, k: [0, 0] }, s: { a: 0, k: [100, 100] }, r: { a: 0, k: 0 }, o: { a: 0, k: 100 }, sk: { a: 0, k: 0 }, sa: { a: 0, k: 0 } });
const layerBase = (ind, nm) => ({ ddd: 0, ind, ty: 4, nm, sr: 1, ao: 0, ip: 0, op: OP, st: 0, bm: 0 });

// frameMoveXY: 0% scaleX(1) · 25% scale(-1) · 50% scaleX(-1) · 75% scaleY(-1) · 100% scaleX(1)
const MIRROR = [[100, 100, 100], [-100, -100, 100], [-100, 100, 100], [100, -100, 100]];
const scaleKeys = () => [
  ...MIRROR.map((s, k) => ({ t: k * STEP, s, h: 1 })),
  { t: OP, s: MIRROR[0] }
];

const manifest = [];
for (const v of data.variants) {
  const name = slug(v.properties);
  const W = Math.round(v.w), H = Math.round(v.h);
  const layers = [];

  // Порядок у Lottie: перший шар — верхній. У Figma знизу вгору: Ellipse 2 (заливка) · Ellipse 4 · Ellipse 6 · Ellipse 5.
  [...v.outlines].reverse().forEach((o) => {
    const [[, , tx], [, , ty]] = o.transform;
    const shape = parsePath(o.paths.join(" "), tx + PAD, ty + PAD);
    const { cx, cy } = bounds(shape);
    layers.push({
      ...layerBase(layers.length + 1, o.name),
      ks: {
        o: { a: 0, k: 100 }, r: { a: 0, k: 0 },
        p: { a: 0, k: [r3(cx), r3(cy), 0] },
        a: { a: 0, k: [r3(cx), r3(cy), 0] },
        s: { a: 1, k: scaleKeys() }
      },
      shapes: [{
        ty: "gr", nm: o.name,
        it: [
          { ty: "sh", nm: "path", ks: { a: 0, k: round(shape) } },
          { ty: "st", nm: "stroke · " + o.variable, c: { a: 0, k: [...rgb(o.color), 1] }, o: { a: 0, k: 100 }, w: { a: 0, k: o.strokeWeight }, lc: 2, lj: 2 },
          tr()
        ]
      }]
    });
  });

  if (v.fill) {
    const f = v.fill;
    layers.push({
      ...layerBase(layers.length + 1, "Ellipse 2"),
      ks: { o: { a: 0, k: 100 }, r: { a: 0, k: 0 }, p: { a: 0, k: [0, 0, 0] }, a: { a: 0, k: [0, 0, 0] }, s: { a: 0, k: [100, 100, 100] } },
      shapes: [{
        ty: "gr", nm: "Ellipse 2",
        it: [
          { ty: "el", nm: "ellipse", d: 1, p: { a: 0, k: [r3(f.x + f.w / 2 + PAD), r3(f.y + f.h / 2 + PAD)] }, s: { a: 0, k: [r3(f.w), r3(f.h)] } },
          { ty: "fl", nm: "fill · " + f.variable, c: { a: 0, k: [...rgb(f.color), 1] }, o: { a: 0, k: 100 }, r: 1 },
          tr()
        ]
      }]
    });
  }

  const lottie = {
    v: "5.7.4", fr: FR, ip: 0, op: OP, w: W + PAD * 2, h: H + PAD * 2,
    nm: name, ddd: 0, assets: [], layers,
    markers: [{ tm: 0, cm: "idle", dr: 0 }],
    meta: { g: "Zadzerkalya button-sketch/build.js", a: "Figma 6102:16846 / " + v.nodeId }
  };
  const file = name + ".json";
  fs.writeFileSync(path.join(__dirname, file), JSON.stringify(lottie));
  const t = v.text;
  manifest.push({
    file, variant: v.properties.Variant, breakpoint: v.properties.Breakpoint, figmaNodeId: v.nodeId,
    button: { w: W, h: H }, canvas: { w: lottie.w, h: lottie.h, inset: -PAD },
    label: t.label,
    text: { x: r3(t.x), y: r3(t.y), w: r3(t.w), h: r3(t.h), fontSize: t.fontSize, fontFamily: t.fontFamily, lineHeightPercent: t.lineHeight.unit === "PERCENT" ? r3(t.lineHeight.value) : null, color: t.color, variable: t.variable },
    bytes: fs.statSync(path.join(__dirname, file)).size
  });
}

const manifestJson = {
  component: "button (Figma 6102:16846)",
  animation: { fps: FR, frames: OP, durationMs: OP / FR * 1000, loop: true, idleFrame: 0, stepMs: STEP / FR * 1000 },
  canvasPad: PAD,
  variants: manifest
};
fs.writeFileSync(path.join(__dirname, "manifest.json"), JSON.stringify(manifestJson, null, 2));

// Один браузерний bundle для теми, зібраний із актуальних JSON-варіантів.
const runtime = Object.fromEntries(manifest.map((m) => [m.file.replace(/\.json$/, ""), JSON.parse(fs.readFileSync(path.join(__dirname, m.file), "utf8"))]));
fs.writeFileSync(
  path.join(__dirname, "../../js/button-animations.js"),
  "window.zadzerkalyaButtonAnims = " + JSON.stringify(runtime) + ";\n"
);

// Демо підтягує дані інлайн — відкривається і з диска, без сервера.
const demoFile = path.join(__dirname, "demo.html");
const inline = Object.fromEntries(manifest.map((m) => [m.file, JSON.parse(fs.readFileSync(path.join(__dirname, m.file), "utf8"))]));
const demo = fs.readFileSync(demoFile, "utf8")
  .replace(/\/\*DATA\*\/[\s\S]*?\/\*END\*\//, () => "/*DATA*/" + JSON.stringify(inline) + "/*END*/")
  .replace(/\/\*MANIFEST\*\/[\s\S]*?\/\*ENDM\*\//, () => "/*MANIFEST*/" + JSON.stringify(manifestJson) + "/*ENDM*/");
fs.writeFileSync(demoFile, demo);
console.log(manifest.map((m) => `${m.file}  ${m.canvas.w}×${m.canvas.h}  ${m.bytes} B`).join("\n"));
