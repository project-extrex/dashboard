<?php
/**
 * Admin Layout
 */
?>
<?php use App\Helper\Theme; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    
    <!-- Core Admin Styles -->
    <link rel="stylesheet" href="<?= Theme::getPublicPath('css/admin.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php Theme::renderHead(); ?>
</head>
<body class="admin-page">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <img src="<?= Theme::asset('images/logo.png') ?>" alt="Dashboard Logo">
                </div>
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item<?= $currentMenu === 'dashboard' ? ' active' : '' ?>">
                        <a href="<?= Theme::url('admin') ?>">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item<?= $currentMenu === 'users' ? ' active' : '' ?>">
                        <a href="<?= Theme::url('admin/users') ?>">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item<?= $currentMenu === 'themes' ? ' active' : '' ?>">
                        <a href="<?= Theme::url('admin/themes') ?>">
                            <i class="fas fa-paint-brush"></i>
                            <span>Themes</span>
                        </a>
                    </li>
                    <li class="nav-item<?= $currentMenu === 'plugins' ? ' active' : '' ?>">
                        <a href="<?= Theme::url('admin/plugins') ?>">
                            <i class="fas fa-puzzle-piece"></i>
                            <span>Plugins</span>
                        </a>
                    </li>
                    <li class="nav-item<?= $currentMenu === 'settings' ? ' active' : '' ?>">
                        <a href="<?= Theme::url('admin/settings') ?>">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <h1 class="page-title"><?= $title ?? 'Dashboard' ?></h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <img src="<?= $user->getAvatarUrl() ?? Theme::asset('images/default-avatar.png') ?>" 
                             alt="<?= htmlspecialchars($user->getUsername()) ?>" 
                             class="user-avatar">
                        <div class="user-dropdown">
                            <span class="username"><?= htmlspecialchars($user->getUsername()) ?></span>
                            <a href="<?= Theme::url('admin/profile') ?>" class="dropdown-item">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a href="<?= Theme::url('auth/logout') ?>" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <?php Theme::renderView(); ?>
            </div>
        </main>
    </div>

    <!-- Core Admin Scripts -->
    <script src="<?= Theme::asset('js/admin.js') ?>"></script>
    <?php Theme::renderScripts(); ?>
</body>
</html>
