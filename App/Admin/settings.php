<?php
/** @var \App\Database\Repositories\SettingsRepository $repo */

$siteName    = htmlspecialchars($repo->getSetting("site_name") ?? "");
$description = htmlspecialchars($repo->getSetting("description") ?? "");
$logo        = $repo->getSetting("logo");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $newSiteName = trim($_POST['site_name']);
    $newDesc     = trim($_POST['description']);

    // Handle logo upload
    $logoBase64 = $repo->getSetting("logo");
    if (!empty($_FILES['logo']['tmp_name'])) {
        $mime = mime_content_type($_FILES['logo']['tmp_name']);
        $allowed = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];

        if ($_FILES['logo']['size'] > 50 * 1024) {
            $error = "Logo must be under 50KB!";
        } elseif (!in_array($mime, $allowed, true)) {
            $error = "Only PNG, JPG, GIF, or SVG allowed!";
        } else {
            $fileData = file_get_contents($_FILES['logo']['tmp_name']);
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode($fileData);
        }
    }

    if (!isset($error)) {
        $repo->setSetting("site_name", $newSiteName);
        $repo->setSetting("description", $newDesc);
        $repo->setSetting("logo", $logoBase64);

        $success = "Settings updated!";
        $siteName = htmlspecialchars($newSiteName);
        $description = htmlspecialchars($newDesc);
        $logo = $logoBase64;
    }
}
?>

<div class="settings-container">
    <h2>Extrex Settings <i class="fa-solid fa-gear"></i></h2>

    <?php if (isset($success)): ?>
        <p class="success-msg"><?= $success ?> <i class="fa-solid fa-circle-check"></i></p>
    <?php elseif (isset($error)): ?>
        <p class="error-msg"><?= $error ?> <i class="fa-solid fa-triangle-exclamation"></i></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="settings-form">
        <div class="form-group">
            <label>Site Name <i class="fa-solid fa-signature"></i></label>
            <input type="text" name="site_name" value="<?= $siteName ?>" required>
        </div>

        <div class="form-group">
            <label>Description <i class="fa-solid fa-audio-description"></i></label>
            <textarea name="description" rows="4" required><?= $description ?></textarea>
        </div>

        <div class="form-group">
            <label>Logo (max 50KB) <i class="fa-solid fa-image"></i></label>
            
            <label for="logo-input" id="logo-sec">Select Logo</label>
            <input type="file" name="logo" id="logo-input" accept="image/*">

            <div class="preview" id="logo-preview">
                <?php if (!empty($logo)): ?>
                    <img src="<?= $logo ?>" alt="Current Logo">
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" name="save" class="btn">Save Settings</button>
    </form>
</div>

<style>
.settings-container {
    max-width: 600px;
    margin: 2em auto;
    padding: 2em;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}
.settings-container h2 {
    margin-bottom: 1em;
    text-align: center;
}
.settings-form .form-group {
    margin-bottom: 1.5em;
}
.settings-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5em;
}
.settings-form input[type="text"],
.settings-form textarea {
    width: 100%;
    padding: 0.7em;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.settings-form textarea {
    resize: vertical;
}
#logo-input {
    display: none;
}

#logo-sec {
    margin: 5px 0;
    padding: 8px 12px;
    background: #f9fafb;
    border: 2px solid #ddd;
    border-radius: 6px;
    color: #333;
    font-weight: bold;
    cursor: pointer;
    display: inline-block;
    transition: background 0.2s ease;
}
#logo-sec:hover {
    background: #e5e7eb;
}

.preview {
    margin-top: 0.8em;
}
.preview img {
    max-height: 80px;
    border: 1px solid #ccc;
    padding: 4px;
    border-radius: 10px;
    background: #fafafa;
}
.btn {
    background: #4f46e5;
    color: #fff;
    padding: 0.8em 1.5em;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s ease;
}
.btn:hover {
    background: #4338ca;
}
.success-msg {
    color: green;
    text-align: center;
    margin-bottom: 1em;
}
.error-msg {
    color: red;
    text-align: center;
    margin-bottom: 1em;
}
</style>

<script>
// Live logo preview before upload
document.getElementById('logo-input').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (evt) {
            const img = document.createElement('img');
            img.src = evt.target.result;
            img.alt = "New Logo Preview";
            img.style.maxHeight = "80px";
            img.style.border = "1px solid #ccc";
            img.style.padding = "4px";
            img.style.borderRadius = "10px";
            img.style.background = "#fafafa";

            const preview = document.getElementById('logo-preview');
            preview.innerHTML = "";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>