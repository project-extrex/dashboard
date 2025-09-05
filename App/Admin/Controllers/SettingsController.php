<?php
namespace App\Admin\Controllers;

class SettingsController extends AdminBaseController {
    public function index(): void {
        $this->render('settings/index', [
            'title' => 'Settings',
            'settings' => $this->getSettings()
        ]);
    }

    public function update(): void {
        // TODO: Implement settings update
        // This would use the get_option/set_option functions we created
    }

    private function getSettings(): array {
        $settings = [];
        $repo = $this->container->getSettingsRepository();
        foreach ($repo->findAll() as $setting) {
            $settings[$setting->getKey()] = $setting->getValue();
        }
        return $settings;
    }
}
