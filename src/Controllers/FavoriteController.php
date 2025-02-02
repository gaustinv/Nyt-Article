<?php
namespace App\Controllers;

use App\Database\Database;
use App\Services\FavoriteService;
use PDO;

class FavoriteController {
    private $db;
    protected $favoriteService;

    /**
     * Constructor for FavoriteController.
     *
     * @param FavoriteService $favoriteService
     */
    public function __construct(FavoriteService $favoriteService) {
        $this->db = Database::getInstance()->getConnection();
        $this->favoriteService = $favoriteService;
    }

    /**
     * Adds an article to the user's favorites.
     *
     * @param int $userId
     * @param int $articleId
     * @param string $title
     * @param string $url
     *
     * @return array
     *
     * @throws \Exception
     */
    public function addToFavorites($userId, $articleId, $title, $url) {

        $result = $this->favoriteService->addFavorite($userId, $articleId, $title, $url);
        if ($result) {
            return ["message" => "Article added to favorites."];
        } else {
            http_response_code(500);
            return ["error" => "Failed to add article."];
        }
    }

    /**
     * Retrieves the user's favorites.
     *
     * @param int $userId
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getFavorites($userId, $limit, $offset) {
        return $this->favoriteService->getFavorites($userId, $limit, $offset);
    }
    
    /**
     * Retrieves the total count of the user's favorites.
     *
     * @param int $userId
     *
     * @return int
     */
    public function getFavoritesCount($userId) {
        return $this->favoriteService->getFavoritesCount($userId);
    }
    

    /**
     * Removes an article from the user's favorites.
     *
     * @param int $userId
     * @param int $favoriteId
     *
     * @return array
     *
     * @throws \Exception
     */
    public function removeFromFavorites($userId, $favoriteId) {
        $result =  $this->favoriteService->removeFavorite($userId, $favoriteId);
       
        if ($result) {
            return ["message" => "Article removed from favorites."];
        } else {
            http_response_code(500);
            return ["error" => "Failed to remove article."];
        }
    }
}
