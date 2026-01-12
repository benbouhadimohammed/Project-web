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

// Récupérer les données JSON envoyées
$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)($input['product_id'] ?? 0);
$quantity = (int)($input['quantity'] ?? 1);

// Validation des données
if ($productId <= 0 || $quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID or quantity'
    ]);
    exit;
}

try {
    // Vérifier que le produit existe dans la base de données
    $stmt = query("SELECT id, name, price, stock FROM products WHERE id = ?", [$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
        exit;
    }
    
    // Vérifier le stock disponible
    if ($product['stock'] < $quantity) {
        echo json_encode([
            'success' => false,
            'message' => 'Insufficient stock'
        ]);
        exit;
    }
    
    // Initialiser le panier dans la session si il n'existe pas
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Ajouter ou mettre à jour la quantité dans le panier
    if (isset($_SESSION['cart'][$productId])) {
        // Le produit existe déjà, on augmente la quantité
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Nouveau produit, on l'ajoute
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'quantity' => $quantity
        ];
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product added to cart successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error'
    ]);
}
?>