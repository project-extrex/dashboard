<link rel="stylesheet" href="/admin/assets/settings/style.css">

<?php
global $entityManager;

// Render the settings table
function renderSettingsTable(array $settings): void {
    echo '<table class="settings-table">';
    echo '<thead>';
    echo '<tr>
            <th>ID</th>
            <th>Name</th>
            <th>Value</th>
            <th>Actions</th>
          </tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($settings as $setting) {
        echo '<tr>';
        echo '<td>' . $setting->getId() . '</td>';
        echo '<td>' . htmlspecialchars($setting->getName()) . '</td>';
        echo '<td>' . htmlspecialchars($setting->getValue()) . '</td>';
        echo '<td>';
        if ($setting->getName() !== "theme") {
            echo '<form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="' . $setting->getId() . '">
                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                  </form>';
        } else {
            echo '<span class="protected">Protected</span>';
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

// Render form
function renderForm(): void {
    echo '<form method="post">';
    echo '<label>Name: <input type="text" name="name" required></label><br><br>';
    echo '<label>Value: <input type="text" name="value" required></label><br><br>';
    echo '<input type="submit" value="Save Setting" name="submit">';
    echo '</form>';
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $value = trim($_POST['value']);
    $repo->setSetting($name, $value);
    echo '<p class="success-msg">Setting saved successfully!</p>';
}

// Handle deletion
if (isset($_POST['delete']) && !empty($_POST['id'])) {
    $id = (int) $_POST['id'];
    $setting = $entityManager->find(\App\Database\Entities\Settings::class, $id);
    if ($setting) {
        $entityManager->remove($setting);
        $entityManager->flush();
        echo '<p class="error-msg">Setting deleted successfully!</p>';
    }
}

// Render page
echo "<h2>Current Settings</h2>";
renderSettingsTable($settings);

echo "<h2>Add or Update Setting</h2>";
renderForm();
?>