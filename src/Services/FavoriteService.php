<?php
namespace App\Services;

use App\Models\Favorite;
use Exception;

class FavoriteService {
    private $favoriteModel;

    public function __construct() {
        $this->favoriteModel = new Favorite();
    }

    // Add article to favorites
    public function addFavorite($userId, $articleId, $title, $url) {
        return $this->favoriteModel->addFavorite($userId, $articleId, $title, $url);
    }

    // Remove article from favorites
    public function removeFavorite($userId, $articleId) {
        return $this->favoriteModel->removeFavorite($userId, $articleId);
    }

    // Get all favorites for a user
    public function getFavorites($userId) {
        return $this->favoriteModel->getFavoritesByUser($userId);
    }
}
