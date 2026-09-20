// Генерує Lottie JSON (bodymovin 5.x) hover-анімації кнопки з design-system/components/button.json.
// Запуск: node design-system/animations/lottie/build-lottie.js
const fs = require("fs");
const path = require("path");
const root = path.resolve(__dirname, "../../..");
const spec = JSON.parse(fs.readFileSync(path.join(root, "design-system/components/button.json"), "utf8"));

const FR = 48;                 // 48 fps → 0.75s = 36 кадрів, кожна позиція = 9 кадрів (187.5ms)
const OP = 36;
const hexToRgb = (hex) => [1, 3, 5].map((i) => Math.round(parseInt(hex.slice(i, i + 2), 16) / 255 * 10000) / 10000);
const r3 = (n) => Math.round(n * 1000) / 1000;

function svgPathToLottie(d) {
  const nums = d.match(/[A-Za-z]|-?\d*\.?\d+(?:e[-+]?\d+)?/g);
  let i = 0, cmd = null;
  const v = [], inT = [], outT = [];
  const num = () => parseFloat(nums[i++]);
  while (i < nums.length) {
    if (/[A-Za-z]/.test(nums[i])) cmd = nums[i++];
    if (cmd === "M") { v.push([num(), num()]); inT.push([0, 0]); outT.push([0, 0]); }
    else if (cmd === "C") {
      const c1 = [num(), num()], c2 = [num(), num()], p = [num(), num()];
      const prev = v[v.length - 1];
      outT[outT.length - 1] = [c1[0] - prev[0], c1[1] - prev[1]];
      v.push(p); inT.push([c2[0] - p[0], c2[1] - p[1]]); outT.push([0, 0]);
    } else throw new Error("Unsupported path command: " + cmd);
  }
  const R = (a) => a.map((p) => p.map(r3));
  return { c: false, v: R(v), i: R(inT), o: R(outT) };
}

const tr = () => ({ ty: "tr", p: { a: 0, k: [0, 0] }, a: { a: 0, k: [0, 0] }, s: { a: 0, k: [100, 100] }, r: { a: 0, k: 0 }, o: { a: 0, k: 100 }, sk: { a: 0, k: 0 }, sa: { a: 0, k: 0 } });
const staticKs = (w, h) => ({ o: { a: 0, k: 100 }, r: { a: 0, k: 0 }, p: { a: 0, k: [0, 0, 0] }, a: { a: 0, k: [0, 0, 0] }, s: { a: 0, k: [100, 100, 100] } });

const out = {};
for (const v of spec.variants) {
  const { Variant, Breakpoint } = v.properties;
  const L = v.layers, o = L.outline;
  const cx = o.x + o.width / 2, cy = o.y + o.height / 2;
  // «Кипіння»: hold-keyframes (h:1) — позиція змінюється стрибком, як steps(1) у CSS
  const flips = [[100, 100], [-100, -100], [-100, 100], [100, -100], [100, 100]];
  const scaleKeys = flips.map((s, k) => (k < flips.length - 1
    ? { t: k * (OP / 4), s: [...s, 100], h: 1 }
    : { t: OP, s: [...s, 100] }));

  const layers = [{
    ddd: 0, ind: 1, ty: 4, nm: "outline", sr: 1, ao: 0, ip: 0, op: OP, st: 0, bm: 0,
    ks: {
      o: { a: 0, k: 100 }, r: { a: 0, k: 0 },
      p: { a: 0, k: [r3(cx), r3(cy), 0] },
      a: { a: 0, k: [r3(o.width / 2), r3(o.height / 2), 0] },
      s: { a: 1, k: scaleKeys }
    },
    shapes: [{
      ty: "gr", nm: "Vector 1",
      it: [
        { ty: "sh", nm: "path", ks: { a: 0, k: svgPathToLottie(o.svgPath.join(" ")) } },
        { ty: "st", nm: "stroke " + o.stroke.variable, c: { a: 0, k: [...hexToRgb(o.stroke.hex), 1] }, o: { a: 0, k: 100 }, w: { a: 0, k: o.stroke.weight }, lc: 2, lj: 2 },
        tr()
      ]
    }]
  }];

  if (L.background) {
    const b = L.background;
    layers.push({
      ddd: 0, ind: 2, ty: 4, nm: "background", sr: 1, ao: 0, ip: 0, op: OP, st: 0, bm: 0,
      ks: staticKs(),
      shapes: [{
        ty: "gr", nm: "Ellipse 2",
        it: [
          { ty: "el", nm: "ellipse", d: 1, p: { a: 0, k: [r3(b.x + b.width / 2), r3(b.y + b.height / 2)] }, s: { a: 0, k: [r3(b.width), r3(b.height)] } },
          { ty: "fl", nm: "fill " + b.fill.variable, c: { a: 0, k: [...hexToRgb(b.fill.hex), 1] }, o: { a: 0, k: 100 }, r: 1 },
          tr()
        ]
      }]
    });
  }

  const name = `button-${Variant.toLowerCase()}-${Breakpoint.toLowerCase()}`;
  const lottie = {
    v: "5.7.4", fr: FR, ip: 0, op: OP, w: Math.round(v.width), h: Math.round(v.height),
    nm: name, ddd: 0, assets: [], layers,
    markers: [{ tm: 0, cm: "idle — кадр 0 = вигляд 1:1 з Figma", dr: 0 }],
    meta: { g: "Zadzerkalya build-lottie.js", a: "Figma 6102:16846 / " + v.nodeId }
  };
  const file = path.join(__dirname, name + ".json");
  fs.writeFileSync(file, JSON.stringify(lottie));
  out[name] = { file: path.relative(root, file).split(path.sep).join("/"), w: lottie.w, h: lottie.h, bytes: fs.statSync(file).size };
}
console.log(out);
