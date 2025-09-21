<?php
/** @var \App\Database\Repositories\SettingsRepository $repo */

$siteName    = htmlspecialchars($repo->getSetting("site_name") ?? "");
$description = htmlspecialchars($repo->getSetting("description") ?? "");
$logo        = $repo->getSetting("logo");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $newSiteName = trim($_POST['site_name']);
    $newDesc     = trim($_POST['description']);

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

<div class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Extrex Settings</h1>

    <?php if (!empty($success)): ?>
        <p class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> <?= $success ?>
        </p>
    <?php elseif (!empty($error)): ?>
        <p class="mb-4 px-4 py-2 rounded bg-red-100 text-red-800 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= $error ?>
        </p>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-lg mx-auto">
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Site Name <i class="fa-solid fa-signature"></i></label>
                <input type="text" name="site_name" value="<?= $siteName ?>" required
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Description <i class="fa-solid fa-audio-description"></i></label>
                <textarea name="description" rows="4" required
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"><?= $description ?></textarea>
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Logo (max 50KB) <i class="fa-solid fa-image"></i></label>
                <label for="logo-input" class="cursor-pointer inline-block px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">Select Logo</label>
                <input type="file" name="logo" id="logo-input" accept="image/*" class="hidden">

                <div id="logo-preview" class="mt-3 flex justify-center">
                    <?php if (!empty($logo)): ?>
                        <img src="<?= $logo ?>" alt="Current Logo" class="max-h-20 border p-2 rounded-lg bg-gray-50">
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="save" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-bold transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            const img = document.createElement('img');
            img.src = evt.target.result;
            img.alt = "New Logo Preview";
            img.className = "max-h-20 border p-2 rounded-lg bg-gray-50";

            const preview = document.getElementById('logo-preview');
            preview.innerHTML = "";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
</script>