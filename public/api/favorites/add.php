<?php
// Autoload Composer classes and helpers
require_once '../../../vendor/autoload.php';  // Composer autoload
require_once '../../../src/Controllers/JwtController.php';
require_once '../../../src/Controllers/FavoriteController.php';
require_once '../../../src/Services/FavoriteService.php';

use App\Controllers\JwtController;
use App\Controllers\FavoriteController;
use App\Services\FavoriteService;

// Set response headers
header('Content-Type: application/json');

try {
    // Retrieve the JWT from the Authorization header
    $headers = apache_request_headers();
    $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

    if (!$jwt) {
        throw new Exception('Token not provided');
    }

    // Decode the JWT to get the user ID
    $jwtController = new JwtController();
    $decoded = $jwtController->decode($jwt);
    $userId = $decoded['sub']; // Assuming the user ID is stored in 'sub'

    // Get POST data
    $postData = json_decode(file_get_contents("php://input"), true);
    $articleId = isset($postData['article_id']) ? intval($postData['article_id']) : 0;
    $webUrl = isset($postData['web_url']) ? trim($postData['web_url']) : '';
    $title = isset($postData['title']) ? trim($postData['title']) : '';

    // Validate input
    if (empty($webUrl)) {
        throw new Exception('Invalid input: web_url are required');
    }

        $favoriteService = new FavoriteService();
        $favoriteController = new FavoriteController($favoriteService);
        $response = $favoriteController->addToFavorites($userId, $articleId, $title, $webUrl);

    // Validate response
    if (!is_array($response)) {
        throw new Exception('Invalid response format from FavoriteController');
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
