<?php

namespace App\Registry;

class Menu {
    private static array $menu = [];

    public static function initial(): void {
        self::$menu["/"] = [
            "url" => "/",
            "title" => "Home",
            "admin" => false,
            "icon" => "fas fa-home",
            "position" => 1
        ];
        self::$menu["/dashboard"] = [
            "url" => "/dashboard",
            "title" => "Dashboard",
            "admin" => false,
            "icon" => "fas fa-gauge",
            "position" => 2
        ];
        self::$menu["/profile"] = [
            "url" => "/profile",
            "title" => "Profile",
            "admin" => false,
            "icon" => "fas fa-user",
            "position" => 3
        ];
        self::$menu["/admin"] = [
            "url" => "/admin",
            "title" => "Admin",
            "admin" => true,
            "icon" => "fas fa-user-gear",
            "position" => 99
        ];
        self::$menu["/logout"] = [
            "url" => "/logout",
            "title" => "Log out",
            "admin" => false,
            "icon" => "fas fa-door-open",
            "position" => 100
        ];
    }

    public static function add(array $menu): void {
        if (!isset($menu['position'])) {
            // Default to very last if no position given
            $menu['position'] = count(self::$menu) + 1;
        }
        self::$menu[$menu['url']] = $menu;
    }

    public static function remove(string $url): void {
        unset(self::$menu[$url]);
    }

    public static function get(): array {
        // Sort by "position" before returning
        $menus = self::$menu;
        uasort($menus, fn($a, $b) => $a['position'] <=> $b['position']);
        return $menus;
    }
}