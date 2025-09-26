<?php $__env->startSection('title', 'Shop'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-4 sm:py-8 overflow-x-auto">
  <!-- Header -->
  <div class="max-w-7xl mx-auto px-4 mb-8">
    <div class="text-center mb-6">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">
        <i class="fas fa-store text-blue-600 mr-2"></i>
        Product Shop
      </h1>
      <p class="text-gray-600">Choose from our selection of server products</p>
      <?php if(isset($user)): ?>
      <p> Credits: <i class="fa-solid fa-coins text-yellow-500"></i> <?php echo e($user->getResources()->getCoins()); ?></p>
      <?php endif; ?>
      <?php if($msg): ?>
      <?php echo e($msg); ?>

      <?php elseif($error): ?>
      <?php echo e($error); ?>

      <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="flex justify-center mb-6">
      <div class="bg-white rounded-lg shadow-sm p-1 flex flex-wrap gap-1 max-w-full overflow-x-auto">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="CreateServer">Servers</button>
        <button class="filter-btn" data-filter="AddResources">Resources</button>
        <button class="filter-btn" data-filter="CreateDatabase">Databases</button>
        <button class="filter-btn" data-filter="CreateBackup">Backups</button>
      </div>
    </div>
  </div>

  <!-- Products Grid -->
  <div class="max-w-7xl mx-auto px-4">
    <?php if($products && count($products) > 0): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6" id="productsGrid">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="product-card group" data-action="<?php echo e($product->getAction()); ?>">
            
            <!-- Product Image/Icon -->
            <div class="relative h-32 sm:h-40 bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-lg flex items-center justify-center overflow-hidden">
              <?php if($product->getImage()): ?>
                <img src="<?php echo e($product->getImage()); ?>" alt="<?php echo e($product->getName()); ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
              <?php else: ?>
                <div class="text-center text-gray-400">
                  <?php switch($product->getAction()):
                    case ('CreateServer'): ?>
                      <i class="fas fa-server text-3xl sm:text-4xl mb-2 text-blue-500"></i>
                      <?php break; ?>
                    <?php case ('AddResources'): ?>
                      <i class="fas fa-plus-circle text-3xl sm:text-4xl mb-2 text-green-500"></i>
                      <?php break; ?>
                    <?php case ('CreateDatabase'): ?>
                      <i class="fas fa-database text-3xl sm:text-4xl mb-2 text-purple-500"></i>
                      <?php break; ?>
                    <?php case ('CreateBackup'): ?>
                      <i class="fas fa-shield-alt text-3xl sm:text-4xl mb-2 text-orange-500"></i>
                      <?php break; ?>
                    <?php default: ?>
                      <i class="fas fa-box text-3xl sm:text-4xl mb-2 text-gray-500"></i>
                  <?php endswitch; ?>
                </div>
              <?php endif; ?>
              
              <!-- Category Badge -->
              <div class="absolute top-2 right-2">
                <?php switch($product->getAction()):
                  case ('CreateServer'): ?>
                    <span class="badge badge-blue">Server</span>
                    <?php break; ?>
                  <?php case ('AddResources'): ?>
                    <span class="badge badge-green">Resource</span>
                    <?php break; ?>
                  <?php case ('CreateDatabase'): ?>
                    <span class="badge badge-purple">Database</span>
                    <?php break; ?>
                  <?php case ('CreateBackup'): ?>
                    <span class="badge badge-orange">Backup</span>
                    <?php break; ?>
                  <?php default: ?>
                    <span class="badge badge-gray">Custom</span>
                <?php endswitch; ?>
              </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
              <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                <?php echo e($product->getName()); ?>

              </h3>

              <?php if($product->getDescription()): ?>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                  <?php echo e($product->getDescription()); ?>

                </p>
              <?php endif; ?>

              <!-- Key Specs -->
              <?php if($product->getActionParams() && count($product->getActionParams()) > 0): ?>
                <div class="mb-3">
                  <?php $params = $product->getActionParams(); ?>
                  <?php switch($product->getAction()):
                    case ('CreateServer'): ?>
                      <div class="flex flex-wrap gap-2 text-xs">
                        <?php if(isset($params['ram'])): ?>
                          <span class="spec-tag"><?php echo e($params['ram']); ?>MB RAM</span>
                        <?php endif; ?>
                        <?php if(isset($params['cpu'])): ?>
                          <span class="spec-tag"><?php echo e($params['cpu']); ?>% CPU</span>
                        <?php endif; ?>
                        <?php if(isset($params['disk'])): ?>
                          <span class="spec-tag"><?php echo e($params['disk']); ?>MB Disk</span>
                        <?php endif; ?>
                      </div>
                      <?php break; ?>
                    <?php case ('AddResources'): ?>
                      <?php if(isset($params['resource_type']) && isset($params['value'])): ?>
                        <div class="text-xs">
                          <span class="spec-tag"><?php echo e($params['value']); ?> <?php echo e($params['unit'] ?? ''); ?> <?php echo e(ucfirst($params['resource_type'])); ?></span>
                        </div>
                      <?php endif; ?>
                      <?php break; ?>
                  <?php endswitch; ?>
                </div>
              <?php endif; ?>

              <!-- Price & Action -->
              <div class="flex items-center justify-between">
                <div class="text-lg font-bold text-green-600">
                  <i class="fa-solid fa-coins text-yellow-500"></i> <?php echo e(number_format($product->getPrice(), 2)); ?>

                </div>
                
                <?php if(isset($user)): ?>
                 <?php if($user->getResources()->getCoins() >= $product->getPrice()): ?>
                <button class="buy-btn" 
                        data-product-id="<?php echo e($product->getId()); ?>"
                        data-product-name="<?php echo e($product->getName()); ?>"
                        data-product-price="<?php echo e($product->getPrice()); ?>"
                        data-product-action="<?php echo e($product->getAction()); ?>"
                        data-product-params="<?php echo e(json_encode($product->getActionParams() ?: [])); ?>">
                  Buy Now
                </button>
                 <?php else: ?>
                  <p>You can't afford it </p>
                 <?php endif; ?>
                <?php else: ?>
                <button class="p-1 m-2 rounded shadow bg-blue-400 text-white" id="login-btn" onclick="window.location = '/login?redirect=/shop' ">Login</button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php else: ?>
      <!-- Empty State -->
      <div class="text-center py-16">
        <i class="fas fa-store-slash text-5xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-600 mb-2">No products available</h3>
        <p class="text-gray-500">Check back soon for new products!</p>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php if(isset($user)): ?>
<!-- Purchase Modal -->
<div id="modal" class="modal">
  <div class="modal-content">
    <div class="text-center mb-4">
      <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <i class="fas fa-shopping-cart text-blue-600"></i>
      </div>
      <h3 class="text-lg font-semibold mb-1">Confirm Purchase</h3>
    </div>
    
    <div class="bg-gray-50 rounded-lg p-3 mb-4">
      <div class="flex justify-between items-center mb-2">
        <h4 id="modal-name" class="font-medium"></h4>
        <span id="modal-price" class="text-green-600 font-bold"></span>
      </div>
      <p id="modal-action" class="text-sm text-gray-600"></p>
    </div>
    
    <div class="flex gap-3">
      <button onclick="closeModal()" class="flex-1 py-2 px-4 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
        Cancel
      </button>
      <button onclick="purchase()" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Confirm
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
/* Base Styles */
.product-card {
  @apply bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer border border-gray-100;
}

.filter-btn {
  @apply px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors whitespace-nowrap;
}

.filter-btn.active {
  @apply bg-blue-600 text-white hover:bg-blue-700;
}

.badge {
  @apply px-2 py-1 text-xs font-medium rounded-full;
}

.badge-blue { @apply bg-blue-100 text-blue-800; }
.badge-green { @apply bg-green-100 text-green-800; }
.badge-purple { @apply bg-purple-100 text-purple-800; }
.badge-orange { @apply bg-orange-100 text-orange-800; }
.badge-gray { @apply bg-gray-100 text-gray-800; }

.spec-tag {
  @apply inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs;
}

.buy-btn {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Modal */
.modal {
  @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50;
}

.modal-content {
  @apply bg-white rounded-xl p-6 w-full max-w-sm mx-auto;
}

/* Responsive Adjustments */
@media (max-width: 640px) {
  .filter-btn {
    @apply px-2 py-1 text-xs;
  }
  
  .spec-tag {
    @apply px-1.5 py-0.5;
  }
}
</style>

<script>
let selectedProduct = null;

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
  // Filter buttons
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      // Update active state
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      
      const filter = this.dataset.filter;
      const cards = document.querySelectorAll('.product-card');
      
      cards.forEach(card => {
        const shouldShow = filter === 'all' || card.dataset.action === filter;
        card.style.display = shouldShow ? 'block' : 'none';
      });
    });
  });

  // Purchase buttons
  document.querySelectorAll('.buy-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      selectedProduct = {
        id: this.dataset.productId,
        name: this.dataset.productName,
        price: this.dataset.productPrice,
        action: this.dataset.productAction,
        params: JSON.parse(this.dataset.productParams || '{}')
      };
      showModal();
    });
  });

  // Modal close on backdrop click
  document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
});

