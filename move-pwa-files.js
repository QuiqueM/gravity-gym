import fs from 'fs-extra';
import path from 'path';

const publicDir = 'public';
const buildDir = path.join(publicDir, 'build');

const filesToMove = [
    'manifest.json',
    'sw.js',
    // Agrega aquí cualquier otro archivo de PWA que necesites mover, como los íconos si también están en 'build'
    // 'icon-196x196.png', 
    // 'icon-512x512.png',
];

async function moveFiles() {
    try {
        for (const file of filesToMove) {
            const sourcePath = path.join(buildDir, file);
            const destPath = path.join(publicDir, file);

            if (await fs.pathExists(sourcePath)) {
                await fs.move(sourcePath, destPath, { overwrite: true });
                console.log(`✅ Movido: ${sourcePath} -> ${destPath}`);
            } else {
                console.warn(`⚠️ Archivo no encontrado en build, no se movió: ${sourcePath}`);
            }
        }
        console.log('🚀 Archivos PWA movidos exitosamente a la carpeta public.');
    } catch (error) {
        console.error('❌ Error moviendo los archivos PWA:', error);
    }
}

moveFiles();