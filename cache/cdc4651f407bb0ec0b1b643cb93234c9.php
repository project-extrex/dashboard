<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection("content"); ?>


<?php if(isset($_GET['error'])): ?>
    <div class="bg-red-600 text-white p-3 mb-4 rounded-lg shadow-md flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo e(htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8')); ?>

    </div>
<?php endif; ?>


<?php if(isset($user_data)): ?>
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-user-circle text-blue-600"></i>
            <?php echo e(htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8')); ?>

        </h2>

        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center shadow-sm">
                <i class="fas fa-memory text-blue-600 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Memory</p>
                <p class="text-xl font-semibold text-blue-600">
                    <?php echo e(htmlspecialchars($user_data->getResources()->getMemory(), ENT_QUOTES, 'UTF-8')); ?> MB
                </p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center shadow-sm">
                <i class="fas fa-hdd text-green-600 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">Disk</p>
                <p class="text-xl font-semibold text-green-600">
                    <?php echo e(htmlspecialchars($user_data->getResources()->getDisk(), ENT_QUOTES, 'UTF-8')); ?> GB
                </p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg text-center shadow-sm">
                <i class="fas fa-microchip text-purple-600 text-2xl mb-2"></i>
                <p class="text-gray-500 text-sm">CPU</p>
                <p class="text-xl font-semibold text-purple-600">
                    <?php echo e(htmlspecialchars($user_data->getResources()->getCpu(), ENT_QUOTES, 'UTF-8')); ?>%
                </p>
            </div>
        </div>

        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-sm text-gray-600">
            <div class="bg-gray-50 p-2 rounded-md shadow-sm flex items-center gap-2">
                <i class="fas fa-coins text-yellow-500"></i>
                Coins: <span class="font-medium"><?php echo e(htmlspecialchars($user_data->getResources()->getCoins(), ENT_QUOTES, 'UTF-8')); ?></span>
            </div>
            <div class="bg-gray-50 p-2 rounded-md shadow-sm flex items-center gap-2">
                <i class="fas fa-database text-indigo-500"></i>
                DBs: <span class="font-medium"><?php echo e(htmlspecialchars($user_data->getResources()->getDbs(), ENT_QUOTES, 'UTF-8')); ?></span>
            </div>
            <div class="bg-gray-50 p-2 rounded-md shadow-sm flex items-center gap-2">
                <i class="fas fa-cloud text-blue-500"></i>
                Backups: <span class="font-medium"><?php echo e(htmlspecialchars($user_data->getResources()->getBackups(), ENT_QUOTES, 'UTF-8')); ?></span>
            </div>
            <div class="bg-gray-50 p-2 rounded-md shadow-sm flex items-center gap-2">
                <i class="fas fa-network-wired text-pink-500"></i>
                Allocations: <span class="font-medium"><?php echo e(htmlspecialchars($user_data->getResources()->getAllocations(), ENT_QUOTES, 'UTF-8')); ?></span>
            </div>
            <div class="bg-gray-50 p-2 rounded-md shadow-sm flex items-center gap-2">
                <i class="fas fa-server text-gray-600"></i>
                Slots: <span class="font-medium"><?php echo e(htmlspecialchars($user_data->getResources()->getSlots(), ENT_QUOTES, 'UTF-8')); ?></span>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/dashboard.blade.php ENDPATH**/ ?>