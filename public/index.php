<?php
$requestUri = $_SERVER['REQUEST_URI'];

//print_r($requestUri);exit;

if (strpos($requestUri, '/api/auth/login') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../api/auth/login.php'; // Include login route
} elseif (strpos($requestUri, '/api/auth/register') !== false && $_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../api/auth/register.php'; // Include register route
} else {
    echo "Page not found!";
    header("location:/login.html");
}
?>
