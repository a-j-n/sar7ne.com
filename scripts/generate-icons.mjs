import fs from 'fs/promises';
import path from 'path';
import sharp from 'sharp';
import pngToIco from 'png-to-ico';

const projectRoot = new URL('../', import.meta.url).pathname;
const publicDir = path.join(projectRoot, 'public');
const srcFile = path.join(publicDir, 'Sar7ne-logo.png');
const outDir = publicDir; // write icons directly to public

const sizes = [16, 32, 48, 64, 96, 128, 144, 192, 256, 384, 512];
const appleSize = 180; // apple touch icon

async function fileExists(p) {
  try {
    await fs.access(p);
    return true;
  } catch {
    return false;
  }
}

async function createPngIcons() {
  if (!await fileExists(srcFile)) {
    throw new Error(`Source file not found: ${srcFile}`);
  }

  const created = [];

  // create individual PNGs
  for (const size of sizes) {
    const out = path.join(outDir, `icon-${size}x${size}.png`);
    await sharp(srcFile)
      .resize(size, size, { fit: 'cover' })
      .png({ quality: 90 })
      .toFile(out);
    created.push(out);
    console.log('created', out);
  }

  // apple touch icon
  const appleOut = path.join(outDir, `apple-touch-icon.png`);
  await sharp(srcFile).resize(appleSize, appleSize, { fit: 'cover' }).png({ quality: 90 }).toFile(appleOut);
  created.push(appleOut);
  console.log('created', appleOut);

  // ensure 512 exists for PWA
  const pwaSize = 512;
  const pwaOut = path.join(outDir, `icon-${pwaSize}x${pwaSize}.png`);
  if (!created.includes(pwaOut)) {
    await sharp(srcFile).resize(pwaSize, pwaSize, { fit: 'cover' }).png({ quality: 90 }).toFile(pwaOut);
    created.push(pwaOut);
    console.log('created', pwaOut);
  }

  return created;
}

async function createFaviconIco() {
  // png-to-ico expects buffers for multiple pngs (16,32,48 typically)
  const icoSizes = [16, 32, 48];
  const pngBuffers = [];
  for (const s of icoSizes) {
    const file = path.join(outDir, `icon-${s}x${s}.png`);
    if (!await fileExists(file)) {
      // generate temporarily
      const buf = await sharp(srcFile).resize(s, s, { fit: 'cover' }).png().toBuffer();
      pngBuffers.push(buf);
    } else {
      pngBuffers.push(await fs.readFile(file));
    }
  }

  const icoBuffer = await pngToIco(pngBuffers);
  const icoPath = path.join(outDir, 'favicon.ico');
  await fs.writeFile(icoPath, icoBuffer);
  console.log('created', icoPath);
  return icoPath;
}

async function run() {
  try {
    console.log('source:', srcFile);
    const createdPngs = await createPngIcons();
    const ico = await createFaviconIco();

    console.log('\nAll icons created:');
    for (const f of createdPngs) console.log(' -', path.relative(projectRoot, f));
    console.log(' -', path.relative(projectRoot, ico));

    // Optionally, update manifest.json if present (best-effort)
    const manifestPath = path.join(publicDir, 'manifest.json');
    if (await fileExists(manifestPath)) {
      try {
        const raw = await fs.readFile(manifestPath, 'utf8');
        const manifest = JSON.parse(raw);
        manifest.icons = sizes.map(s => ({ src: `icon-${s}x${s}.png`, sizes: `${s}x${s}`, type: 'image/png' }));
        manifest.icons.push({ src: 'apple-touch-icon.png', sizes: `${appleSize}x${appleSize}`, type: 'image/png' });
        await fs.writeFile(manifestPath, JSON.stringify(manifest, null, 2), 'utf8');
        console.log('updated', path.relative(projectRoot, manifestPath));
      } catch (err) {
        console.warn('failed to update manifest.json:', err.message);
      }
    }

    console.log('\nDone.');
  } catch (err) {
    console.error('Error:', err.message);
    process.exitCode = 1;
  }
}

run();
