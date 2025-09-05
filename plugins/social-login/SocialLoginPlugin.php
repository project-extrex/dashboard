<?php
use App\Core\Plugin\BasePlugin;
use App\Helper\Theme;

class SocialLoginPlugin extends BasePlugin {
    public function onActivate(): void {
        // Add login buttons to the auth page
        $this->addHook('auth_login_buttons', [$this, 'renderLoginButtons']);
        
        // Add settings page to admin
        $this->addAdminMenu(
            'Social Login', 
            'social-login', 
            [$this, 'renderSettings'],
            'fas fa-share-alt'
        );
    }

    public function onDeactivate(): void {
        // Cleanup if needed
    }

    public function renderLoginButtons(): string {
        $settings = $this->getSettings();
        $enabledProviders = $settings['enabled_providers'] ?? ['facebook', 'google'];
        $output = '<div class="social-login-buttons">';
        
        foreach ($enabledProviders as $provider) {
            switch ($provider) {
                case 'facebook':
                    if (!empty($settings['facebook_app_id'])) {
                        $output .= $this->renderFacebookButton();
                    }
                    break;
                case 'google':
                    if (!empty($settings['google_client_id'])) {
                        $output .= $this->renderGoogleButton();
                    }
                    break;
                case 'github':
                    if (!empty($settings['github_client_id'])) {
                        $output .= $this->renderGithubButton();
                    }
                    break;
            }
        }
        
        $output .= '</div>';
        return $output;
    }

    private function renderFacebookButton(): string {
        return '
            <a href="' . Theme::url('auth/social/facebook') . '" class="btn btn-facebook btn-block">
                <i class="fab fa-facebook-f"></i> Continue with Facebook
            </a>';
    }

    private function renderGoogleButton(): string {
        return '
            <a href="' . Theme::url('auth/social/google') . '" class="btn btn-google btn-block">
                <i class="fab fa-google"></i> Continue with Google
            </a>';
    }

    private function renderGithubButton(): string {
        return '
            <a href="' . Theme::url('auth/social/github') . '" class="btn btn-github btn-block">
                <i class="fab fa-github"></i> Continue with GitHub
            </a>';
    }

    public function renderSettings(): void {
        $settings = $this->getSettings();
        Theme::setLayout('layouts/admin');
        Theme::render('plugins/social-login/settings', [
            'title' => 'Social Login Settings',
            'settings' => $settings
        ]);
    }

    private function getSettings(): array {
        $defaultSettings = $this->manifest['settings'];
        $savedSettings = get_option('social_login_settings', []);
        return array_merge($defaultSettings, $savedSettings);
    }
}
