<?php
function handleGet($route) {
    global $db;
    
    switch($route) {
        case '/api/products':
            $stmt = $db->query("SELECT * FROM products WHERE status = 'active'");
            return $stmt->fetchAll();
            
        case '/api/users':
            $stmt = $db->query("SELECT id, username, role FROM users");
            return $stmt->fetchAll();
            
        case '/api/sales':
            $stmt = $db->query("
                SELECT s.*, p.name as product_name 
                FROM sales s 
                JOIN products p ON s.product_id = p.id
                ORDER BY s.transaction_date DESC
            ");
            return $stmt->fetchAll();
            
        case '/api/inventory':
            $stmt = $db->query("
                SELECT il.*, p.name as product_name, u.username as user_name
                FROM inventory_logs il
                JOIN products p ON il.product_id = p.id
                LEFT JOIN users u ON il.user_id = u.id
                ORDER BY il.created_at DESC
            ");
            return $stmt->fetchAll();
            
        case '/test':
            return [
                'success' => true,
                'message' => 'API is working',
                'time' => date('Y-m-d H:i:s'),
                'request_uri' => $_SERVER['REQUEST_URI'],
                'route' => $route
            ];
            
        default:
            http_response_code(404);
            return ['error' => 'Route not found'];
    }
}
