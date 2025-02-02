<?php
namespace App\Controllers;

use GuzzleHttp\Client;
use App\Services\ArticleService;

$configPath = realpath('../../../config/config.php');
$config = require $configPath;
class ArticleController {
    private $articleService;
    private $nytKey;
    private $nytLink;
    public function __construct(ArticleService $articleService) {
        global $config;
        if (!isset($config['nyt_api'])) {
            print_r($config['nyt_api'] );
            die("Configuration missing.");
        }
        $this->articleService = $articleService;
        $this->nytKey = $config['nyt_api']['api_key'] ?? null;
        $this->nytLink = $config['nyt_api']['base_url'] ?? null;

        if (!$this->nytKey || !$this->nytLink) {
            die("NYT API configuration is missing or invalid.");
        }
    }
    
    /**
     * Searches for articles based on a query and page number
     *
     * @param string $query The search query
     * @param int $page The page number to fetch (starts at 1)
     *
     * @return array An array containing the articles' details, or an error message
     */
    public function searchArticles($query, $page = 1) {
        try {
            $articles = $this->articleService->fetchArticles($query, $page, $this->nytKey, $this->nytLink);
            return $articles;
        } catch (\Exception $e) {
            return ["error" => "Failed to fetch articles."];
        }
    }
}
