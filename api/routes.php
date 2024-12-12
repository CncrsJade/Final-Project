<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once("./config/Connection.php");

try {
    // Test database connection
    $db->query("SELECT 1");

    $fullUri = $_SERVER['REQUEST_URI'];
    $route = parse_url($fullUri, PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Log request details
    error_log("Full URI: " . $fullUri);
    error_log("Route before cleanup: " . $route);
    
    // Clean up route - remove both /Final-Project/api and /api prefixes
    $route = preg_replace('/^\/Final-Project\/api\/api/', '', $route);
    $route = preg_replace('/^\/Final-Project\/api/', '', $route);
    $route = preg_replace('/^\/api/', '', $route);
    
    error_log("Route after cleanup: " . $route);
    
    // Handle routes
    switch ($method) {
        case 'GET':
            switch ($route) {
                case '/test':
                case 'test':
                    $response = [
                        'success' => true,
                        'message' => 'API is working',
                        'time' => date('Y-m-d H:i:s'),
                        'request_uri' => $_SERVER['REQUEST_URI'],
                        'route' => $route
                    ];
                    break;

                case '/products':
                case 'products':
                    $stmt = $db->query("
                        SELECT p.* 
                        FROM products p
                        WHERE p.status = 'active'
                        ORDER BY p.created_at DESC
                    ");
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    break;

                case '/product':
                    if (!isset($_GET['id'])) {
                        throw new Exception('Product ID is required');
                    }
                    $stmt = $db->prepare("
                        SELECT p.*, 
                        COALESCE(SUM(s.quantity), 0) as total_sales
                        FROM products p
                        LEFT JOIN sales s ON p.id = s.product_id
                        WHERE p.id = ?
                        GROUP BY p.id
                    ");
                    $stmt->execute([$_GET['id']]);
                    $response = $stmt->fetch();
                    if (!$response) {
                        throw new Exception('Product not found');
                    }
                    break;

                case '/users':
                    $stmt = $db->query("SELECT id, username, role FROM users");
                    $response = $stmt->fetchAll();
                    break;

                case '/sales':
                case 'sales':
                    $stmt = $db->query("
                        SELECT s.*, p.name as product_name 
                        FROM sales s 
                        JOIN products p ON s.product_id = p.id
                        ORDER BY s.created_at DESC
                    ");
                    $response = $stmt->fetchAll();
                    break;

                case '/inventory':
                case 'inventory':
                    // First check if the table exists
                    $stmt = $db->query("
                        SELECT COUNT(*) 
                        FROM information_schema.tables 
                        WHERE table_schema = 'bakery_db' 
                        AND table_name = 'inventory_logs'
                    ");
                    
                    if ($stmt->fetchColumn() == 0) {
                        // Table doesn't exist, return empty array
                        $response = [];
                    } else {
                        // Table exists, get the data
                        $stmt = $db->query("
                            SELECT il.*, p.name as product_name, u.username as user_name
                            FROM inventory_logs il
                            JOIN products p ON il.product_id = p.id
                            LEFT JOIN users u ON il.user_id = u.id
                            ORDER BY il.created_at DESC
                        ");
                        $response = $stmt->fetchAll();
                    }
                    break;

                case '/dashboard':
                case 'dashboard':
                    try {
                        // Get total active products
                        $stmt = $db->query("
                            SELECT COUNT(*) as total
                            FROM products 
                            WHERE status = 'active'
                        ");
                        $totalProducts = $stmt->fetchColumn();

                        // Get total categories
                        $stmt = $db->query("
                            SELECT COUNT(DISTINCT category) as total
                            FROM products 
                            WHERE status = 'active'
                            AND category IS NOT NULL
                        ");
                        $totalCategories = $stmt->fetchColumn();

                        // Get low stock items (less than 10)
                        $stmt = $db->query("
                            SELECT COUNT(*) as total
                            FROM products 
                            WHERE status = 'active' 
                            AND stock < 10
                        ");
                        $lowStockItems = $stmt->fetchColumn();

                        $response = [
                            'success' => true,
                            'data' => [
                                'totalProducts' => intval($totalProducts),
                                'totalCategories' => intval($totalCategories),
                                'lowStockItems' => intval($lowStockItems)
                            ]
                        ];
                    } catch (Exception $e) {
                        error_log("Dashboard Error: " . $e->getMessage());
                        throw new Exception('Error fetching dashboard data');
                    }
                    break;

                case '/debug/product':
                    if (!isset($_GET['id'])) {
                        throw new Exception('Product ID is required');
                    }
                    $stmt = $db->prepare("
                        SELECT id, name, status, updated_at 
                        FROM products 
                        WHERE id = ?
                    ");
                    $stmt->execute([$_GET['id']]);
                    $response = $stmt->fetch();
                    if (!$response) {
                        throw new Exception('Product not found');
                    }
                    break;

                case '/categories':
                    try {
                        $stmt = $db->query("
                            SELECT 
                                c.*,
                                COUNT(p.id) as product_count
                            FROM categories c
                            LEFT JOIN products p ON c.name = p.category AND p.status = 'active'
                            WHERE c.status = 'active'
                            GROUP BY c.id
                            ORDER BY c.name ASC
                        ");
                        $response = $stmt->fetchAll();
                    } catch (Exception $e) {
                        error_log("Categories Error: " . $e->getMessage());
                        throw new Exception('Error fetching categories');
                    }
                    break;

                default:
                    http_response_code(404);
                    throw new Exception('Route not found: ' . $route);
            }
            break;

        case 'POST':
            $rawData = file_get_contents("php://input");
            error_log("Raw request data: " . $rawData);
            
            $data = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data: ' . json_last_error_msg());
            }
            
            error_log("Parsed request data: " . print_r($data, true));

            // Remove leading slash if present
            $route = ltrim($route, '/');

            switch ($route) {
                case 'register':
                    // Validate required fields
                    if (empty($data['username']) || empty($data['password'])) {
                        throw new Exception('Username and password are required');
                    }
                    
                    // Check username length
                    if (strlen($data['username']) < 3) {
                        throw new Exception('Username must be at least 3 characters long');
                    }
                    
                    // Check if username exists
                    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                    $stmt->execute([$data['username']]);
                    if ($stmt->fetch()) {
                        throw new Exception('Username already exists');
                    }
                    
                    // Create user
                    $stmt = $db->prepare("
                        INSERT INTO users (username, password, role) 
                        VALUES (?, ?, 'user')
                    ");
                    
                    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                    $stmt->execute([$data['username'], $hashedPassword]);
                    
                    $userId = $db->lastInsertId();
                    $response = [
                        'success' => true,
                        'user' => [
                            'id' => $userId,
                            'username' => $data['username'],
                            'role' => 'user'
                        ]
                    ];
                    break;

                case 'login':
                    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
                    $stmt->execute([$data['username']]);
                    $user = $stmt->fetch();
                    
                    if ($user && $data['password'] === $user['password']) { // Temporarily removed password_verify for testing
                        $response = [
                            'success' => true,
                            'user' => [
                                'id' => $user['id'],
                                'username' => $user['username'],
                                'role' => $user['role']
                            ]
                        ];
                    } else {
                        throw new Exception('Invalid credentials');
                    }
                    break;

                case 'products':
                case 'product':
                    try {
                        // Validate required fields
                        if (empty($data['name']) || !isset($data['price']) || !isset($data['stock'])) {
                            throw new Exception('Name, price and stock are required');
                        }
                        
                        // Begin transaction
                        $db->beginTransaction();
                        
                        $stmt = $db->prepare("
                            INSERT INTO products (
                                name, 
                                description, 
                                price, 
                                stock, 
                                category, 
                                image_url,
                                status
                            ) VALUES (?, ?, ?, ?, ?, ?, 'active')
                        ");
                        
                        $result = $stmt->execute([
                            $data['name'],
                            $data['description'] ?? null,
                            $data['price'],
                            $data['stock'],
                            $data['category'] ?? null,
                            $data['image_url'] ?? null
                        ]);
                        
                        if (!$result) {
                            throw new Exception('Failed to create product');
                        }
                        
                        $productId = $db->lastInsertId();
                        
                        $db->commit();
                        
                        // Fetch the created product
                        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
                        $stmt->execute([$productId]);
                        $product = $stmt->fetch();
                        
                        $response = [
                            'success' => true,
                            'message' => 'Product created successfully',
                            'product' => $product
                        ];
                        
                    } catch (Exception $e) {
                        $db->rollBack();
                        error_log("Product creation error: " . $e->getMessage());
                        throw new Exception('Failed to create product: ' . $e->getMessage());
                    }
                    break;

                case 'categories':
                    try {
                        if (empty($data['name'])) {
                            throw new Exception('Category name is required');
                        }

                        $stmt = $db->prepare("
                            INSERT INTO categories (name, description)
                            VALUES (?, ?)
                        ");
                        
                        $result = $stmt->execute([
                            $data['name'],
                            $data['description'] ?? null
                        ]);
                        
                        if (!$result) {
                            throw new Exception('Failed to create category');
                        }
                        
                        $categoryId = $db->lastInsertId();
                        
                        // Fetch the created category
                        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
                        $stmt->execute([$categoryId]);
                        $category = $stmt->fetch();
                        
                        $response = [
                            'success' => true,
                            'message' => 'Category created successfully',
                            'category' => $category
                        ];
                    } catch (Exception $e) {
                        error_log("Category creation error: " . $e->getMessage());
                        throw new Exception('Failed to create category: ' . $e->getMessage());
                    }
                    break;

                case 'users':
                    try {
                        // Validate required fields
                        if (empty($data['username']) || empty($data['password'])) {
                            throw new Exception('Username and password are required');
                        }
                        
                        // Validate role
                        if (!isset($data['role']) || !in_array($data['role'], ['user', 'admin'])) {
                            throw new Exception('Invalid role');
                        }
                        
                        // Check if username exists
                        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
                        $stmt->execute([$data['username']]);
                        if ($stmt->fetch()) {
                            throw new Exception('Username already exists');
                        }
                        
                        // Create user
                        $stmt = $db->prepare("
                            INSERT INTO users (username, password, role) 
                            VALUES (?, ?, ?)
                        ");
                        
                        $result = $stmt->execute([
                            $data['username'],
                            $data['password'], // Note: In production, you should hash the password
                            $data['role']
                        ]);
                        
                        if (!$result) {
                            throw new Exception('Failed to create user');
                        }
                        
                        $userId = $db->lastInsertId();
                        
                        // Fetch the created user
                        $stmt = $db->prepare("SELECT id, username, role, created_at FROM users WHERE id = ?");
                        $stmt->execute([$userId]);
                        $user = $stmt->fetch();
                        
                        $response = [
                            'success' => true,
                            'message' => 'User created successfully',
                            'user' => $user
                        ];
                    } catch (Exception $e) {
                        error_log("User creation error: " . $e->getMessage());
                        throw new Exception('Failed to create user: ' . $e->getMessage());
                    }
                    break;

                case 'account/password':
                    try {
                        if (!isset($data['userId']) || !isset($data['currentPassword']) || !isset($data['newPassword'])) {
                            throw new Exception('User ID, current password and new password are required');
                        }

                        // Get user's current password
                        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
                        $stmt->execute([$data['userId']]);
                        $user = $stmt->fetch();

                        if (!$user) {
                            throw new Exception('User not found');
                        }

                        // Verify current password
                        if ($user['password'] !== $data['currentPassword']) { // In production, use password_verify()
                            throw new Exception('Current password is incorrect');
                        }

                        // Update password
                        $stmt = $db->prepare("
                            UPDATE users 
                            SET 
                                password = ?,
                                updated_at = CURRENT_TIMESTAMP 
                            WHERE id = ?
                        ");

                        $result = $stmt->execute([
                            $data['newPassword'], // In production, use password_hash()
                            $data['userId']
                        ]);

                        if (!$result) {
                            throw new Exception('Failed to update password');
                        }

                        $response = [
                            'success' => true,
                            'message' => 'Password updated successfully'
                        ];
                    } catch (Exception $e) {
                        error_log("Password update error: " . $e->getMessage());
                        throw new Exception('Failed to update password: ' . $e->getMessage());
                    }
                    break;

                default:
                    http_response_code(404);
                    throw new Exception('Route not found: ' . $route);
            }
            break;

        case 'PUT':
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, true);
            
            switch ($route) {
                case 'product':
                    if (!isset($data['id'])) {
                        throw new Exception('Product ID is required');
                    }
                    
                    $fields = [];
                    $values = [];
                    
                    // Build dynamic update query
                    if (isset($data['name'])) {
                        $fields[] = 'name = ?';
                        $values[] = $data['name'];
                    }
                    if (isset($data['description'])) {
                        $fields[] = 'description = ?';
                        $values[] = $data['description'];
                    }
                    if (isset($data['price'])) {
                        $fields[] = 'price = ?';
                        $values[] = $data['price'];
                    }
                    if (isset($data['category'])) {
                        $fields[] = 'category = ?';
                        $values[] = $data['category'];
                    }
                    if (isset($data['image_url'])) {
                        $fields[] = 'image_url = ?';
                        $values[] = $data['image_url'];
                    }
                    if (isset($data['status'])) {
                        $fields[] = 'status = ?';
                        $values[] = $data['status'];
                    }
                    
                    // Handle stock changes
                    if (isset($data['stock_change'])) {
                        $stmt = $db->prepare("
                            UPDATE products 
                            SET stock = stock + ? 
                            WHERE id = ?
                        ");
                        $stmt->execute([$data['stock_change'], $data['id']]);
                        
                        // Log inventory change
                        $stmt = $db->prepare("
                            INSERT INTO inventory_logs (
                                product_id, 
                                quantity_change, 
                                type, 
                                reason, 
                                user_id
                            ) VALUES (?, ?, ?, ?, ?)
                        ");
                        
                        $stmt->execute([
                            $data['id'],
                            abs($data['stock_change']),
                            $data['stock_change'] > 0 ? 'in' : 'out',
                            $data['reason'] ?? 'Stock adjustment',
                            $data['user_id'] ?? null
                        ]);
                    }
                    
                    if (!empty($fields)) {
                        $values[] = $data['id'];
                        $stmt = $db->prepare("
                            UPDATE products 
                            SET " . implode(', ', $fields) . ", 
                            updated_at = CURRENT_TIMESTAMP 
                            WHERE id = ?
                        ");
                        $stmt->execute($values);
                    }
                    
                    $response = [
                        'success' => true,
                        'message' => 'Product updated successfully'
                    ];
                    break;

                case (preg_match('/^\/users\/(\d+)$/', $route, $matches) ? true : false):
                    try {
                        $userId = $matches[1];
                        
                        if (!isset($data['role'])) {
                            throw new Exception('Role is required');
                        }
                        
                        // Validate role
                        if (!in_array($data['role'], ['user', 'admin'])) {
                            throw new Exception('Invalid role');
                        }
                        
                        $stmt = $db->prepare("
                            UPDATE users 
                            SET role = ?, 
                                updated_at = CURRENT_TIMESTAMP 
                            WHERE id = ?
                        ");
                        
                        $result = $stmt->execute([$data['role'], $userId]);
                        
                        if (!$result) {
                            throw new Exception('Failed to update user role');
                        }
                        
                        $response = [
                            'success' => true,
                            'message' => 'User role updated successfully'
                        ];
                    } catch (Exception $e) {
                        error_log("Update user role error: " . $e->getMessage());
                        throw new Exception('Failed to update user role: ' . $e->getMessage());
                    }
                    break;

                default:
                    throw new Exception('Route not found');
            }
            break;

        case 'DELETE':
            // Remove leading slash if present
            $route = ltrim($route, '/');
            error_log("DELETE request for route: " . $route . " with ID: " . ($_GET['id'] ?? 'none'));
            
            switch ($route) {
                case 'product':
                case 'products':
                    if (!isset($_GET['id'])) {
                        throw new Exception('Product ID is required');
                    }
                    
                    try {
                        $db->beginTransaction();
                        
                        // First check if product exists and is active
                        $stmt = $db->prepare("
                            SELECT id, status 
                            FROM products 
                            WHERE id = ?
                        ");
                        $stmt->execute([$_GET['id']]);
                        $product = $stmt->fetch();
                        
                        if (!$product) {
                            throw new Exception('Product not found');
                        }
                        
                        // Soft delete - update status
                        $stmt = $db->prepare("
                            UPDATE products 
                            SET 
                                status = 'inactive',
                                updated_at = CURRENT_TIMESTAMP 
                            WHERE id = ?
                        ");
                        
                        $result = $stmt->execute([$_GET['id']]);
                        error_log("Delete query executed. Result: " . ($result ? 'true' : 'false'));
                        
                        if (!$result) {
                            throw new Exception('Failed to delete product');
                        }
                        
                        // Verify the update
                        $stmt = $db->prepare("SELECT status FROM products WHERE id = ?");
                        $stmt->execute([$_GET['id']]);
                        $updatedProduct = $stmt->fetch();
                        error_log("Product status after update: " . ($updatedProduct['status'] ?? 'unknown'));
                        
                        $db->commit();
                        
                        $response = [
                            'success' => true,
                            'message' => 'Product deleted successfully',
                            'product_id' => $_GET['id']
                        ];
                        
                    } catch (Exception $e) {
                        $db->rollBack();
                        error_log("Delete error: " . $e->getMessage());
                        throw $e;
                    }
                    break;
                    
                default:
                    throw new Exception('Route not found: ' . $route);
            }
            break;

        default:
            throw new Exception('Method not allowed');
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}