<?php
header('Content-Type: application/json');
require_once '../config/database.php';
require_once '../includes/session.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized - Please login'
    ]);
    exit;
}

// Vérifier que c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed - Use POST'
    ]);
    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)($input['product_id'] ?? 0);

if ($productId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
    exit;
}

try {
    // Vérifier si le produit existe dans le panier
    if (isset($_SESSION['cart'][$productId])) {
        // Supprimer le produit du panier
        unset($_SESSION['cart'][$productId]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Product removed from cart successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found in cart'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error'
    ]);
}
?>