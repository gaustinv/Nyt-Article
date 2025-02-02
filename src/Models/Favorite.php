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

    public function getFavoritesByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeFavorite($userId, $articleId) {
        $stmt = $this->db->prepare("DELETE FROM favorites WHERE user_id = :user_id AND article_id = :article_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':article_id', $articleId);
        return $stmt->execute();
    }
}
