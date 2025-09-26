<?php

namespace App\Core;

class AdminSidebar
{
    private static array $menus = [];
    private static string $activeMenu = '';
    private static string $activeSubmenu = '';

    public static function addMenu(array $menu): void
    {
        if (isset($menu['slug'])) {
            $slug = $menu['slug'];
            $title = $menu['title'] ?? '';
            $icon = $menu['icon'] ?? '';
            $callback = $menu['callback'] ?? null;
            $url = $menu['url'] ?? null;
            $submenus = $menu['submenus'] ?? [];
            $position = $menu['position'] ?? PHP_INT_MAX;  // default = bottom
        } else {
            // Indexed format: [slug, title, icon, callback, submenus, position]
            $slug = $menu[0] ?? '';
            $title = $menu[1] ?? '';
            $icon = $menu[2] ?? '';
            $callback = $menu[3] ?? null;
            $submenus = $menu[4] ?? [];
            $url = null;
            $position = $menu[5] ?? PHP_INT_MAX;
        }

        if (empty($slug) || empty($title)) {
            throw new \InvalidArgumentException('Menu slug and title are required');
        }

        $isExternal = !empty($url) || isset($url);
        $finalUrl = $url ?: ($isExternal ? $callback : null);

        self::$menus[$slug] = [
            'title' => $title,
            'icon' => $icon,
            'callback' => $callback,
            'is_external' => $isExternal,
            'url' => $finalUrl,
            'submenus' => [],
            'position' => $position,
        ];

        foreach ($submenus as $subSlug => $submenuData) {
            if (is_array($submenuData)) {
                self::$menus[$slug]['submenus'][$subSlug] = [
                    'title' => $submenuData['title'],
                    'callback' => $submenuData['callback'] ?? null,
                    'is_external' => isset($submenuData['url']) ?? is_string($submenuData['callback']) &&
                        (filter_var($submenuData['callback'], FILTER_VALIDATE_URL) || str_contains($submenuData['callback'], '.php')),
                    'url' => $submenuData['url'] ?? null,
                    'position' => $submenuData['position'] ?? PHP_INT_MAX,
                ];
            } else {
                self::$menus[$slug]['submenus'][$subSlug] = [
                    'title' => $submenuData,
                    'callback' => null,
                    'is_external' => false,
                    'position' => PHP_INT_MAX,
                ];
            }
        }
    }

    public static function setActive(string $menuSlug, string $submenuSlug = ''): void
    {
        self::$activeMenu = $menuSlug;
        self::$activeSubmenu = $submenuSlug;
    }

    public static function renderSidebar(): string
    {
        $styles = self::getStyles();
        $script = self::getScript();
        $menuHtml = self::renderMenus();

        return <<<HTML
            {$styles}
            <div id="wp-admin-sidebar-overlay" class="wp-admin-sidebar-overlay"></div>
            <nav id="wp-admin-sidebar" class="wp-admin-sidebar">
                <div class="wp-admin-sidebar-header">
                    <div class="wp-admin-logo">
                         <i class="fas fa-e" style="color: #00a0d2; font-size: 24px;">xtrex Admin</i>
                    </div>
                </div>
                <ul class="wp-admin-menu">
                    {$menuHtml}
                </ul>
            </nav>
            <div class="w-screen shadow rounded-bl rounded-br h-15.5 bg-gray-800 flex">
               <!-- <div>
                    <i class="fas fa-e text-3xl mx-5 text-blue-500 px-3 py-1 rounded-xl bg-gray-300 relative"></i>
                </div> -->
                <div class="relative flex items-center justify-center">
                    <a href="/" class="group relative">
                    <i class="fa fa-home text-white text-2xl align-middle m-1"></i>

                    <!-- Tooltip -->
                      <span
              class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 whitespace-nowrap 
             rounded-lg bg-gray-800 text-white text-sm px-2 py-1 shadow-lg opacity-0 scale-90 
             group-hover:opacity-100 group-hover:scale-100 group-hover:translate-y-0 
             transform transition-all duration-200 ease-out pointer-events-none">
                Visit site
               <!-- Tooltip Arrow -->
               <span class="absolute top-full left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45"></span>
              </span>
              </a>
              </div>
             <button id="wp-admin-sidebar-toggle" class="wp-admin-sidebar-toggle -top-5 text-xl m-1">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            {$script}
            HTML;
    }

