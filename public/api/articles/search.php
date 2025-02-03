<?php
// Autoload Composer classes and helpers
require_once '../../../vendor/autoload.php';  // Composer autoload
require_once '../../../src/Controllers/JwtController.php';
require_once '../../../src/Controllers/ArticleController.php';
require_once '../../../src/Services/ArticleService.php';

use App\Controllers\JwtController;
use App\Controllers\ArticleController;
use App\Services\ArticleService;

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

    // Get search query and page number
    $query = $_GET['query'] ?? '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $itemsPerPage = 10;

    // Instantiate ArticleService and ArticleController
    $articleService = new ArticleService();
    $articleController = new ArticleController($articleService);

    // Fetch articles
    $response = $articleController->searchArticles($query, $page, $userId);

    // Validate response
    if (!is_array($response)) {
        throw new Exception('Invalid response format from ArticleController');
    }

    echo json_encode([
        'articles' => array_slice($response, 0, 20), // Limit to 20 articles
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
