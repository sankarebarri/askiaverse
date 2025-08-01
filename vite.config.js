import { defineConfig } from 'vite';

export default defineConfig({
  // NOTE : Nous n'utilisons plus l'option 'root'. 
  // Vite utilisera la racine du projet par défaut.
  build: {
    // Le dossier où les fichiers compilés seront placés (relatif à la racine du projet)
    outDir: 'public/assets',
    // Vider le dossier de sortie avant chaque build
    emptyOutDir: true,
    // Générer un manifest.json pour l'intégration avec le backend PHP
    manifest: true,
    rollupOptions: {
      // Le chemin d'entrée est maintenant relatif à la racine du projet
      input: 'resources/css/app.css',
      // Configurer la sortie pour éviter la structure nested assets/
      output: {
        assetFileNames: (assetInfo) => {
          // Placer les assets directement dans le dossier assets sans sous-dossier
          return assetInfo.name;
        }
      }
    },
  },
});