    public static function renderPage($adminPath = null): string
    {
        $page = $_GET['page'] ?? '';
        $tab = $_GET['tab'] ?? '';

        // Handle case where no page is specified
        if (empty($page)) {
            if (isset($adminPath) && file_exists($adminPath)) {
                ob_start();
                include $adminPath;
                $content = ob_get_clean();
                return '<div class="wp-admin-content">' . $content . '</div>';
            } else {
                return '<div class="wp-admin-content"><p>404 Page not found</p></div>';
            }
        }

        // Check if page exists in menus
        if (!isset(self::$menus[$page])) {
            return '<div class="wp-admin-content"><p>Page not found.</p></div>';
        }

        $menu = self::$menus[$page];

        // Handle submenu
        if (!empty($tab) && isset($menu['submenus'][$tab])) {
            $submenu = $menu['submenus'][$tab];
            if (is_callable($submenu['callback'])) {
                ob_start();
                call_user_func($submenu['callback']);
                $content = ob_get_clean();
                return '<div class="wp-admin-content">' . $content . '</div>';
            }
        }

        // Handle main menu
        if (is_callable($menu['callback'])) {
            ob_start();
            call_user_func($menu['callback']);
            $content = ob_get_clean();
            return '<div class="wp-admin-content">' . $content . '</div>';
        }

        return '<div class="wp-admin-content"><p>No content available for this page.</p></div>';
    }

