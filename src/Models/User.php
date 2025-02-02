<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Creates a new user in the database
     *
     * @param string $email    The user's email address
     * @param string $password The user's password
     *
     * @return boolean True if the user was created successfully, false otherwise
     */
    public function createUser($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }

    /**
     * Retrieves a user from the database by their email address.
     *
     * @param string $email The email address of the user to retrieve.
     *
     * @return array|false An associative array containing the user's id, email,
     *                     and password, or false if no user was found.
     */
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT id, email, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a user from the database by their ID.
     *
     * @param int $userId The ID of the user to retrieve.
     *
     * @return array|false An associative array containing the user's id and email,
     *                     or false if no user was found.
     */
    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT id, email FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
