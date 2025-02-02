
<?php
require_once '../../../vendor/autoload.php'; 
require_once '../../../src/Controllers/AuthController.php';  
require_once '../../../src/Services/AuthService.php';  

use App\Services\AuthService;
use App\Controllers\AuthController;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawData = file_get_contents("php://input");
    // Decode the JSON string to a PHP object
    $data = json_decode($rawData);
    
    // Retrieve values safely
    $email = isset($data->email) ? trim($data->email) : null;
    $password = isset($data->password) ? trim($data->password) : null;

    
    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(["error" => "Email and password are required"]);
        exit;
    }

    $userService = new AuthService();
    $authController = new AuthController($userService);
    $response = $authController->register(trim($email), trim($password));
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}
?>
