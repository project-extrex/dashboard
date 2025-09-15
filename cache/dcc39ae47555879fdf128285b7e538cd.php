<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection("content"); ?>


<?php if(!empty($error)): ?>
    <div class="bg-red-600 text-white p-3 mb-4 rounded-lg shadow-md flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo e($error); ?>

    </div>
<?php endif; ?>


<form method="POST" class="bg-white p-6 rounded-xl shadow-md max-w-lg mx-auto space-y-4">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-4 flex items-center justify-center gap-2">
        <i class="fas fa-user-circle text-blue-600"></i> Your Profile
    </h2>

    <div>
        <label for="name" class="block text-gray-700 font-medium mb-1">Name <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" 
               value="<?php echo e(old('name', $user_data->getName())); ?>"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="username" class="block text-gray-700 font-medium mb-1">Username <span class="text-red-500">*</span></label>
        <input type="text" id="username" name="username" 
               value="<?php echo e(old('username', $user_data->getUsername())); ?>"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="firstname" class="block text-gray-700 font-medium mb-1">Firstname <span class="text-red-500">*</span></label>
        <input type="text" id="firstname" name="firstname" 
               value="<?php echo e(old('firstname', $user_data->getFirstname())); ?>"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="lastname" class="block text-gray-700 font-medium mb-1">Lastname <span class="text-red-500">*</span></label>
        <input type="text" id="lastname" name="lastname" 
               value="<?php echo e(old('lastname', $user_data->getLastname())); ?>"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="email" class="block text-gray-700 font-medium mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" 
               value="<?php echo e(old('email', $user_data->getEmail())); ?>"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="password" class="block text-gray-700 font-medium mb-1">Password <span class="text-red-500">*</span></label>
        <input type="password" id="password" name="password" placeholder="Confirm password"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <input type="hidden" name="action" value="account">

    <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
        <i class="fas fa-save"></i> Save Profile
    </button>
</form>


<form method="POST" class="bg-white p-6 mt-6 rounded-xl shadow-md max-w-lg mx-auto space-y-4">
    <h2 class="text-xl font-bold text-gray-800 text-center mb-4 flex items-center justify-center gap-2">
        <i class="fas fa-key text-yellow-600"></i> Change Password
    </h2>

    <div>
        <label for="current-password" class="block text-gray-700 font-medium mb-1">Current Password <span class="text-red-500">*</span></label>
        <input type="password" id="current-password" name="current-password"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
    </div>

    <div>
        <label for="new-password" class="block text-gray-700 font-medium mb-1">New Password <span class="text-red-500">*</span></label>
        <input type="password" id="new-password" name="new-password"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
    </div>

    <div>
        <label for="retype-password" class="block text-gray-700 font-medium mb-1">Re-type Password <span class="text-red-500">*</span></label>
        <input type="password" id="retype-password" name="retype-password"
               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" required>
    </div>

    <input type="hidden" name="action" value="password">

    <button type="submit"
            class="w-full bg-yellow-600 text-white py-2 rounded-lg font-semibold hover:bg-yellow-700 transition flex items-center justify-center gap-2">
        <i class="fas fa-lock"></i> Update Password
    </button>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/profile.blade.php ENDPATH**/ ?>