<?php
function handleGet($route) {
    global $db;
    
    switch($route) {
        case '/products':
            $stmt = $db->query("SELECT * FROM products");
            return $stmt->fetchAll();
            
        case '/users':
            $stmt = $db->query("SELECT id, username, role FROM users");
            return $stmt->fetchAll();
            
        case '/sales':
            $stmt = $db->query("
                SELECT s.*, p.name as product_name 
                FROM sales s 
                JOIN products p ON s.product_id = p.id
            ");
            return $stmt->fetchAll();
            
        default:
            return ['error' => 'Route not found'];
    }
}
