<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-center min-h-screen bg-gray-50">
  <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-8">

    <!-- Title -->
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
      <i class="fa-solid fa-right-to-bracket mr-2"></i> Login
    </h1>

    <!-- Error Message -->
    <?php if(isset($error)): ?>
    <div class="bg-red-600 text-white text-sm p-3 mb-4 rounded-lg flex items-center gap-2">
      <i class="fa-solid fa-circle-exclamation"></i>
      <span><?php echo e($error); ?></span>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?php echo e($login_path); ?>" method="post" class="space-y-5">

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
          <i class="fa-solid fa-envelope mr-1 text-gray-500"></i> Email
        </label>
        <input type="email" name="email" id="email" required
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
          <i class="fa-solid fa-lock mr-1 text-gray-500"></i> Password
        </label>
        <input type="password" name="password" id="password" required
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
      </div>

      <!-- Submit Button -->
      <div>
        <button type="submit"
          class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
          <i class="fa-solid fa-right-to-bracket"></i> Login
        </button>
      </div>

    </form>

  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('../layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/auth/login.blade.php ENDPATH**/ ?>