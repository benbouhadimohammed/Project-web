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

try {
    // Récupérer le panier de la session (ou tableau vide si inexistant)
    $cart = $_SESSION['cart'] ?? [];
    
    $cartItems = [];
    $grandTotal = 0;
    
    // Parcourir chaque article du panier
    foreach ($cart as $productId => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $grandTotal += $subtotal;
        
        $cartItems[] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'subtotal' => $subtotal
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $cartItems,
        'grand_total' => $grandTotal,
        'item_count' => count($cartItems)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error'
    ]);
}
?>