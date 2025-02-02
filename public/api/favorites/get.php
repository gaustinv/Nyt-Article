<?php

require_once '../../../vendor/autoload.php';
require_once '../../../src/Controllers/JwtController.php';
require_once '../../../src/Controllers/FavoriteController.php';
require_once '../../../src/Services/FavoriteService.php';

use App\Controllers\JwtController;
use App\Controllers\FavoriteController;
use App\Services\FavoriteService;

$headers = apache_request_headers();
$jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if ($jwt) {
    try {
        $jwtController = new JwtController();
        $decoded =  $jwtController->decode($jwt);
        $userId = $decoded['sub'];

        $favoriteService = new FavoriteService();
        $favoriteController = new FavoriteController($favoriteService);

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
        $offset = ($page - 1) * $limit;

        $favorites = $favoriteController->getFavorites($userId, $limit, $offset);
        $total = $favoriteController->getFavoritesCount($userId);

        echo json_encode(['data' => $favorites, 'total' => $total]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Unauthorized']);
    }
} else {
    echo json_encode(['error' => 'Token not provided']);
}
?>