    private static function renderMenus(): string
    {
        // Sort menus by position
        $menus = self::$menus;
        uasort($menus, function ($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        $html = '';
        foreach ($menus as $slug => $menu) {
            $isActive = self::$activeMenu === $slug;
            $activeClass = $isActive ? ' wp-menu-active' : '';
            $hasSubmenus = !empty($menu['submenus']);
            $expandedClass = $isActive && $hasSubmenus ? ' wp-menu-expanded' : '';

            $icon = $menu['icon'] ? "<i class='fas {$menu['icon']}'></i>" : '';
            $arrow = $hasSubmenus ? "<i class='fas fa-chevron-down wp-menu-arrow'></i>" : '';

            $href = '#';
            if ($menu['is_external']) {
                $href = $menu['url'];
            } elseif (!$hasSubmenus) {
                $href = "/admin/{$slug}";
            }

            $html .= "<li class='wp-menu-item{$activeClass}{$expandedClass}' data-menu='{$slug}'>";
            $html .= "<a href='{$href}' class='wp-menu-link'>";
            $html .= "<span class='wp-menu-icon'>{$icon}</span>";
            $html .= "<span class='wp-menu-text'>{$menu['title']}</span>";
            $html .= $arrow;
            $html .= '</a>';

            if ($hasSubmenus) {
                $submenus = $menu['submenus'];
                uasort($submenus, function ($a, $b) {
                    return $a['position'] <=> $b['position'];
                });

                $html .= "<ul class='wp-submenu'>";
                foreach ($submenus as $subSlug => $submenu) {
                    $subActiveClass = (self::$activeMenu === $slug && self::$activeSubmenu === $subSlug) ? ' wp-submenu-active' : '';

                    $subHref = '#';
                    if (isset($submenu['url'])) {
                        $subHref = $submenu['url'];
                    } elseif ($submenu['is_external']) {
                        $subHref = $submenu['callback'];
                    } else {
                        $subHref = "/admin/{$slug}/{$subSlug}";
                    }

                    $html .= "<li class='wp-submenu-item{$subActiveClass}' data-submenu='{$subSlug}'>";
                    $html .= "<a href='{$subHref}' class='wp-submenu-link'>{$submenu['title']}</a>";
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    private static function getStyles(): string
    {
        return <<<CSS
            <style>
            * {
                box-sizing: border-box;
            }

            .wp-admin-sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .wp-admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 50vw;
                height: 100vh;
                background: #23282d;
                z-index: 1000;
                overflow-y: auto;
                transition: all 0.3s ease;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }

            .wp-admin-sidebar-header {
                padding: 10px 15px;
                border-bottom: 1px solid #32373c;
                background: #23282d;
            }

            .wp-admin-logo {
                text-align: center;
            }

            .wp-admin-menu {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .wp-menu-item {
                border-bottom: 1px solid #32373c;
            }

            .wp-menu-link {
                display: flex;
                align-items: center;
                padding: 10px 12px;
                color: #eee;
                text-decoration: none;
                transition: all 0.2s ease;
                position: relative;
            }

            .wp-menu-link:hover {
                background: #32373c;
                color: #00a0d2;
            }

            .wp-menu-active .wp-menu-link {
                background: #007cba;
                color: #fff;
            }

            .wp-menu-icon {
                width: 20px;
                margin-right: 8px;
                text-align: center;
                font-size: 16px;
            }

            .wp-menu-text {
                flex: 1;
                font-size: 13px;
                font-weight: 400;
            }

            .wp-menu-arrow {
                font-size: 10px;
                transition: transform 0.2s ease;
                margin-left: auto;
            }

            .wp-menu-expanded .wp-menu-arrow {
                transform: rotate(180deg);
            }

            .wp-submenu {
                list-style: none;
                margin: 0;
                padding: 0;
                background: #32373c;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .wp-menu-expanded .wp-submenu {
                max-height: 500px;
            }

            .wp-submenu-item {
                border-bottom: 1px solid #46494d;
            }

            .wp-submenu-item:last-child {
                border-bottom: none;
            }

            .wp-submenu-link {
                display: block;
                padding: 10px 12px 10px 45px;
                color: #eee;
                text-decoration: none;
                font-size: 12px;
                transition: all 0.2s ease;
            }

            .wp-submenu-link:hover {
                background: #46494d;
                color: #00a0d2;
            }

            .wp-submenu-active .wp-submenu-link {
                background: #007cba;
                color: #fff;
            }

            .wp-admin-sidebar-toggle {
                display: none;
                position: fixed;
                top: 0;
                left: auto;
                right: 0;
                width: 40px;
                height: 40px;
                background: #23282d;
                border: none;
                border-radius: 3px;
                z-index: 1001;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .wp-admin-sidebar-toggle span {
                width: 20px;
                height: 2px;
                background: #eee;
                transition: all 0.3s ease;
                border-radius: 1px;
            }

            .wp-admin-sidebar-toggle:hover {
                background: #32373c;
            }

            .wp-admin-sidebar-toggle.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }

            .wp-admin-sidebar-toggle.active span:nth-child(2) {
                opacity: 0;
            }

            .wp-admin-sidebar-toggle.active span:nth-child(3) {
                transform: rotate(-45deg) translate(7px, -6px);
            }

            .wp-admin-content {
                padding: 20px;
                background: #f1f1f1;
                min-height: 100vh;
            }

            .wp-admin-content h1 {
                margin-top: 0;
                color: #23282d;
                font-size: 23px;
                font-weight: 400;
                margin-bottom: 20px;
            }

            .wp-admin-content h2 {
                color: #23282d;
                font-size: 18px;
                font-weight: 600;
                margin-bottom: 15px;
            }

            .wp-admin-content p {
                color: #555;
                line-height: 1.6;
                margin-bottom: 15px;
            }

            @media (max-width: 768px) {
                .wp-admin-sidebar {
                    transform: translateX(-100%);
                }
                
                .wp-admin-sidebar.open {
                    transform: translateX(0);
                }
                
                .wp-admin-sidebar-toggle {
                    display: flex;
                }
                
                .wp-admin-sidebar-overlay.active {
                    display: block;
                }
                
                .wp-admin-content {
                    margin-left: 0;
                }
            }

            @media (min-width: 769px) {
                .wp-admin-content {
                    margin-left: 160px;
                }
            }

            .wp-admin-sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .wp-admin-sidebar::-webkit-scrollbar-track {
                background: #23282d;
            }

            .wp-admin-sidebar::-webkit-scrollbar-thumb {
                background: #555;
                border-radius: 3px;
            }

            .wp-admin-sidebar::-webkit-scrollbar-thumb:hover {
                background: #777;
            }
            </style>
            CSS;
    }

    private static function getScript(): string
    {
        return <<<JS
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('wp-admin-sidebar');
                const toggle = document.getElementById('wp-admin-sidebar-toggle');
                const overlay = document.getElementById('wp-admin-sidebar-overlay');
                const menuItems = document.querySelectorAll('.wp-menu-item');
                
                // Sidebar toggle functionality
                if (toggle && sidebar && overlay) {
                    toggle.addEventListener('click', function() {
                        sidebar.classList.toggle('open');
                        toggle.classList.toggle('active');
                        overlay.classList.toggle('active');
                    });
                    
                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('open');
                        toggle.classList.remove('active');
                        overlay.classList.remove('active');
                    });
                }
                
                // Menu item click handlers
                menuItems.forEach(function(menuItem) {
                    const menuLink = menuItem.querySelector('.wp-menu-link');
                    const submenu = menuItem.querySelector('.wp-submenu');
                    
                    if (menuLink && submenu) {
                        // Only prevent default if it's not an external link
                        if (menuLink.getAttribute('href') === '#') {
                            menuLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                
                                // Close other expanded menus
                                menuItems.forEach(function(otherItem) {
                                    if (otherItem !== menuItem) {
                                        otherItem.classList.remove('wp-menu-expanded');
                                    }
                                });
                                
                                // Toggle current menu
                                menuItem.classList.toggle('wp-menu-expanded');
                            });
                        }
                    }
                    
                    // Auto-expand menu if it contains active submenu
                    const activeSubmenu = menuItem.querySelector('.wp-submenu-active');
                    if (activeSubmenu) {
                        menuItem.classList.add('wp-menu-expanded');
                    }
                });
            });
            </script>
            JS;
    }

