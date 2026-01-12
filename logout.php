<?php
header('Content-Type: application/json');
require_once '../includes/session.php';

if (!isLoggedIn()) {
    echo json_encode([
        'success' => false,
        'message' => 'No active session'
    ]);
    exit;
}

logoutUser();

echo json_encode([
    'success' => true,
    'message' => 'Logout successful'
]);
?>