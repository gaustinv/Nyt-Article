<?php
namespace App\Services;

use App\Models\Favorite;
use GuzzleHttp\Client;
use Exception;

class ArticleService {
    private $client;
    private $favoriteModel;

    public function __construct() {
        $this->client = new Client();
        $this->favoriteModel = new Favorite();
    }

    // Fetch articles from NYT API
    public function fetchArticles($query, $page = 1, $apiKey, $apiLink) {
        $url = $apiLink;
        $apiKey = $apiKey;  // Use a valid NYT API key

        $response = $this->client->request('GET', $url, [
            'query' => [
                'q' => $query,
                'page' => $page,
                'api-key' => $apiKey
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['response']['docs'];
    }

    // Retrieve article details
    public function getArticleDetails($articleId) {
        // This is just a placeholder logic for fetching article details
        return $this->client->request('GET', "https://api.nytimes.com/svc/search/v2/articlesearch.json", [
            'query' => [
                'articleId' => $articleId,
                'api-key' => 'your-nyt-api-key'
            ]
        ]);
    }
}
