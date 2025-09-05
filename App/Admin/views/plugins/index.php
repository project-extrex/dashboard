<?php
/**
 * Admin Plugin Management View
 */

use App\Helper\Theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Plugin Management' ?> - Admin Dashboard</title>
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    
    <main class="admin-main">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="plugins-page">
                <div class="page-header">
                    <h1>Manage Plugins</h1>
                    <div class="page-actions">
                        <button class="btn btn-primary" onclick="window.location.href='/admin/plugins/browse'">
                            <i class="fas fa-plus"></i> Browse Plugins
                        </button>
                    </div>
                </div>

                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <div class="plugins-grid">
                    <?php foreach ($plugins as $plugin): ?>
                        <div class="plugin-card">
                            <div class="plugin-icon">
                                <?php if ($plugin->hasIcon()): ?>
                                    <img src="<?= $plugin->getIconUrl() ?>" alt="<?= htmlspecialchars($plugin->getName()) ?>">
                                <?php else: ?>
                                    <i class="fas fa-puzzle-piece"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="plugin-info">
                                <h3 class="plugin-name"><?= htmlspecialchars($plugin->getName()) ?></h3>
                                <p class="plugin-description"><?= htmlspecialchars($plugin->getDescription()) ?></p>
                                
                                <div class="plugin-meta">
                                    <span class="version">v<?= htmlspecialchars($plugin->getVersion()) ?></span>
                                    <span class="author">by <?= htmlspecialchars($plugin->getAuthor()) ?></span>
                                </div>
                            </div>

                            <div class="plugin-actions">
                                <?php if ($plugin->isActive()): ?>
                                    <?php if ($plugin->hasConfiguration()): ?>
                                        <a href="/admin/plugins/configure/<?= $plugin->getId() ?>" 
                                           class="btn btn-secondary">
                                            <i class="fas fa-cog"></i> Configure
                                        </a>
                                    <?php endif; ?>
                                    
                                    <form action="/admin/plugins/deactivate/<?= $plugin->getId() ?>" 
                                          method="POST" 
                                          class="inline-form">
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-power-off"></i> Deactivate
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="/admin/plugins/activate/<?= $plugin->getId() ?>" 
                                          method="POST" 
                                          class="inline-form">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-power-off"></i> Activate
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($plugins)): ?>
                        <div class="no-plugins">
                            <i class="fas fa-puzzle-piece"></i>
                            <h3>No Plugins Found</h3>
                            <p>Start by browsing available plugins or upload a new one.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="/admin/assets/js/admin.js"></script>
</body>
</html>
