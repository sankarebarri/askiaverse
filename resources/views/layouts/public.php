<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Askiaverse' ?></title>
    <link rel="stylesheet" href="/assets/app.css">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <?php include __DIR__ . '/../components/public/header.php'; ?>
    
    <!-- Main Content -->
    <main class="container py-8">
        <?= $content ?>
    </main>

    <!-- Core JavaScript -->
    <script src="/assets/js/public/core.js"></script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inline_js)): ?>
        <script>
            <?= $inline_js ?>
        </script>
    <?php endif; ?>
</body>
</html> 