<?php

// Autoload Composer classes and helpers
require_once '../../../vendor/autoload.php';  // Composer autoload
require_once '../../../src/Controllers/JwtController.php';
require_once '../../../src/Controllers/FavoriteController.php';
require_once '../../../src/Services/FavoriteService.php';  

use App\Controllers\JwtController;
use App\Controllers\FavoriteController;
use App\Services\FavoriteService;

// Retrieve the JWT from the Authorization header
$headers = apache_request_headers();

$jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if ($jwt) {
    try {
        // Decode the JWT to get the user ID
        $jwtController = new JwtController();
        $decoded =  $jwtController->decode($jwt);
        $userId = $decoded['sub'];

        $favoriteService = new FavoriteService();
        $favoriteController = new FavoriteController($favoriteService);
        $favorites = $favoriteController->getFavorites($userId);
    
        // Assuming you have a valid $pdo connection
        if ($favorites) {
            echo json_encode($favorites);
        } else {
            echo json_encode(['error' => 'Database connection failed']);
        }
    } catch (Exception $e) {
        // If the JWT is invalid or expired, return an error
        echo json_encode(['error' => 'Unauthorized']);
    }
} else {
    echo json_encode(['error' => 'Token not provided']);
}
?>
