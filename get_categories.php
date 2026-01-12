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
    $stmt = query("SELECT id, name, description FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'data' => $categories
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch categories'
    ]);
}
?>