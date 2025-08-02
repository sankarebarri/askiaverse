<?php
// create-tables.php - Script pour créer les tables de la base de données

echo "🗄️  Création des tables de la base de données...\n";
echo "=============================================\n\n";

// Charger la configuration
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Shared/Database.php';
require_once __DIR__ . '/src/Shared/Config.php';

try {
    $db = \Shared\Database::getInstance();
    echo "✅ Connexion à la base de données établie\n\n";

    // Lire le fichier SQL
    $sqlFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($sqlFile)) {
        echo "❌ Fichier schema.sql non trouvé\n";
        exit(1);
    }

    $sql = file_get_contents($sqlFile);
    
    // Diviser les requêtes SQL
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "📝 Exécution des requêtes SQL...\n";
    
    foreach ($queries as $query) {
        if (empty($query)) continue;
        
        try {
            $db->query($query);
            echo "✅ Requête exécutée avec succès\n";
        } catch (Exception $e) {
            echo "⚠️  Erreur lors de l'exécution: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Tables créées avec succès!\n";
    echo "📝 Vous pouvez maintenant exécuter: php setup-backend.php\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📝 Assurez-vous que:\n";
    echo "   - MySQL est démarré\n";
    echo "   - La base de données 'askiaverse' existe\n";
    echo "   - Les paramètres de connexion sont corrects dans .env\n";
    exit(1);
} 