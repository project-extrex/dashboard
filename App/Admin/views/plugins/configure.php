<?php
/**
 * Admin Plugin Configuration View
 */

use App\Helper\Theme;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Plugin Configuration' ?> - Admin Dashboard</title>
    <link rel="stylesheet" href="/admin/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-page">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>
    
    <main class="admin-main">
        <?php include __DIR__ . '/../partials/header.php'; ?>

        <div class="content-wrapper">
            <div class="plugin-config-page">
                <div class="page-header">
                    <h1>Configure <?= htmlspecialchars($plugin->getName()) ?></h1>
                    <div class="page-actions">
                        <a href="/admin/plugins" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Plugins
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['flash'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
                    </div>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <div class="config-form-container">
                    <form action="/admin/plugins/save-config/<?= $plugin->getId() ?>" method="POST" class="plugin-config-form">
                        <?php foreach ($plugin->getConfigurationSchema() as $key => $field): ?>
                            <div class="form-group">
                                <label for="<?= $key ?>"><?= htmlspecialchars($field['label']) ?></label>
                                
                                <?php if ($field['type'] === 'text' || $field['type'] === 'password'): ?>
                                    <input type="<?= $field['type'] ?>" 
                                           id="<?= $key ?>" 
                                           name="config[<?= $key ?>]" 
                                           value="<?= htmlspecialchars($config[$key] ?? $field['default'] ?? '') ?>"
                                           class="form-control"
                                           <?= ($field['required'] ?? false) ? 'required' : '' ?>>

                                <?php elseif ($field['type'] === 'textarea'): ?>
                                    <textarea id="<?= $key ?>" 
                                              name="config[<?= $key ?>]" 
                                              class="form-control"
                                              rows="4"
                                              <?= ($field['required'] ?? false) ? 'required' : '' ?>><?= htmlspecialchars($config[$key] ?? $field['default'] ?? '') ?></textarea>

                                <?php elseif ($field['type'] === 'select'): ?>
                                    <select id="<?= $key ?>" 
                                            name="config[<?= $key ?>]" 
                                            class="form-control"
                                            <?= ($field['required'] ?? false) ? 'required' : '' ?>>
                                        <?php foreach ($field['options'] as $value => $label): ?>
                                            <option value="<?= $value ?>" <?= ($config[$key] ?? $field['default'] ?? '') === $value ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($label) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                <?php elseif ($field['type'] === 'checkbox'): ?>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="<?= $key ?>" 
                                               name="config[<?= $key ?>]" 
                                               value="1"
                                               <?= ($config[$key] ?? $field['default'] ?? false) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="<?= $key ?>"><?= htmlspecialchars($field['label']) ?></label>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($field['description'])): ?>
                                    <small class="form-text text-muted"><?= htmlspecialchars($field['description']) ?></small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="/admin/assets/js/admin.js"></script>
</body>
</html>
