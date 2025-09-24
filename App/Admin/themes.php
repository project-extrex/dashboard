<?php

use App\Helper\ThemeLoader;
use App\Database\Entities\Settings;

require __DIR__ . "/../../connection.php";

// Path where themes live
$themeDir = __DIR__ . '/../../theme';

// Active theme from settings
$settingsRepo = $entityManager->getRepository(Settings::class);
$activeTheme = $settingsRepo->getSetting("theme") ?? "extrax";

// Load all themes
$loader = new ThemeLoader();
$themes = iterator_to_array($loader->loadThemes($themeDir));
?>

<body>
<style>
  body { font-family: system-ui; background:#f3f4f6; margin:0;/* padding:2rem 1rem; */}
  h1 { text-align:center; margin-bottom:1.5rem; font-size:1.5rem; }
  .theme-grid { display:grid; gap:1.5rem; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); padding: 2rem 1rem;}
  .theme-card { background:#fff; border-radius:0.5rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.1); position:relative; transition: transform .2s, box-shadow .2s; }
  .theme-card:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.15); }
  .theme-screenshot { width:100%; height:140px; object-fit:cover; background:#e5e7eb; }
  .theme-info { padding:.75rem 1rem; }
  .theme-info h3 { margin:0 0 .25rem 0; font-size:1.1rem; }
  .theme-info p { margin:0; font-size:.85rem; color:#4b5563; height:40px; overflow:hidden; }
  .theme-info small { color:#6b7280; }
  .theme-badge { position:absolute; top:.5rem; right:.5rem; padding:.25rem .5rem; border-radius:.25rem; font-size:.75rem; font-weight:600; color:#fff; }
  .badge-active { background-color:#10b981; }
  .badge-inactive { background-color:#f59e0b; }
  .theme-actions { display:flex; gap:.5rem; padding:.75rem 1rem; justify-content:center; flex-wrap:wrap; }
  .theme-btn { padding:.35rem .75rem; border-radius:.35rem; border:none; cursor:pointer; font-size:.85rem; transition:background .2s; }
  .btn-primary { background:#3b82f6; color:#fff; }
  .btn-primary:hover { background:#2563eb; }
  .btn-muted { background:#e5e7eb; color:#111827; }
  .btn-muted:hover { background:#d1d5db; }
  .btn-danger { background:#ef4444; color:#fff; }
  .btn-danger:hover { background:#b91c1c; }
</style>

<h1>Theme Manager</h1> <a href="/admin/theme-explorer">Find</a>
<div>
  <h2>Upload</h2>
  <p>Upload a theme from your local computer</p>
  <form id="uploadForm" action="/admin/theme/upload" method="post" enctype="multipart/form-data">
  <input type="file" name="zipfile" accept=".zip" required>
  <button type="submit">Upload & Extract</button>
</form>

<div id="status"></div>

<script>
const form = document.getElementById('uploadForm');
const statusDiv = document.getElementById('status');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    const response = await fetch('/admin/theme/upload', {
        method: 'POST',
        body: formData
    });

    const reader = response.body.getReader();
    const decoder = new TextDecoder();

    while(true) {
        const { value, done } = await reader.read();
        if (done) break;
        statusDiv.innerHTML += decoder.decode(value);
    }
});
</script>
</div>
<?php

if(isset($_GET["error"])) {
  echo htmlspecialchars($_GET["error"]);
};

if(isset($msg)) {
  echo $msg;
}

?>

<div class="theme-grid">

<?php foreach($themes as $slug => $theme): 
    $isActive = ($slug === $activeTheme);
    $screenshot = $theme['screenshot'] ?? '';
?>
  <div class="theme-card">
    <div class="theme-screenshot">
      <?php if($screenshot && file_exists("{$themeDir}/{$slug}/{$screenshot}")): ?>
        <img src="data:image/png;base64,<?= base64_encode(file_get_contents("{$themeDir}/{$slug}/{$screenshot}")) ?>" alt="<?= htmlspecialchars($theme['name']) ?> screenshot" style="width:100%;height:100%;object-fit:cover;"> 
      <?php endif; ?>
    </div>

    <div class="theme-info">
      <h3><?= htmlspecialchars($theme['name'] ?? $slug) ?></h3>
      <p><?= htmlspecialchars($theme['description'] ?? 'No description') ?></p>
      <small>
        v<?= htmlspecialchars($theme['version'] ?? '1.0') ?> | 
        <?php if (!empty($theme['authors'])): ?>
          <?php foreach($theme['authors'] as $a): ?>
            <a href="<?= htmlspecialchars($a['url']) ?>"><?= htmlspecialchars($a['name']) ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </small>
    </div>

    <div class="theme-badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
      <?= $isActive ? 'Active' : 'Inactive' ?>
    </div>

    <div class="theme-actions">
      <?php if($isActive): ?>
        <!-- Active: No BTN -->
        <?php else: ?>
        <!-- Inactive: Activate -->
        <form method="POST" action="/admin/theme/">
          <input type="hidden" name="action" value="activate">
          <input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>">
          <button type="submit" class="theme-btn btn-primary">Activate</button>
        </form>
        <!-- Inactive: Delete (but prevent deleting 'extrax') -->
        <?php ?>
          <form method="POST" action="/admin/theme/" onsubmit="return confirm('Delete theme \"<?= htmlspecialchars($slug) ?>\"? This cannot be undone.');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>">
            <button type="submit" class="theme-btn btn-danger">Delete</button>
          </form>
        <?php ?>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>

</div>
</body>