<?php
function handlePost($route, $data) {
    global $db, $auth;
    
    switch($route) {
        case '/login':
            return $auth->validateLogin($data['username'], $data['password']);
            
        case '/products':
            $stmt = $db->prepare("
                INSERT INTO products (name, price, stock, image_url) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['name'],
                $data['price'],
                $data['stock'],
                $data['image_url'] ?? null
            ]);
            return ['success' => true, 'id' => $db->lastInsertId()];
            
        case '/users':
            $stmt = $db->prepare("
                INSERT INTO users (username, password, role) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['role'] ?? 'user'
            ]);
            return ['success' => true, 'id' => $db->lastInsertId()];
            
        case '/sales':
            $stmt = $db->prepare("
                INSERT INTO sales (product_id, quantity, total_price) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $data['product_id'],
                $data['quantity'],
                $data['total_price']
            ]);
            return ['success' => true, 'id' => $db->lastInsertId()];
            
        default:
            return ['error' => 'Route not found'];
    }
}