function showModal() {
  if (!selectedProduct) return;
  
  document.getElementById('modal-name').textContent = selectedProduct.name;
  document.getElementById('modal-price').innerHTML = '<i class="fa-solid fa-coins text-yellow-500"></i> ' + parseFloat(selectedProduct.price).toFixed(2);
  document.getElementById('modal-action').textContent = getActionName(selectedProduct.action);
  document.getElementById('modal').classList.remove('hidden');
  //document.body.style.overflow = 'hidden';
}

function closeModal() {
  document.getElementById('modal').classList.add('hidden');
  document.body.style.overflow = '';
  selectedProduct = null;
}

function purchase() {
  if (!selectedProduct) return;
  
  // Create form and submit
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/purchase';
  
  // Add CSRF token if available
  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  if (csrfToken) {
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = csrfToken.content;
    form.appendChild(tokenInput);
  }
  
  // Add product data
  Object.entries(selectedProduct).forEach(([key, value]) => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = typeof value === 'object' ? JSON.stringify(value) : value;
    form.appendChild(input);
  });
  
  document.body.appendChild(form);
  form.submit();
}

function getActionName(action) {
  const names = {
    'CreateServer': 'Server Creation',
    'AddResources': 'Resource Addition', 
    'CreateDatabase': 'Database Creation',
    'CreateBackup': 'Backup Service'
  };
  return names[action] || 'Custom Service';
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal();
});

const loginBtn = document.getElementById("login-btn");
loginBtn.addEventListener("click", function(e) {
  window.location = "/login"
})

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /project/workspace/theme/extrax/shop.blade.php ENDPATH**/ ?>