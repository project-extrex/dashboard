<?php
/**
 * Theme Settings View
 */
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Theme Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Theme::url('admin') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Theme Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] === '1'): ?>
                    Theme activated successfully!
                <?php elseif ($_GET['success'] === 'settings_saved'): ?>
                    Theme settings saved successfully!
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php if ($_GET['error'] === 'invalid_theme'): ?>
                    Invalid theme selected.
                <?php elseif ($_GET['error'] === 'invalid_settings'): ?>
                    Failed to save theme settings.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Available Themes</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($themes as $themeId => $theme): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card theme-card <?= $themeId === $activeTheme ? 'border-primary' : '' ?>">
                                        <img src="<?= $theme['screenshot'] ?>" class="card-img-top" alt="<?= htmlspecialchars($theme['name']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($theme['name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($theme['description']) ?></p>
                                            <p class="text-muted">
                                                Version: <?= htmlspecialchars($theme['version']) ?><br>
                                                By: <?= htmlspecialchars($theme['author']) ?>
                                            </p>
                                            <?php if ($themeId === $activeTheme): ?>
                                                <button class="btn btn-primary" disabled>Active Theme</button>
                                            <?php else: ?>
                                                <form action="<?= Theme::url('admin/themes/activate') ?>" method="POST" style="display: inline;">
                                                    <input type="hidden" name="theme" value="<?= $themeId ?>">
                                                    <button type="submit" class="btn btn-outline-primary">Activate</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Theme Settings</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($settings)): ?>
                            <p class="text-muted">No configurable settings for this theme.</p>
                        <?php else: ?>
                            <form action="<?= Theme::url('admin/themes/settings') ?>" method="POST">
                                <input type="hidden" name="theme" value="<?= $activeTheme ?>">
                                
                                <?php foreach ($settings as $key => $setting): ?>
                                    <div class="form-group">
                                        <label for="setting-<?= $key ?>"><?= htmlspecialchars($setting['label'] ?? ucwords(str_replace('_', ' ', $key))) ?></label>
                                        
                                        <?php if ($setting['type'] === 'color'): ?>
                                            <input type="color" 
                                                   class="form-control" 
                                                   id="setting-<?= $key ?>" 
                                                   name="settings[<?= $key ?>]"
                                                   value="<?= htmlspecialchars($setting['value'] ?? $setting['default'] ?? '') ?>">
                                        
                                        <?php elseif ($setting['type'] === 'select'): ?>
                                            <select class="form-control" 
                                                    id="setting-<?= $key ?>" 
                                                    name="settings[<?= $key ?>]">
                                                <?php foreach ($setting['options'] as $value => $label): ?>
                                                    <option value="<?= $value ?>" 
                                                            <?= ($setting['value'] ?? $setting['default'] ?? '') === $value ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($label) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        
                                        <?php elseif ($setting['type'] === 'textarea'): ?>
                                            <textarea class="form-control" 
                                                      id="setting-<?= $key ?>" 
                                                      name="settings[<?= $key ?>]"
                                                      rows="3"><?= htmlspecialchars($setting['value'] ?? $setting['default'] ?? '') ?></textarea>
                                        
                                        <?php else: ?>
                                            <input type="<?= $setting['type'] ?? 'text' ?>" 
                                                   class="form-control" 
                                                   id="setting-<?= $key ?>" 
                                                   name="settings[<?= $key ?>]"
                                                   value="<?= htmlspecialchars($setting['value'] ?? $setting['default'] ?? '') ?>"
                                                   <?= isset($setting['placeholder']) ? 'placeholder="' . htmlspecialchars($setting['placeholder']) . '"' : '' ?>>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($setting['description'])): ?>
                                            <small class="form-text text-muted"><?= htmlspecialchars($setting['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
