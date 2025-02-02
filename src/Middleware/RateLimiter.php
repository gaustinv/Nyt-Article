<?php
namespace App\Middleware;

use App\Database;
use PDO;

class RateLimiter {
    private $db;
    private $limit = 5;  // Limit to 5 requests
    private $timeFrame = 5 * 60;  // 5 minutes in seconds

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function checkRateLimit($userId) {
        // Fetch count of requests made by the user in the last 5 minutes
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM api_requests WHERE user_id = :user_id AND timestamp > :time_limit");
        $timeLimit = time() - $this->timeFrame;
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':time_limit', $timeLimit);
        $stmt->execute();

        $requestCount = $stmt->fetchColumn();

        if ($requestCount >= $this->limit) {
            return false; // Rate limit exceeded
        }

        return true; // Allow the request
    }

    public function logRequest($userId) {
        // Log the request in the database
        $stmt = $this->db->prepare("INSERT INTO api_requests (user_id, timestamp) VALUES (:user_id, :timestamp)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':timestamp', time());
        $stmt->execute();
    }
}
