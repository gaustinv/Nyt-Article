<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Services\AuthService;

$config = require_once realpath('../../../config/config.php'); // Update path if needed

class AuthController {
    private $secretKey;
    protected $userService;

    /**
     * Constructor for the AuthController class
     *
     * @param AuthService $userService Dependency injection of the user service
     *
     * @return void
     */
    public function __construct(AuthService $userService) {
        global $config;
        $this->secretKey = $config['jwt']['secret_key'];
        $this->userService = $userService;
    }

    /**
     * Registers a new user
     *
     * @param string $email     The new user's email
     * @param string $password  The new user's password
     *
     * @return array  The response message
     */

    public function register($email, $password) {
        $register = $this->userService->register($email, $password);
        if ($register) { 
            return ["message" => "Registration successful."];
        } else {
            http_response_code(500);
            return ["error" => "Registration failed."];
        }
    }

    public function login($email, $password) {
        try {
            $login = $this->userService->login($email, $password, $this->secretKey);
            echo json_encode(["token" => $login]);
        } catch (Exception $e) {
            http_response_code(401);  // Unauthorized error code
            echo json_encode(["error" => $e->getMessage()]);
        }
        exit();
    }
}
