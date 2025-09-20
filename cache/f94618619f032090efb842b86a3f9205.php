<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" property="og:description" content="<?php echo e($description); ?>">
  <meta name="image" property="og:image" content="<?php echo e($favicon); ?>">
  <title><?php echo $__env->yieldContent('title', '<?php echo e($siteName); ?>'); ?></title>

  
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">

  
  <link rel="icon" type="image/png" href="<?php echo e($favicon); ?>"/>

  <style>
    body {
      font-family: "JetBrains Mono", monospace;
    }
  </style>

  <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="min-h-screen flex flex-col">

  <?php echo $__env->make('components.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <main role="main" class="flex-1">
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <footer role="contentinfo" class="text-center py-4">
    © 2025 <?php echo e($siteName); ?>

  </footer>

  <?php echo $__env->yieldPushContent('script'); ?>
</body>
</html><?php /**PATH /project/workspace/theme/extrax/layout.blade.php ENDPATH**/ ?>