<?php
namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Exception;

class AuthService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Register a new user
    public function register($email, $password, $token) {
        
        return $this->userModel->createUser($email, $password, $token);
    }

    // Login user and generate JWT token
    public function login($email, $password, $token) {
        // Get the user by email
        $user = $this->userModel->getUserByEmail($email);
        
        // Check if the user exists
        if (!$user) {
            throw new Exception('User not found.');
        }
        // Trim spaces just in case
        $password = trim($password);
    
        // Verify the password
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception('Invalid credentials');
        }
    
        // Generate JWT token
        $payload = [
            'iat' => time(),
            'exp' => time() + 8600, // Token expires in 1 hour
            'sub' => $user['id']
        ];
    
        // Encode the payload into a JWT
        $jwt = JWT::encode($payload, $token, 'HS256');
        return $jwt;
    }
    
    
    // Verify JWT token
    public function verifyToken($jwt) {
        try {
            $decoded = JWT::decode($jwt, 'your-secret-key', ['HS256']);
            return $decoded->sub;
        } catch (Exception $e) {
            return false;
        }
    }
}
