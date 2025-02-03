<?php
namespace App\Controllers;

use GuzzleHttp\Client;
use App\Services\ArticleService;
use App\Models\Log;
use App\Utils\Logger;

$configPath = realpath('../../../config/config.php');
$config = require $configPath;

class ArticleController {
    private $articleService;
    private $nytKey;
    private $nytLink;
    private $logModel;

    public function __construct(ArticleService $articleService) {
        global $config;
        $this->articleService = $articleService;
        $this->nytKey = $config['nyt_api']['api_key'] ?? null;
        $this->nytLink = $config['nyt_api']['base_url'] ?? null;
        $this->logModel = new Log();  // Initialize Log model for database logging
    }
    
    /**
     * Searches for articles based on a query and page number
     *
     * @param string $query The search query
     * @param int $page The page number to fetch (starts at 1)
     * @param int|null $userId The ID of the user making the request
     *
     * @return array An array containing the articles' details, or an error message
     */
    public function searchArticles($query, $page = 1, $userId = null) {
        try {
            // Prepare data to log
            $requestData = [
                'query' => $query,
                'page' => $page
            ];

            // Log the request to the file using Logger
            Logger::writeLog($userId ?? 0, "Search Articles Request", $requestData);
            // Log the request to the database using Log model
            $this->logModel->addLog($userId ?? 0, "Search Articles Request: " . json_encode($requestData));

            // Fetch articles from the article service
            $articles = $this->articleService->fetchArticles($query, $page, $this->nytKey, $this->nytLink);

            // Log the response to the file using Logger
            Logger::writeLog($userId ?? 0, "Search Articles Response", $articles);
            // Log the response to the database using Log model
            $this->logModel->addLog($userId ?? 0, "Search Articles Response: " . json_encode($articles));

            return $articles;
        } catch (\Exception $e) {
            $errorResponse = ["error" => "Failed to fetch articles."];

            // Log error response to the file using Logger
            Logger::writeLog($userId ?? 0, "Search Articles Error", ["exception" => $e->getMessage()]);
            // Log error response to the database using Log model
            $this->logModel->addLog($userId ?? 0, "Search Articles Error: " . $e->getMessage());

            return $errorResponse;
        }
    }
}