    public static function getMenu($slug): array | null
    {
        return self::$menus[$slug];
    }
}

// ============================================================================
// USAGE EXAMPLE WITH ARRAY PARAMETER
// ============================================================================

/*
 * // Add Dashboard page
 * AdminSidebar::addMenu([
 *     'slug' => 'dashboard',
 *     'title' => 'Dashboard',
 *     'icon' => 'fa-tachometer-alt',
 *     'callback' => function() {
 *         echo '<h1>Welcome to Dashboard</h1>';
 *         echo '<p>This is the main dashboard page. Here you can view system overview and statistics.</p>';
 *         echo '<div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
 *         echo '<h2>Quick Stats</h2>';
 *         echo '<p>Users: 1,234 | Posts: 5,678 | Comments: 9,012</p>';
 *         echo '</div>';
 *     }
 * ]);
 *
 * // Add Settings menu with submenus
 * AdminSidebar::addMenu([
 *     'slug' => 'settings',
 *     'title' => 'Settings',
 *     'icon' => 'fa-cog',
 *     'callback' => null,
 *     'submenus' => [
 *         'general' => [
 *             'title' => 'General',
 *             'callback' => function() {
 *                 echo '<h1>General Settings</h1>';
 *                 echo '<p>Configure general application settings here.</p>';
 *                 echo '<form style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
 *                 echo '<h2>Site Configuration</h2>';
 *                 echo '<p><label>Site Title: <input type="text" value="My Application" style="margin-left: 10px; padding: 5px;"></label></p>';
 *                 echo '<p><label>Admin Email: <input type="email" value="admin@example.com" style="margin-left: 10px; padding: 5px;"></label></p>';
 *                 echo '<p><button type="submit" style="background: #007cba; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer;">Save Changes</button></p>';
 *                 echo '</form>';
 *             }
 *         ],
 *         'profile' => [
 *             'title' => 'Profile',
 *             'callback' => function() {
 *                 echo '<h1>Profile Settings</h1>';
 *                 echo '<p>Manage your personal profile settings and preferences.</p>';
 *                 echo '<form style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
 *                 echo '<h2>User Profile</h2>';
 *                 echo '<p><label>Display Name: <input type="text" value="John Doe" style="margin-left: 10px; padding: 5px;"></label></p>';
 *                 echo '<p><label>Bio: <textarea style="margin-left: 10px; padding: 5px; width: 300px; height: 80px;">Web developer and designer...</textarea></label></p>';
 *                 echo '<p><button type="submit" style="background: #007cba; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer;">Update Profile</button></p>';
 *                 echo '</form>';
 *             }
 *         ]
 *     ]
 * ]);
 *
 * // Add Users page linking to external URL
 * AdminSidebar::addMenu([
 *     'slug' => 'users',
 *     'title' => 'Users',
 *     'icon' => 'fa-users',
 *     'url' => '/users.php'
 * ]);
 *
 * // Add external link menu
 * AdminSidebar::addMenu([
 *     'slug' => 'docs',
 *     'title' => 'Documentation',
 *     'icon' => 'fa-book',
 *     'url' => 'https://example.com/docs'
 * ]);
 *
 * // Set active menu based on current page
 * $currentPage = $_GET['page'] ?? 'dashboard';
 * $currentTab = $_GET['tab'] ?? '';
 * AdminSidebar::setActive($currentPage, $currentTab);
 *
 * ?>
 * <!DOCTYPE html>
 * <html lang="en">
 * <head>
 *     <meta charset="UTF-8">
 *     <meta name="viewport" content="width=device-width, initial-scale=1.0">
 *     <title>Admin Panel</title>
 *     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 * </head>
 * <body>
 *     <?php echo AdminSidebar::renderSidebar(); ?>
 *
 *     <?php echo AdminSidebar::renderPage(); ?>
 * </body>
 * </html>
 */
