<?php
/**
 * Admin Dashboard Layout
 */
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    
    <!-- Modern CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="/admin/assets/css/admin.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <?php if (isset($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link href="<?= $style ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="/admin/assets/images/logo.svg" alt="Dashboard Logo" class="brand-logo">
                <span class="brand-text">Dashboard</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-section-title">Main</span>
                <ul>
                    <li class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <a href="<?= url('admin') ?>">
                            <i class="mdi mdi-view-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                        <a href="<?= url('admin/users') ?>">
                            <i class="mdi mdi-account-group"></i>
                            <span>Users</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Customization</span>
                <ul>
                    <li class="<?= $currentPage === 'themes' ? 'active' : '' ?>">
                        <a href="<?= url('admin/themes') ?>">
                            <i class="mdi mdi-palette"></i>
                            <span>Themes</span>
                        </a>
                    </li>
                    <li class="<?= $currentPage === 'plugins' ? 'active' : '' ?>">
                        <a href="<?= url('admin/plugins') ?>">
                            <i class="mdi mdi-puzzle"></i>
                            <span>Plugins</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Settings</span>
                <ul>
                    <li class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                        <a href="<?= url('admin/settings') ?>">
                            <i class="mdi mdi-cog"></i>
                            <span>General</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-menu">
                <img src="<?= $user->getAvatarUrl() ?? '/admin/assets/images/default-avatar.png' ?>" 
                     alt="User Avatar" 
                     class="user-avatar">
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($user->getUsername()) ?></span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-menu-dropdown">
                    <a href="<?= url('admin/profile') ?>" class="menu-item">
                        <i class="mdi mdi-account"></i>
                        <span>Profile</span>
                    </a>
                    <a href="<?= url('auth/logout') ?>" class="menu-item">
                        <i class="mdi mdi-logout"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="content-header">
            <div class="header-left">
                <?php if (isset($breadcrumbs)): ?>
                    <nav class="breadcrumb">
                        <?php foreach ($breadcrumbs as $label => $link): ?>
                            <?php if ($link): ?>
                                <a href="<?= $link ?>"><?= htmlspecialchars($label) ?></a>
                                <i class="mdi mdi-chevron-right"></i>
                            <?php else: ?>
                                <span><?= htmlspecialchars($label) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </nav>
                <?php endif; ?>
            </div>
            
            <div class="header-right">
                <div class="search-box">
                    <i class="mdi mdi-magnify"></i>
                    <input type="text" placeholder="Search...">
                </div>
                
                <div class="header-actions">
                    <button class="action-button" id="notificationsToggle">
                        <i class="mdi mdi-bell"></i>
                        <?php if ($unreadNotifications > 0): ?>
                            <span class="notification-badge"><?= $unreadNotifications ?></span>
                        <?php endif; ?>
                    </button>
                    <button class="action-button" id="themeToggle">
                        <i class="mdi mdi-weather-night"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                    <i class="mdi mdi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle' : 'alert' ?>"></i>
                    <span><?= htmlspecialchars($_SESSION['flash']['message']) ?></span>
                    <button class="alert-close"><i class="mdi mdi-close"></i></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <div class="content">
                <?= $content ?>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/admin/assets/js/admin.js"></script>
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
