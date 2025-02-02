<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class Favorite {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Adds an article to the user's favorites in the database.
     *
     * @param int $userId The ID of the user.
     * @param int $articleId The ID of the article to be added.
     * @param string $title The title of the article.
     * @param string $url The URL of the article.
     *
     * @return bool True on successful insertion, false otherwise.
     */
    public function addFavorite($userId, $articleId, $title, $url) {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, article_id, title, url) VALUES (:user_id, :article_id, :title, :url)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':article_id', $articleId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':url', $url);
        return $stmt->execute();
    }

    /**
     * Retrieves the user's favorites from the database.
     *
     * @param int $userId The ID of the user.
     * @param int $limit The number of records to retrieve.
     * @param int $offset The offset for pagination.
     *
     * @return array|null The user's favorites, or null if none are found.
     */
    public function getFavoritesByUser($userId, $limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = :user_id LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Retrieves the total count of the user's favorites from the database.
     *
     * @param int $userId The ID of the user.
     *
     * @return int The total count of the user's favorites.
     */
    public function getFavoritesCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    /**
     * Removes an article from the user's favorites in the database.
     *
     * @param int $userId The ID of the user.
     * @param int $favoriteId The ID of the favorite to be removed.
     *
     * @return bool True on successful deletion, false otherwise.
     */
    public function removeFavorite($userId, $favoriteId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :user_id AND id = :favorite_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':favorite_id', $favoriteId);
        return $stmt->execute();
    }    
}
