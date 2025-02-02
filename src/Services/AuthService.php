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

    /**
     * Registers a new user
     *
     * @param string $email    The new user's email address
     * @param string $password The new user's password
     * @param string $token     The JWT token used to authenticate the registration
     *
     * @return array The newly created user's data
     *
     * @throws Exception If the user already exists
     */
    public function register($email, $password) {
        
        return $this->userModel->createUser($email, $password);
    }
    
    /**
     * Logs in a user
     *
     * @param string $email    The user's email address
     * @param string $password The user's password
     * @param string $token     The JWT token used to authenticate the login
     *
     * @return string The JWT token for the logged in user
     *
     * @throws Exception If the user is not found or the credentials are invalid
     */
    public function login($email, $password, $token) {

        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            throw new Exception('User not found.');
        }

        $password = trim($password);
    
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception('Invalid credentials');
        }

        $payload = [
            'iat' => time(),
            'exp' => time() + 8600,
            'sub' => $user['id']
        ];
    
        $jwt = JWT::encode($payload, $token, 'HS256');
        return $jwt;
    }
    
}
