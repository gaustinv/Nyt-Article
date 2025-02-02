<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class Favorite {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addFavorite($userId, $articleId, $title, $url) {
        $stmt = $this->db->prepare("INSERT INTO favorites (user_id, article_id, title, url) VALUES (:user_id, :article_id, :title, :url)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':article_id', $articleId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':url', $url);
        return $stmt->execute();
    }

    public function getFavoritesByUser($userId, $limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = :user_id LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getFavoritesCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public function removeFavorite($userId, $favoriteId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :user_id AND id = :favorite_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':favorite_id', $favoriteId);
        return $stmt->execute();
    }    
}
