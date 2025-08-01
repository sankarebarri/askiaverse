// FICHIER : tailwind.config.js
// C'est le fichier de configuration pour Tailwind CSS.
// Il lui indique quels fichiers surveiller pour trouver les classes CSS.
// À placer à la racine de votre projet.
// ===================================================================
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    // Surveille tous les fichiers PHP dans votre dossier public
    "./public/**/*.php",
    // Nous ajouterons un dossier 'views' plus tard pour nos vues HTML/PHP
    "./resources/views/**/*.php" 
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}