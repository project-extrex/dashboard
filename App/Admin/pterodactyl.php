<?php

use App\Core\AdminSidebar;
use App\Database\Entities\Eggs;
use App\Database\Entities\Settings;

$settingsRepo = $entityManager->getRepository(Settings::class);
// $settingsRepo->setSetting('theme', $themeSlug);

/**
 * Create a new Egg
 */
function createEgg(array $data)
{
  global $entityManager;

  $egg = new Eggs();
  $egg
    ->setName($data['name'])
    ->setCategory($data['category'])
    ->setDescription($data['description'] ?? null)
    ->setDockerImage($data['dockerImage'])
    ->setStartup($data['startup'])
    ->setIcon($data['icon'] ?? null);

  $entityManager->persist($egg);
  $entityManager->flush();

  return $egg;
}

function updateEgg(int $id, array $data)
{
  global $entityManager;

  $egg = $entityManager->find(Eggs::class, $id);
  if (!$egg)
    return null;

  if (isset($data['name']))
    $egg->setName($data['name']);
  if (isset($data['category']))
    $egg->setCategory($data['category']);
  if (array_key_exists('description', $data))
    $egg->setDescription($data['description']);
  if (isset($data['dockerImage']))
    $egg->setDockerImage($data['dockerImage']);
  if (isset($data['startup']))
    $egg->setStartup($data['startup']);
  if (array_key_exists('icon', $data))
    $egg->setIcon($data['icon']);

  $entityManager->flush();
  return $egg;
}

function deleteEgg(int $id): bool
{
  global $entityManager;

  $egg = $entityManager->find(Eggs::class, $id);
  if (!$egg)
    return false;

  $entityManager->remove($egg);
  $entityManager->flush();
  return true;
}

function listEggs(): array
{
  global $entityManager;
  return $entityManager->getRepository(Eggs::class)->findAll();
}

function eggManager()
{
  // Handle form submissions
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
      createEgg([
        'name' => $_POST['name'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'dockerImage' => $_POST['dockerImage'],
        'startup' => $_POST['startup'],
        'icon' => $_POST['icon'] ?? null,
      ]);
    } elseif (isset($_POST['update'])) {
      updateEgg((int) $_POST['id'], [
        'name' => $_POST['name'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
        'dockerImage' => $_POST['dockerImage'],
        'startup' => $_POST['startup'],
        'icon' => $_POST['icon'] ?? null,
      ]);
    } elseif (isset($_POST['delete'])) {
      deleteEgg((int) $_POST['id']);
    }
  }

  $eggs = listEggs();
  ?>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<body class=" bg-gray-100">

  <h1 class="text-2xl font-bold mb-4">Egg Manager</h1>

  <!-- Create Egg Form -->
  <form method="POST" class="mb-6 p-4 bg-white rounded shadow space-y-2">
    <h2 class="text-lg font-semibold">Create New Egg</h2>
    <input type="text" name="name" placeholder="Name" required class="w-full border p-2 rounded">
    <input type="text" name="category" placeholder="Category" required class="w-full border p-2 rounded">
    <textarea name="description" placeholder="Description" class="w-full border p-2 rounded"></textarea>
    <input type="text" name="dockerImage" placeholder="Docker Image" required class="w-full border p-2 rounded">
    <input type="text" name="startup" placeholder="Startup Command" required class="w-full border p-2 rounded">
    <input type="text" name="icon" placeholder="Icon URL (optional)" class="w-full border p-2 rounded">
    <button type="submit" name="create" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
  </form>

  <!-- List Eggs -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($eggs as $egg): ?>
      <div class="p-4 bg-white rounded shadow">
        <?php if ($egg->getIcon()): ?>
          <img src="<?= htmlspecialchars($egg->getIcon()) ?>" alt="<?= htmlspecialchars($egg->getName()) ?>" class="w-12 h-12 mb-2 rounded">
        <?php endif; ?>
        <h3 class="font-semibold"><?= htmlspecialchars($egg->getName()) ?></h3>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($egg->getCategory()) ?></p>
        <p class="text-sm"><?= htmlspecialchars($egg->getDescription() ?? '') ?></p>
        <p class="text-xs text-gray-400">Image: <?= htmlspecialchars($egg->getDockerImage()) ?></p>
        <p class="text-xs text-gray-400">Startup: <?= htmlspecialchars($egg->getStartup()) ?></p>

        <!-- Update / Delete Form -->
        <form method="POST" class="mt-2 space-y-1">
          <input type="hidden" name="id" value="<?= $egg->getId() ?>">
          <input type="text" name="name" value="<?= htmlspecialchars($egg->getName()) ?>" class="w-full border p-1 rounded">
          <input type="text" name="category" value="<?= htmlspecialchars($egg->getCategory()) ?>" class="w-full border p-1 rounded">
          <textarea name="description" class="w-full border p-1 rounded"><?= htmlspecialchars($egg->getDescription() ?? '') ?></textarea>
          <input type="text" name="dockerImage" value="<?= htmlspecialchars($egg->getDockerImage()) ?>" class="w-full border p-1 rounded">
          <input type="text" name="startup" value="<?= htmlspecialchars($egg->getStartup()) ?>" class="w-full border p-1 rounded">
          <input type="text" name="icon" value="<?= htmlspecialchars($egg->getIcon() ?? '') ?>" class="w-full border p-1 rounded">

          <div class="flex gap-2">
            <button type="submit" name="update" class="bg-yellow-500 text-white px-3 py-1 rounded">Update</button>
            <button type="submit" name="delete" class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

</body>

<?php
}

AdminSidebar::addMenu([
  'title' => 'Pterodactyl',
  'slug' => 'pterodactyl',
  'icon' => 'fa-solid fa-dove',
  'position' => 5,
  'callback' => function () {
    global $entityManager;
    $settingsRepo = $entityManager->getRepository(\App\Database\Entities\Settings::class);

    $message = null;
    $messageType = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $url = filter_input(INPUT_POST, 'panel_url', FILTER_SANITIZE_URL);
      $key = $_POST['panel_key'] ?? null;

      if ($url && $key) {
        $settingsRepo->setSetting('pterodactyl_panel_url', $url);
        $settingsRepo->setSetting('pterodactyl_panel_key', $key);
        $message = 'Settings saved successfully!';
        $messageType = 'success';
      } else {
        $message = 'Invalid input. Please check your entries.';
        $messageType = 'error';
      }
    }

    $panelUrl = $settingsRepo->getSetting('pterodactyl_panel_url') ?? '';
    $panelKey = $settingsRepo->getSetting('pterodactyl_panel_key') ?? '';
    ?>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold mb-6">Pterodactyl Settings</h1>

        <?php if ($message): ?>
            <div class="mb-4 p-3 rounded 
                <?= $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="bg-white rounded shadow p-6 space-y-4 max-w-xl">
            <div>
                <label class="block text-sm font-medium text-gray-700">Panel URL</label>
                <input type="url" name="panel_url" required
                    placeholder="https://panel.example.com"
                    value="<?= htmlspecialchars($panelUrl) ?>"
                    class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">API Key</label>
                <input type="password" name="panel_key" required
                    placeholder="Enter your API key"
                    value="<?= htmlspecialchars($panelKey) ?>"
                    class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <?php
  },
  'submenus' => [
    '' => [
      'title' => 'General',
      'slug' => '',
    ],
    'Egg' => [
      'title' => 'Egg',
      'slug' => 'eggs',
      'callback' => 'eggManager'
    ],
    'Nodes' => [
      'title' => 'Nodes',
      'slug' => 'nodes'
    ]
  ]
]);
