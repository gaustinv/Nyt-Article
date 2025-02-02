<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

// Include the configuration file
$config = require_once realpath('../../../config/config.php');

class JwtController {
    private static $secretKey = ''; 
    public static function init() {
        global $config;
        if (isset($config['jwt']['secret_key'])) {
            self::$secretKey = $config['jwt']['secret_key'];
        } else {
            throw new Exception("Secret key is not set in the configuration.");
        }
    }

    /**
     * Decodes a JWT token and returns the payload as an associative array.
     *
     * @param string $jwt The JWT token to decode.
     *
     * @return array The payload of the JWT token.
     *
     * @throws Exception If the secret key is not initialized or if the token is invalid.
     */
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

    /**
     * Encodes an associative array as a JWT token.
     *
     * @param array $data The data to encode into the JWT token.
     *
     * @return string The JWT token.
     *
     * @throws Exception If the secret key is not initialized.
     */
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
