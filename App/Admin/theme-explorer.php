<?php

define("REPOSITORY", "https://project-extrex.github.io/repository");

// Load repository.json
$themes = json_decode(file_get_contents(REPOSITORY . "/theme/repository.json"), true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Theme Explorer</title>
  <style>
    body { font-family: system-ui; background:#f3f4f6; margin:0; padding:2rem 1rem; }
    h1 { text-align:center; margin-bottom:1.5rem; font-size:1.5rem; }
    .theme-grid { display:grid; gap:1.5rem; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); }
    .theme-card { background:#fff; border-radius:0.5rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.1); position:relative; transition: transform .2s, box-shadow .2s; }
    .theme-card:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.15); }
    .theme-screenshot { width:100%; height:140px; object-fit:cover; background:#e5e7eb; }
    .theme-info { padding:.75rem 1rem; }
    .theme-info h3 { margin:0 0 .25rem 0; font-size:1.1rem; }
    .theme-info p { margin:0; font-size:.85rem; color:#4b5563; height:40px; overflow:hidden; }
    .theme-info small { color:#6b7280; }
    .theme-badge { position:absolute; top:.5rem; right:.5rem; padding:.25rem .5rem; border-radius:.25rem; font-size:.75rem; font-weight:600; color:#fff; }
    .badge-version { background-color:#3b82f6; }
  </style>
</head>
<body>

<h1>Theme Explorer</h1>

<div class="theme-grid">
<?php foreach ($themes["list"] as $slug): 
    $themeUrl = REPOSITORY . "/theme/" . $slug . "/theme.json";
    $themeData = json_decode(file_get_contents($themeUrl), true);
    if (!$themeData) continue;
?>
  <div class="theme-card">
    <div class="theme-screenshot">
      <?php if (!empty($themeData["screenshot"])): ?>
        <img src="<?= htmlspecialchars($themeData["screenshot"]) ?>" 
             alt="<?= htmlspecialchars($themeData["name"]) ?> screenshot" 
             style="width:100%;height:100%;object-fit:cover;">
      <?php endif; ?>
    </div>

    <div class="theme-info">
      <h3><?= htmlspecialchars($themeData["name"]) ?></h3>
      <p><?= htmlspecialchars($themeData["description"]) ?></p>
      <small>
        v<?= htmlspecialchars($themeData["version"] ?? "1.0") ?> | 
        <?php if (!empty($themeData["authors"])): ?> By 
          <?php foreach($themeData["authors"] as $a): ?>
            <a href="<?= htmlspecialchars($a['url']) ?>" target="_blank"><?= htmlspecialchars($a['name']) ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
      </small>
      <?php if(!empty($themeData["homepage"])): ?>
       | <a href="<?= htmlspecialchars($themeData["homepage"]) ?>" target="_blank" rel="noopener noreferrer">Visit</a>
      <?php endif; ?>
    </div>

    <div class="theme-badge badge-version">
      v<?= htmlspecialchars($themeData["version"] ?? "1.0") ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

</body>
</html>