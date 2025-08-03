<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Administration Askiaverse' ?></title>
    <link rel="stylesheet" href="/assets/app.css">
    <link rel="stylesheet" href="/assets/admin.css">
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="admin-container">
        <?php include __DIR__ . '/../components/admin/header.php'; ?>
        
        <main class="admin-main">
            <?php if (isset($page_header)): ?>
                <div class="page-header">
                    <h1><?= $page_header['title'] ?? '' ?></h1>
                    <?php if (isset($page_header['actions'])): ?>
                        <div class="page-actions">
                            <?php foreach ($page_header['actions'] as $action): ?>
                                <a href="<?= $action['url'] ?>" 
                                   class="btn-<?= $action['type'] ?? 'secondary' ?>"
                                   <?= isset($action['id']) ? 'id="' . $action['id'] . '"' : '' ?>>
                                    <?= $action['text'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?= $content ?>
        </main>
    </div>

    <!-- Core Admin JavaScript -->
    <script src="/assets/js/admin/core.js"></script>
    
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