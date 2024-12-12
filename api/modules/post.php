<?php
function handlePost($route, $data) {
    global $db;
    error_log("Handling POST request for route: " . $route);
    
    switch($route) {
        case '/register':
            return handleRegister($data);
            
        case '/login':
            return handleLogin($data);
            
        case '/products':
            return createProduct($data);
            
        case '/users':
            return createUser($data);
            
        case '/sales':
            return createSale($data);
            
        case '/inventory':
            return updateInventory($data);
            
        default:
            error_log("No matching route found for: " . $route);
            http_response_code(404);
            return ['error' => 'Route not found', 'route' => $route];
    }
}

function handleLogin($data) {
    global $db;
    
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$data['username']]);
    $user = $stmt->fetch();
    
    if ($user && $data['password'] === $user['password']) { // In production, use password_verify()
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]
        ];
    }
    
    http_response_code(401);
    return ['success' => false, 'message' => 'Invalid credentials'];
}

function handleRegister($data) {
    global $db;
    
    try {
        // Validate required fields
        if (empty($data['username']) || empty($data['password'])) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Username and password are required'
            ];
        }
        
        // Check username length
        if (strlen($data['username']) < 3) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Username must be at least 3 characters long'
            ];
        }
        
        // Check if username already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$data['username']]);
        if ($stmt->fetch()) {
            http_response_code(400);
            return [
                'success' => false,
                'message' => 'Username already exists'
            ];
        }
        
        // Insert new user
        $stmt = $db->prepare("
            INSERT INTO users (username, password, role) 
            VALUES (?, ?, 'user')
        ");
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->execute([
            $data['username'],
            $hashedPassword
        ]);
        
        $userId = $db->lastInsertId();
        
        return [
            'success' => true,
            'user' => [
                'id' => $userId,
                'username' => $data['username'],
                'role' => 'user'
            ]
        ];
        
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        http_response_code(500);
        return [
            'success' => false,
            'message' => 'Registration failed: ' . $e->getMessage()
        ];
    }
}

function createProduct($data) {
    global $db;
    
    $stmt = $db->prepare("
        INSERT INTO products (name, description, price, stock, category, status) 
        VALUES (?, ?, ?, ?, ?, 'active')
    ");
    
    $stmt->execute([
        $data['name'],
        $data['description'],
        $data['price'],
        $data['stock'],
        $data['category']
    ]);
    
    return ['success' => true, 'id' => $db->lastInsertId()];
}

function createUser($data) {
    global $db;
    
    $stmt = $db->prepare("
        INSERT INTO users (username, password, role) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([
        $data['username'],
        $data['password'], // In production, use password_hash()
        $data['role']
    ]);
    
    return ['success' => true, 'id' => $db->lastInsertId()];
}

function createSale($data) {
    global $db;
    
    try {
        $db->beginTransaction();
        
        // Create sale record
        $stmt = $db->prepare("
            INSERT INTO sales (product_id, user_id, quantity, unit_price, total_price, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['product_id'],
            $data['user_id'],
            $data['quantity'],
            $data['unit_price'],
            $data['quantity'] * $data['unit_price'],
            $data['payment_method']
        ]);
        
        // Update product stock
        $stmt = $db->prepare("
            UPDATE products 
            SET stock = stock - ? 
            WHERE id = ?
        ");
        
        $stmt->execute([$data['quantity'], $data['product_id']]);
        
        // Log inventory change
        $stmt = $db->prepare("
            INSERT INTO inventory_logs (product_id, quantity_change, type, reason, user_id) 
            VALUES (?, ?, 'out', 'sale', ?)
        ");
        
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['user_id']
        ]);
        
        $db->commit();
        return ['success' => true];
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

function updateInventory($data) {
    global $db;
    
    try {
        $db->beginTransaction();
        
        // Update product stock
        $stmt = $db->prepare("
            UPDATE products 
            SET stock = stock + ? 
            WHERE id = ?
        ");
        
        $stmt->execute([$data['quantity'], $data['product_id']]);
        
        // Log inventory change
        $stmt = $db->prepare("
            INSERT INTO inventory_logs (product_id, quantity_change, type, reason, user_id) 
            VALUES (?, ?, 'in', ?, ?)
        ");
        
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['reason'],
            $data['user_id']
        ]);
        
        $db->commit();
        return ['success' => true];
        
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
