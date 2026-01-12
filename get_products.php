<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/session.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized - Please login'
    ]);
    exit;
}

try {
    $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
    
    if ($categoryId) {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.category_id, 
                       p.stock, p.image_url, c.name as category_name
                FROM products p
                INNER JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = ?
                ORDER BY p.name ASC";
        $stmt = query($sql, [$categoryId]);
    } else {
        $sql = "SELECT p.id, p.name, p.description, p.price, p.category_id, 
                       p.stock, p.image_url, c.name as category_name
                FROM products p
                INNER JOIN categories c ON p.category_id = c.id
                ORDER BY p.name ASC";
        $stmt = query($sql);
    }
    
    $products = $stmt->fetchAll();
    
    foreach ($products as &$product) {
        $product['price'] = (float)$product['price'];
        $product['stock'] = (int)$product['stock'];
        $product['category_id'] = (int)$product['category_id'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $products,
        'count' => count($products)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch products'
    ]);
}
?>
