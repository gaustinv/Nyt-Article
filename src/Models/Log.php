<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class Log {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Adds a log entry to the logs table in the database.
     *
     * @param int $userId The ID of the user performing the action.
     * @param string $action The action performed by the user.
     * @return bool Whether the log entry was successfully added to the database.
     */
    public function addLog($userId, $action) {
        $stmt = $this->db->prepare("INSERT INTO logs (user_id, action) VALUES (:user_id, :action)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':action', $action);
        return $stmt->execute();
    }

    /**
     * Fetches all logs from the database.
     *
     * @return array All log entries.
     */
    public function getLogs() {
        $stmt = $this->db->query("SELECT * FROM logs ORDER BY timestamp DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
