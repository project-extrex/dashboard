<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', $siteName); ?></title>
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: "JetBrains Mono", monospace
    }

  </style>
  <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body>

  <?php echo $__env->make('components.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <main>
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <footer class="justify-self-center">© 2025 <?php echo e($siteName); ?></footer>

  <?php echo $__env->yieldPushContent('script'); ?>
</body>
</html>
<?php /**PATH /project/workspace/theme/extrax////layout.blade.php ENDPATH**/ ?>