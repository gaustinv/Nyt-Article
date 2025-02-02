<?php
namespace App\Controllers;

use App\Database\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Services\AuthService;
use PDO;

$config = require_once realpath('../../../config/config.php'); // Update path if needed

class AuthController {
    private $db;
    private $secretKey;
    protected $userService;

    public function __construct(AuthService $userService) {
        global $config;
        $this->db = Database::getInstance()->getConnection();
        $this->secretKey = $config['jwt']['secret_key'];
        $this->userService = $userService;
    }

    public function register($email, $password) {
        
        // Call the createUser method in AuthService
        $register = $this->userService->register($email, $password, $this->secretKey);
    
        // Check the result from the createUser method
        if ($register) { 
            return ["message" => "Registration successful."];
        } else {
            http_response_code(500);
            return ["error" => "Registration failed."];
        }
    }

    public function login($email, $password) {
        try {
            // Assuming the login logic is handled by AuthService
            $login = $this->userService->login($email, $password, $this->secretKey);
    
            // If login is successful, return the JWT token in a JSON format
            echo json_encode(["token" => $login]);
        } catch (Exception $e) {
            // If there's an error, return an error message with a status code
            http_response_code(401);  // Unauthorized error code
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit();
    }
}
