<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Askiaverse</title>
</head>
<body>
    <h1>Test Page</h1>
    <p>Le serveur PHP fonctionne correctement!</p>
    <p>PHP Version: <?= phpversion() ?></p>
    <p>Extensions disponibles:</p>
    <ul>
        <?php foreach (get_loaded_extensions() as $ext): ?>
        <li><?= $ext ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
