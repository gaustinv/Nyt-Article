<?php
namespace App\Services;

use App\Models\Favorite;
use Exception;

class FavoriteService {
    private $favoriteModel;

    public function __construct() {
        $this->favoriteModel = new Favorite();
    }

    /**
     * Adds an article to the user's favorites.
     *
     * @param int $userId
     * @param int $articleId
     * @param string $title
     * @param string $url
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function addFavorite($userId, $articleId, $title, $url) {
        return $this->favoriteModel->addFavorite($userId, $articleId, $title, $url);
    }

    /**
     * Removes an article from the user's favorites.
     *
     * @param int $userId The ID of the user.
     * @param int $favoriteId The ID of the favorite to be removed.
     *
     * @return bool True on successful removal, false otherwise.
     *
     * @throws \Exception If the removal process fails.
     */
    public function removeFavorite($userId, $favoriteId) {
        return $this->favoriteModel->removeFavorite($userId, $favoriteId);
    }

    /**
     * Retrieves the user's favorites.
     *
     * @param int $userId The ID of the user.
     * @param int $limit The number of records to retrieve.
     * @param int $offset The offset for pagination.
     *
     * @return array|null The user's favorites, or null if none are found.
     */
    public function getFavorites($userId, $limit, $offset) {
        return $this->favoriteModel->getFavoritesByUser($userId, $limit, $offset);
    }
    
    /**
     * Retrieves the total count of the user's favorites.
     *
     * @param int $userId The ID of the user.
     *
     * @return int The total count of the user's favorites.
     */
    public function getFavoritesCount($userId) {
        return $this->favoriteModel->getFavoritesCount($userId);
    }
    
}
