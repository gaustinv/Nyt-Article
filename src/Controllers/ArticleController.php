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

    // Function to handle search for articles
    public function searchArticles($query, $page = 1) {
        try {
            $articles = $this->articleService->fetchArticles($query, $page, $this->nytKey, $this->nytLink);
            return $articles;
        } catch (\Exception $e) {
            return ["error" => "Failed to fetch articles."];
        }
    }
}
