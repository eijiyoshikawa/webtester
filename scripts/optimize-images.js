// Image optimization script: resize + compress all JPEGs in public/images/esco_LP
// Uses sharp (already a Next.js dependency)
const sharp = require("sharp");
const fs = require("fs");
const path = require("path");

const DIR = path.join(process.cwd(), "public/images/esco_LP");

// Max width per slot (height is auto to preserve aspect ratio)
const configs = [
  { pattern: /^esco_top\d+\.jpg$/i, maxWidth: 1920, quality: 80 },
  { pattern: /^hero\.jpg$/i, maxWidth: 1600, quality: 80 },
  { pattern: /^gallery-\d+\.jpg$/i, maxWidth: 1200, quality: 80 },
  { pattern: /^merit-\d+\.jpg$/i, maxWidth: 1200, quality: 80 },
  { pattern: /^message\.jpg$/i, maxWidth: 1200, quality: 80 },
  { pattern: /^ceo\.jpg$/i, maxWidth: 900, quality: 82 },
  { pattern: /^voice-\d+\.jpg$/i, maxWidth: 800, quality: 82 },
  // unused (leftovers)
  { pattern: /^041\.jpg$/i, maxWidth: 1200, quality: 80 },
  { pattern: /^IMG_1672\.(jpg|JPG)$/i, maxWidth: 1200, quality: 80 },
];

(async () => {
  const files = fs.readdirSync(DIR).filter((f) => /\.(jpe?g)$/i.test(f));
  let totalBefore = 0;
  let totalAfter = 0;
  for (const file of files) {
    const full = path.join(DIR, file);
    const config = configs.find((c) => c.pattern.test(file));
    if (!config) {
      console.log(`SKIP (no config): ${file}`);
      continue;
    }
    const before = fs.statSync(full).size;
    totalBefore += before;
    const buf = await sharp(full)
      .rotate() // honor EXIF orientation
      .resize({ width: config.maxWidth, withoutEnlargement: true })
      .jpeg({ quality: config.quality, mozjpeg: true, progressive: true })
      .toBuffer();
    fs.writeFileSync(full, buf);
    const after = buf.length;
    totalAfter += after;
    const saved = ((1 - after / before) * 100).toFixed(0);
    console.log(
      `${file.padEnd(22)}: ${(before / 1024).toFixed(0).padStart(5)}KB -> ${(after / 1024).toFixed(0).padStart(4)}KB  (-${saved}%)`
    );
  }
  console.log(`\nTOTAL: ${(totalBefore / 1024 / 1024).toFixed(2)}MB -> ${(totalAfter / 1024 / 1024).toFixed(2)}MB`);
})();
