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

    /**
     * Fetches articles from the New York Times API based on a query and page number
     *
     * @param string $query The search query
     * @param int $page The page number to fetch (starts at 1)
     * @param string $apiKey The New York Times API key
     * @param string $apiLink The New York Times API link
     *
     * @return array An array containing the articles' details
     * @throws Exception If the API request fails
     */
    public function fetchArticles($query, $page = 1, $apiKey, $apiLink) {
        $url = $apiLink;
        $apiKey = $apiKey;

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
}
