<?php
session_start(); 
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'rental-mobil';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!function_exists('sanitize')) {
    function sanitize($data) {
        global $conn;
        return htmlspecialchars(strip_tags($conn->real_escape_string($data)));
    }
}

if (!function_exists('generateToken')) {
    function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = sanitize($_COOKIE['remember_token']);
    $sql = "SELECT * FROM users WHERE token = '$token'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
?>