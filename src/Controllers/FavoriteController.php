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

    public function getFavorites($userId) {
        $favorites = $this->favoriteService->getFavorites($userId);
        return $favorites;
    }

    public function removeFavorite($userId, $articleId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :user_id AND article_id = :article_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':article_id', $articleId);

        if ($stmt->execute()) {
            return ["message" => "Article removed from favorites."];
        } else {
            http_response_code(500);
            return ["error" => "Failed to remove article."];
        }
    }
}
