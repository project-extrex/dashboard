<?php
$products = $products;
$msg = $msg ?? null;
$error = $error ?? null;
?>

<div class="min-h-screen bg-gray-100 p-6 font-sans">

  <!-- Header -->
  <h1 class="text-3xl font-bold mb-6 flex items-center gap-2">
    <i class="fas fa-box-open text-blue-500"></i>
    Manage Products
  </h1>

  <!-- Messages -->
  <?php if($msg): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4 flex items-center gap-2">
      <i class="fas fa-check-circle"></i> <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <?php if($error): ?>
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4 flex items-center gap-2">
      <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Add Product Button -->
  <div class="mb-6 flex justify-end">
    <button onclick="toggleAddModal()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded flex items-center gap-2">
      <i class="fas fa-plus"></i> Add Product
    </button>
  </div>

  <!-- Product Table -->
  <div class="overflow-x-auto mb-6">
    <table class="min-w-full bg-white rounded-lg shadow divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3">Image</th>
          <th class="px-6 py-3">Action</th>
          <th class="px-6 py-3">Price</th>
          <th class="px-6 py-3">Parameters</th>
          <th class="px-6 py-3">Description</th>
          <th class="px-6 py-3">Manage</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php foreach($products as $i => $product): ?>
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4"><?= $i + 1 ?></td>
          <td class="px-6 py-4 font-medium"><?= htmlspecialchars($product->getName()) ?></td>
          <td class="px-6 py-4">
            <?php if($product->getImage()): ?>
              <img src="<?= htmlspecialchars($product->getImage()) ?>" class="w-12 h-12 object-cover rounded">
            <?php else: ?>
              <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                <i class="fas fa-image text-gray-400"></i>
              </div>
            <?php endif; ?>
          </td>
          <td class="px-6 py-4">
            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
              <?= htmlspecialchars($product->getAction()) ?>
            </span>
          </td>
          <td class="px-6 py-4 font-semibold">$<?= number_format($product->getPrice(),2) ?></td>
          <td class="px-6 py-4">
            <details class="cursor-pointer">
              <summary class="text-sm text-blue-600 hover:text-blue-800">View Parameters</summary>
              <pre class="text-xs mt-2 bg-gray-50 p-2 rounded border max-w-xs overflow-auto"><?= htmlspecialchars(json_encode($product->getActionParams(), JSON_PRETTY_PRINT)) ?></pre>
            </details>
          </td>
          <td class="px-6 py-4 max-w-xs">
            <div class="truncate" title="<?= htmlspecialchars($product->getDescription()) ?>">
              <?= htmlspecialchars($product->getDescription()) ?>
            </div>
          </td>
          <td class="px-6 py-4">
            <div class="flex gap-2">
              <!-- Edit -->
              <button 
                class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded flex items-center gap-1 edit-btn text-sm"
                data-id="<?= $product->getId() ?>"
                data-name="<?= htmlspecialchars($product->getName()) ?>"
                data-image="<?= htmlspecialchars($product->getImage()) ?>"
                data-action="<?= htmlspecialchars($product->getAction()) ?>"
                data-price="<?= $product->getPrice() ?>"
                data-params='<?= json_encode($product->getActionParams()) ?>'
                data-description="<?= htmlspecialchars($product->getDescription()) ?>">
                <i class="fas fa-edit"></i> Edit
              </button>

              <!-- Delete -->
              <form method="post" action="/admin/products/delete" onsubmit="return confirm('Are you sure you want to delete this product?');">
                <input type="hidden" name="id" value="<?= $product->getId() ?>">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded flex items-center gap-1 text-sm">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Add Product Modal -->
  <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Add New Product</h2>
        <button onclick="toggleAddModal()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <form method="post" action="/admin/products/create">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium mb-1">Product Name *</label>
            <input type="text" name="name" required class="w-full border rounded px-3 py-2" placeholder="e.g., Basic Server Plan">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Price *</label>
            <input type="number" name="price" step="0.01" required class="w-full border rounded px-3 py-2" placeholder="0.00">
          </div>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Image URL</label>
          <input type="url" name="image" class="w-full border rounded px-3 py-2" placeholder="https://example.com/image.png">
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Action Type *</label>
          <select name="action" id="addActionSelect" required class="w-full border rounded px-3 py-2" onchange="updateActionParams('add')">
            <option value="">Select an action...</option>
            <option value="CreateServer">Create Server</option>
            <option value="AddResources">Add Resources</option>
            <option value="CreateDatabase">Create Database</option>
            <option value="CreateBackup">Create Backup</option>
            <option value="Custom">Custom Action</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Description</label>
          <textarea name="description" rows="3" class="w-full border rounded px-3 py-2" placeholder="Describe what this product does..."></textarea>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Action Parameters</label>
          <div class="bg-gray-50 p-4 rounded">
            <div id="addActionParamsContainer">
              <p class="text-gray-500 text-sm">Please select an action type to configure parameters.</p>
            </div>
            <input type="hidden" name="params" id="addParamsJson" value="{}">
          </div>
        </div>
        
        <div class="flex justify-end gap-3">
          <button type="button" onclick="toggleAddModal()" class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            <i class="fas fa-plus"></i> Create Product
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Product Modal -->
  <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-screen overflow-y-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Edit Product</h2>
        <button onclick="toggleEditModal()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <form method="post" action="/admin/products/update">
        <input type="hidden" name="id" id="editId">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium mb-1">Product Name *</label>
            <input type="text" name="name" id="editName" required class="w-full border rounded px-3 py-2">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Price *</label>
            <input type="number" name="price" id="editPrice" step="0.01" required class="w-full border rounded px-3 py-2">
          </div>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Image URL</label>
          <input type="url" name="image" id="editImage" class="w-full border rounded px-3 py-2">
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Action Type *</label>
          <select name="action" id="editActionSelect" required class="w-full border rounded px-3 py-2" onchange="updateActionParams('edit')">
            <option value="">Select an action...</option>
            <option value="CreateServer">Create Server</option>
            <option value="AddResources">Add Resources</option>
            <option value="CreateDatabase">Create Database</option>
            <option value="CreateBackup">Create Backup</option>
            <option value="Custom">Custom Action</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Description</label>
          <textarea name="description" id="editDescription" rows="3" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Action Parameters</label>
          <div class="bg-gray-50 p-4 rounded">
            <div id="editActionParamsContainer">
              <p class="text-gray-500 text-sm">Please select an action type to configure parameters.</p>
            </div>
            <input type="hidden" name="params" id="editParamsJson" value="{}">
          </div>
        </div>
        
        <div class="flex justify-end gap-3">
          <button type="button" onclick="toggleEditModal()" class="px-4 py-2 text-gray-600 border rounded hover:bg-gray-50">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            <i class="fas fa-save"></i> Update Product
          </button>
        </div>
      </form>
    </div>
  </div>
  
  <script>
    // Action configurations
    const actionConfigs = {
      'CreateServer': {
        title: 'Create Server Configuration',
        fields: [
          { key: 'egg_id', label: 'Egg ID', type: 'number', required: true, placeholder: 'e.g., 1' },
          { key: 'ram', label: 'RAM (MB)', type: 'number', required: true, placeholder: 'e.g., 1024' },
          { key: 'cpu', label: 'CPU (%)', type: 'number', required: true, placeholder: 'e.g., 100' },
          { key: 'disk', label: 'Disk (MB)', type: 'number', required: true, placeholder: 'e.g., 2048' },
          { key: 'allocations', label: 'Allocations', type: 'number', required: true, placeholder: 'e.g., 1' },
          { key: 'databases', label: 'Databases', type: 'number', required: true, placeholder: 'e.g., 1' },
          { key: 'backups', label: 'Backups', type: 'number', required: true, placeholder: 'e.g., 2' },
          { key: 'node_id', label: 'Node ID', type: 'number', required: false, placeholder: 'e.g., 1' },
          { key: 'startup', label: 'Startup Command', type: 'text', required: false, placeholder: 'Custom startup command' }
        ]
      },
      'AddResources': {
        title: 'Add Resources Configuration',
        fields: [
          { key: 'resource_type', label: 'Resource Type', type: 'select', required: true, options: ['ram', 'cpu', 'disk', 'databases', 'backups', 'allocations'] },
          { key: 'value', label: 'Value', type: 'number', required: true, placeholder: 'Amount to add' },
          { key: 'unit', label: 'Unit', type: 'select', required: true, options: ['MB', 'GB', '%', 'count'] }
        ]
      },
      'CreateDatabase': {
        title: 'Create Database Configuration',
        fields: [
          { key: 'database_name', label: 'Database Name', type: 'text', required: true, placeholder: 'e.g., myapp_db' },
          { key: 'remote', label: 'Remote Access', type: 'select', required: true, options: ['%', 'localhost'] }
        ]
      },
      'CreateBackup': {
        title: 'Create Backup Configuration',
        fields: [
          { key: 'backup_name', label: 'Backup Name', type: 'text', required: false, placeholder: 'Optional backup name' },
          { key: 'ignore_files', label: 'Ignore Files', type: 'text', required: false, placeholder: '*.log,cache/*' }
        ]
      },
      'Custom': {
        title: 'Custom Action Parameters',
        fields: []
      }
    };

    /* MODAL TOGGLE */
    function toggleAddModal(){ 
      document.getElementById('addModal').classList.toggle('hidden'); 
      if (!document.getElementById('addModal').classList.contains('hidden')) {
        document.querySelector('#addModal form').reset();
        document.getElementById('addActionParamsContainer').innerHTML = '<p class="text-gray-500 text-sm">Please select an action type to configure parameters.</p>';
        updateParamsJson('add');
      }
    }
    
    function toggleEditModal(){ 
      document.getElementById('editModal').classList.toggle('hidden'); 
    }

    /* ACTION PARAMETER HANDLER */
    function updateActionParams(type) {
      const select = document.getElementById(type + 'ActionSelect');
      const container = document.getElementById(type + 'ActionParamsContainer');
      const action = select.value;
      
      if (!action || !actionConfigs[action]) {
        container.innerHTML = '<p class="text-gray-500 text-sm">Please select an action type to configure parameters.</p>';
        updateParamsJson(type);
        return;
      }

      const config = actionConfigs[action];
      
      if (action === 'Custom') {
        container.innerHTML = `
          <div class="mb-3">
            <h4 class="font-medium text-sm mb-2">${config.title}</h4>
            <div class="mb-2 flex justify-between items-center">
              <span class="text-sm text-gray-600">Custom Key-Value Pairs</span>
              <button type="button" onclick="addCustomParam('${type}')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                <i class="fas fa-plus"></i> Add Parameter
              </button>
            </div>
            <div id="${type}CustomParamsContainer" class="space-y-2"></div>
          </div>
        `;
      } else {
        let html = `<div class="mb-3"><h4 class="font-medium text-sm mb-3">${config.title}</h4><div class="grid grid-cols-1 md:grid-cols-2 gap-3">`;
        
        config.fields.forEach(field => {
          html += `<div class="form-field" data-key="${field.key}">`;
          html += `<label class="block text-sm font-medium mb-1">${field.label}${field.required ? ' *' : ''}</label>`;
          
          if (field.type === 'select') {
            html += `<select class="w-full border rounded px-3 py-2 param-input" data-key="${field.key}" ${field.required ? 'required' : ''}>`;
            html += `<option value="">Select...</option>`;
            field.options.forEach(option => {
              html += `<option value="${option}">${option}</option>`;
            });
            html += `</select>`;
          } else {
            html += `<input type="${field.type}" class="w-full border rounded px-3 py-2 param-input" data-key="${field.key}" placeholder="${field.placeholder || ''}" ${field.required ? 'required' : ''}>`;
          }
          
          html += `</div>`;
        });
        
        html += `</div></div>`;
        container.innerHTML = html;
      }
      
      // Add event listeners
      container.querySelectorAll('.param-input').forEach(input => {
        input.addEventListener('input', () => updateParamsJson(type));
        input.addEventListener('change', () => updateParamsJson(type));
      });
      
      updateParamsJson(type);
    }

    function addCustomParam(type) {
      const container = document.getElementById(type + 'CustomParamsContainer');
      const row = document.createElement('div');
      row.className = 'flex gap-2 items-center custom-param-row';
      row.innerHTML = `
        <input type="text" placeholder="Parameter Key" class="border rounded px-3 py-2 flex-1 custom-param-key">
        <input type="text" placeholder="Parameter Value" class="border rounded px-3 py-2 flex-1 custom-param-value">
        <button type="button" onclick="this.parentElement.remove(); updateParamsJson('${type}')" class="text-red-500 hover:text-red-700 px-2">
          <i class="fas fa-trash"></i>
        </button>
      `;
      container.appendChild(row);
      
      // Add event listeners
      row.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', () => updateParamsJson(type));
      });
      
      updateParamsJson(type);
    }

    function updateParamsJson(type) {
      const container = document.getElementById(type + 'ActionParamsContainer');
      const params = {};

      // Handle predefined fields
      container.querySelectorAll('.param-input').forEach(input => {
        const key = input.dataset.key;
        const value = input.value.trim();
        if (key && value) {
          // Convert numeric values
          if (input.type === 'number') {
            params[key] = parseFloat(value) || 0;
          } else {
            params[key] = value;
          }
        }
      });

      // Handle custom fields
      const customKeys = container.querySelectorAll('.custom-param-key');
      const customValues = container.querySelectorAll('.custom-param-value');
      customKeys.forEach((keyInput, i) => {
        const key = keyInput.value.trim();
        const value = customValues[i].value.trim();
        if (key && value) {
          params[key] = value;
        }
      });

      const jsonStr = JSON.stringify(params);
      document.getElementById(type + 'ParamsJson').value = jsonStr;
    }

    /* EDIT BUTTONS */
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('editId').value = btn.dataset.id;
        document.getElementById('editName').value = btn.dataset.name;
        document.getElementById('editImage').value = btn.dataset.image;
        document.getElementById('editPrice').value = btn.dataset.price;
        document.getElementById('editDescription').value = btn.dataset.description;
        
        // Set action and trigger params update
        document.getElementById('editActionSelect').value = btn.dataset.action;
        updateActionParams('edit');
        
        // Populate existing params
        setTimeout(() => {
          const params = JSON.parse(btn.dataset.params || '{}');
          const container = document.getElementById('editActionParamsContainer');
          
          // Fill predefined fields
          container.querySelectorAll('.param-input').forEach(input => {
            const key = input.dataset.key;
            if (params[key] !== undefined) {
              input.value = params[key];
            }
          });
          
          // Handle custom parameters
          if (btn.dataset.action === 'Custom') {
            Object.keys(params).forEach(key => {
              addCustomParam('edit');
              const rows = container.querySelectorAll('.custom-param-row');
              const lastRow = rows[rows.length - 1];
              lastRow.querySelector('.custom-param-key').value = key;
              lastRow.querySelector('.custom-param-value').value = params[key];
            });
          }
          
          updateParamsJson('edit');
        }, 100);
        
        toggleEditModal();
      });
    });

    // Close modals when clicking outside
    document.addEventListener('click', (e) => {
      if (e.target.id === 'addModal') toggleAddModal();
      if (e.target.id === 'editModal') toggleEditModal();
    });
  </script>
</div>