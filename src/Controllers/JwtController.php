<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

// Include the configuration file
$config = require_once realpath('../../../config/config.php');

class JwtController {
    private static $secretKey = '';  // Declare as static

    // Initialize the secret key from config
    public static function init() {
        global $config;
        if (isset($config['jwt']['secret_key'])) {
            self::$secretKey = $config['jwt']['secret_key'];  // ✅ Set the secret key from config
        } else {
            throw new Exception("Secret key is not set in the configuration.");
        }
    }

    // Function to decode the JWT
    public static function decode($jwt) {
        try {
            if (empty(self::$secretKey)) {
                throw new Exception("Secret key is not initialized.");
            }
            $decoded = JWT::decode($jwt, new Key(self::$secretKey, 'HS256'));  // ✅ Correct usage
            return (array) $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
            exit();
        }
    }

    // Function to encode a JWT
    public static function encode($data) {
        global $config;

        if (empty(self::$secretKey)) {
            throw new Exception("Secret key is not initialized.");
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + ($config['jwt']['expiration_time'] ?? 3600);  // Default to 1 hour
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');  // ✅ Added algorithm parameter
    }
}

// Initialize JWT helper with the secret key
JwtController::init();
