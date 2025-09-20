<?php
use App\Core\Option;
?>
<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 px-4 text-center">
    
    <!-- Hero Section -->
    <h1 class="text-5xl font-extrabold text-gray-800 mb-4">
        Welcome to <?php echo e($siteName); ?>

    </h1>
    <p class="text-lg text-gray-600 mb-8">
        Start your hosting journey with us
    </p>

    <!-- Call-to-Action Button -->
    <button onclick="window.location.href='/dashboard'" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition" style="background: <?php echo e(Option::get_option('color')); ?>;">
        Let's Go
        <span class="text-xl"><i class="fa-solid fa-chevron-right"></i></span>
    </button>

    <!-- Optional Feature Cards -->
    <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-5xl">
        <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-2">Reliable Hosting</h2>
            <p class="text-gray-600">99.9% uptime and secure servers for your projects.</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-2">Fast Setup</h2>
            <p class="text-gray-600">Get your servers running within minutes, hassle-free.</p>
        </div>
        <div class="p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <h2 class="text-xl font-bold mb-2">24/7 Support</h2>
            <p class="text-gray-600">Our team is always ready to help you anytime.</p>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/index.blade.php ENDPATH**/ ?>