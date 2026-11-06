const sharp = require('sharp');

sharp(process.argv[2], { limitInputPixels: 4096 * 4096 })
    .autoOrient()
    .resize({ width: 960, height: 640, fit: 'inside', withoutEnlargement: true })
    .png()
    .toBuffer()
    .then(image => process.stdout.write(image))
    .catch(() => {
        process.exitCode = 2;
    });
