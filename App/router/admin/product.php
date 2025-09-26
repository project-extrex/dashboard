<?php

use App\Database\Entities\Products;
use App\Database\Entities\User;

global $entityManager;

// --- Handlers ---
function listProducts($renderer, $entityManager) {
    $products = $entityManager->getRepository(Products::class)->findAll();
    $user = $entityManager->find(User::class, $_SESSION["user_id"]) ?? null;
    $msg = $_GET['msg'] ?? null;
    $error = $_GET['error'] ?? null;

    $renderer->renderAdmin("products", [
        "products" => $products,
        "msg" => $msg,
        "error" => $error,
        "user" => $user
    ]);
}

function createProduct($entityManager, array $data) {
    if (!isset($data['name'], $data['price'], $data['action'])) {
        throw new \Exception("Missing required fields");
    }

    // Validate action type
    $allowedActions = ['CreateServer', 'AddResources', 'CreateDatabase', 'CreateBackup', 'Custom'];
    if (!in_array($data['action'], $allowedActions)) {
        throw new \Exception("Invalid action type");
    }

    $params = [];
    if (!empty($data['params'])) {
        $params = json_decode($data['params'], true);
        if (!is_array($params)) $params = [];
    }

    $description = $data['description'] ?? '';

    // Validate required parameters based on action type
    validateActionParams($data['action'], $params);

    // Fix: Correct parameter order - check your Products constructor
    // Most likely order: __construct($name, $description, $image, $action, $price, $actionParams)
    $product = new Products(
        $data['name'],                    // name
        $data['image'] ?? '',            // image
        $data['action'],                 // action  
        (float) $data['price'],          // price
        (string) $description,           // description 
        $params                          // actionParams (array)
    );

    $entityManager->persist($product);
    $entityManager->flush();
    return $product;
}

function validateActionParams($action, $params) {
    switch ($action) {
        case 'CreateServer':
            $required = ['egg_id', 'ram', 'cpu', 'disk', 'allocations', 'databases', 'backups'];
            foreach ($required as $field) {
                if (!isset($params[$field]) || $params[$field] === '') {
                    throw new \Exception("Missing required parameter: {$field} for CreateServer action");
                }
            }
            break;
            
        case 'AddResources':
            $required = ['resource_type', 'value', 'unit'];
            foreach ($required as $field) {
                if (!isset($params[$field]) || $params[$field] === '') {
                    throw new \Exception("Missing required parameter: {$field} for AddResources action");
                }
            }
            $validResourceTypes = ['ram', 'cpu', 'disk', 'databases', 'backups', 'allocations'];
            if (!in_array($params['resource_type'], $validResourceTypes)) {
                throw new \Exception("Invalid resource type");
            }
            break;
            
        case 'CreateDatabase':
            if (!isset($params['database_name']) || $params['database_name'] === '') {
                throw new \Exception("Database name is required for CreateDatabase action");
            }
            break;
    }
}

function updateProduct($entityManager, int $id, array $data) {
    $repo = $entityManager->getRepository(Products::class);
    $product = $repo->find($id);
    if (!$product) throw new \Exception("Product not found");

    // Update fields if provided
    if (isset($data['name'])) {
        $product->setName($data['name']);
    }
    
    if (isset($data['image'])) {
        $product->setImage($data['image']);
    }
    
    if (isset($data['action'])) {
        $product->setAction($data['action']);
    }
    
    if (isset($data['price'])) {
        $product->setPrice((float)$data['price']);
    }
    
    if (isset($data['description'])) {
        $product->setDescription($data['description']);
    }

    // Handle parameters
    if (isset($data['params'])) {
        $params = [];
        if (!empty($data['params'])) {
            $params = json_decode($data['params'], true);
            if (!is_array($params)) $params = [];
        }
        $product->setActionParams($params);
    }

    $entityManager->flush();
    return $product;
}

function deleteProduct($entityManager, int $id) {
    $repo = $entityManager->getRepository(Products::class);
    $product = $repo->find($id);
    if (!$product) throw new \Exception("Product not found");

    $entityManager->remove($product);
    $entityManager->flush();
}

// --- Routes ---
$router->get('/admin/products', fn() => isAdmin() && listProducts($renderer, $entityManager));

$router->post('/admin/products/create', function() use ($entityManager) {
    if (!isAdmin()) exit;
    try {
        // Debug: Log the POST data to see what we're receiving
        error_log("POST data: " . print_r($_POST, true));
        
        createProduct($entityManager, $_POST);
        header('Location: /admin/products?msg=Product+created+successfully'); 
        exit;
    } catch (\Exception $e) {
        error_log("Create product error: " . $e->getMessage());
        header("Location: /admin/products?error=".urlencode($e->getMessage())); 
        exit;
    }
});

$router->post('/admin/products/update', function() use ($entityManager) {
    if (!isAdmin()) exit;
    try {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new \Exception("Invalid product ID");
        }
        
        updateProduct($entityManager, $id, $_POST);
        header('Location: /admin/products?msg=Product+updated+successfully'); 
        exit;
    } catch (\Exception $e) {
        error_log("Update product error: " . $e->getMessage());
        header("Location: /admin/products?error=".urlencode($e->getMessage())); 
        exit;
    }
});

$router->post('/admin/products/delete', function() use ($entityManager) {
    if (!isAdmin()) exit;
    try {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            throw new \Exception("Invalid product ID");
        }
        
        deleteProduct($entityManager, $id);
        header('Location: /admin/products?msg=Product+deleted+successfully'); 
        exit;
    } catch (\Exception $e) {
        error_log("Delete product error: " . $e->getMessage());
        header("Location: /admin/products?error=".urlencode($e->getMessage())); 
        exit;
    }
});
?>