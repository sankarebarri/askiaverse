<?php

// ===================================================================
// FICHIER : src/Shared/Helpers.php
// Ce fichier contiendra des fonctions d'aide globales que nous
// pourrons utiliser n'importe où dans notre application.
// ===================================================================

/**
 * Lit le manifest.json de Vite pour obtenir le chemin correct d'un asset compilé.
 * Cela gère automatiquement le "cache busting" (noms de fichiers avec hash).
 *
 * @param string $entry Le point d'entrée de l'asset tel que défini dans vite.config.js (ex: 'resources/css/app.css')
 * @return string Le chemin d'accès public complet de l'asset.
 * @throws Exception Si le manifest ou l'entrée ne peuvent être trouvés.
 */
function vite_asset(string $entry): string
{
    // Chemin vers le fichier manifest généré par Vite.
    $manifestPath = __DIR__ . '/../../public/assets/.vite/manifest.json';

    // Un cache statique pour éviter de lire le fichier JSON à chaque appel.
    static $manifest = null;

    if ($manifest === null) {
        if (!file_exists($manifestPath)) {
            throw new Exception('Le fichier manifest.json de Vite n\'a pas été trouvé. Avez-vous lancé "npm run build" ?');
        }
        $manifest = json_decode(file_get_contents($manifestPath), true);
    }

    // Vérifie si l'entrée demandée existe dans le manifest.
    if (!isset($manifest[$entry])) {
        throw new Exception("L'entrée Vite '{$entry}' n'a pas été trouvée dans manifest.json.");
    }

    // MODIFIÉ : Version finale, plus simple et plus robuste.
    // On sait que les assets sont servis depuis la racine du site, dans le dossier /assets.
    // Le manifest contient déjà le nom du fichier avec son hash.
    // On retourne simplement le chemin correct.
    // Le fichier dans le manifest contient déjà le préfixe 'assets/', donc on l'utilise directement
    // Cela donne /assets/assets/filename.css, ce qui correspond à la structure réelle
    return '/assets/' . $manifest[$entry]['file'];
}

/**
 * Génère la balise <link> complète pour une feuille de style CSS gérée par Vite.
 *
 * @param string $entry Le point d'entrée CSS.
 * @return string La balise HTML <link> complète.
 */
function vite_css_tag(string $entry): string
{
    try {
        $url = vite_asset($entry);
        return '<link rel="stylesheet" href="' . htmlspecialchars($url) . '">';
    } catch (Exception $e) {
        // En mode développement, on veut voir l'erreur immédiatement.
        // On arrête l'exécution et on affiche un message clair.
        die('<div style="font-family: sans-serif; padding: 2rem; background-color: #fff0f0; border: 2px solid #ff0000; color: #333;">' .
            '<h1>Erreur de l\'asset Vite</h1>' .
            '<p>Impossible de charger l\'asset. Assurez-vous d\'avoir lancé <strong>npm run build</strong>.</p>' .
            '<p><strong>Message d\'erreur :</strong> ' . htmlspecialchars($e->getMessage()) . '</p>' .
            '</div>');
    }
}
