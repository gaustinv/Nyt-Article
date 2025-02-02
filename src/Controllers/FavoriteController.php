<?php
namespace App\Controllers;

use App\Database\Database;
use App\Services\FavoriteService;
use PDO;

class FavoriteController {
    private $db;
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService) {
        $this->db = Database::getInstance()->getConnection();
        $this->favoriteService = $favoriteService;
    }

    public function addToFavorites($userId, $articleId, $title, $url) {

        $result = $this->favoriteService->addFavorite($userId, $articleId, $title, $url);
        if ($result) {
            return ["message" => "Article added to favorites."];
        } else {
            http_response_code(500);
            return ["error" => "Failed to add article."];
        }
    }

    public function getFavorites($userId, $limit, $offset) {
        return $this->favoriteService->getFavorites($userId, $limit, $offset);
    }
    
    public function getFavoritesCount($userId) {
        return $this->favoriteService->getFavoritesCount($userId);
    }
    

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
