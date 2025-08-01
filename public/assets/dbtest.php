<?php
// public/dbtest.php

// Charger le bootstrap (retourne un objet PDO)
$pdo = require __DIR__ . '/../src/bootstrap.php';

// Exécuter une requête simple pour vérifier la connexion
$stmt = $pdo->query('SELECT DATABASE() AS db');
$row = $stmt->fetch();

echo '<p>✅ Connecté à la base : <strong>' . htmlspecialchars($row['db']) . '</strong></p>';
