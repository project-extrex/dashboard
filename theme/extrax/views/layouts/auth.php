<?php
/**
 * Auth Layout Template
 * This template provides the base structure for authentication pages
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Authentication' ?> - Dashboard</title>
    <?php \App\Helper\Theme::renderAssets('css'); ?>
</head>
<body class="auth-page <?= $pageClass ?? '' ?>">
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <img src="<?= \App\Helper\Theme::getAssetUrl('images/logo.png') ?>" alt="Logo">
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php \App\Helper\Theme::renderContent(); ?>
        </div>
    </div>
    <?php \App\Helper\Theme::renderAssets('js'); ?>
</body>
</html>
